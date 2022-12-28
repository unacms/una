<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for WIKI objects.
 * @see BxDolWiki
 */
class BxDolWikiQuery extends BxDolDb
{
    protected $_aObject;
    protected $_sTableBlocksWithRevisions = 'sys_pages_wiki_blocks';

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getBlocks ($sModule)
    {
        $oDb = BxDolDb::getInstance();
        return $oDb->getColumn("SELECT `id` FROM `sys_pages_blocks` WHERE `module` = :module", ['module' => $sModule]);
    }

    static public function getWikiObjectByUri ($sUri)
    {
        return self::getWikiObjectByField ('uri', $sUri);
    }

    static public function getWikiObject ($sObject)
    {
        return self::getWikiObjectByField ('object', $sObject);
    }

    static public function getWikiObjectByField ($sField, $sValue)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_wiki` WHERE `$sField` = ?", $sValue);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }

    static public function getAllPages ()
    {
        $sQuery = "SELECT LOWER(`uri`) as `uri`, `title`, `url` FROM `sys_objects_page` WHERE `url` != '' ORDER BY `uri`";
        return BxDolDb::getInstance()->getAllWithKey($sQuery, 'uri');
    }

    /**
     * Get wiki block
     * @param $iBlockId block ID
     * @param $sLang 2 letters language code
     * @param $iRevision [optional, default false] revision number, if false get latest revision for then given lan
     * @param $bAutoMainLang [optional, default true] automatically load wiki block for main language if translation for gived language doesn't exist
     * @return array with wiki block info
     */
    public function getBlockContent ($iBlockId, $sLang, $iRevision = false, $bAutoMainLang = true)
    {        
        $sWhere = '';
        $aRow = false;
        $aBind = array('block' => $iBlockId);
        if (false !== $sLang) {            
            $aBind['language'] = $sLang;
            if (false !== $iRevision) {
                $sWhere = " AND `revision` = :rev";
                $aBind['rev'] = $iRevision;
            }

            // get latest revision for specific language
            $aRow = $this->getRow("SELECT `block_id`, `revision`, `profile_id`, `language`, `main_lang`, `content`, `unsafe`, `notes`, `added` FROM `{$this->_sTableBlocksWithRevisions}` WHERE `block_id` = :block AND `language` = :language $sWhere ORDER BY `revision` DESC LIMIT 1", $aBind);
        }

        // if translation isn't found for specific language then get latest revision for main language 
        if ($bAutoMainLang && !$aRow) {
            unset($aBind['language']);
            $aRow = $this->getRow("SELECT `block_id`, `revision`, `profile_id`, `language`, `main_lang`, `content`, `unsafe`, `notes`, `added` FROM `{$this->_sTableBlocksWithRevisions}` WHERE `block_id` = :block AND `main_lang` = 1 $sWhere ORDER BY `revision` DESC LIMIT 1", $aBind);
        }

        return $aRow ? $aRow : false;
    }

    public function getBlockLangs ($iBlockId)
    {
        return $this->getColumn("SELECT `language` FROM `{$this->_sTableBlocksWithRevisions}` WHERE `block_id` = :block GROUP BY `language`", array('block' => $iBlockId));
    }

    public function updateBlockIndexingData ($iBlockId, $sText)
    {
        return $this->query("UPDATE `sys_pages_blocks` SET `text` = :text, `text_updated` = :updated WHERE `id` = :id", array('text' => $sText, 'updated' => time(), 'id' => $iBlockId));
    }

    public function getBlockHistory ($iBlockId, $sLang)
    {
        $aBind = array('block' => $iBlockId, 'language' => $sLang);
        return $this->getAll("SELECT `block_id`, `language`, `revision`, `profile_id`, `notes`, `added` FROM `{$this->_sTableBlocksWithRevisions}` WHERE `block_id` = :block AND `language` = :language ORDER BY `revision` DESC", $aBind);
    }

    public function getBlocksWithMissingTranslations ($sLang)
    {
        $aBind = array('lang' => $sLang, 'module' => $this->_aObject['module']);
        return $this->getColumn("
            SELECT `wo`.`block_id`
            FROM `{$this->_sTableBlocksWithRevisions}` AS `wo`
            INNER JOIN `sys_pages_blocks` AS `b` ON (`wo`.`block_id` = `b`.`id` AND `b`.`module` = :module)
            LEFT JOIN `{$this->_sTableBlocksWithRevisions}` AS `wt` ON (`wo`.`block_id` = `wt`.`block_id` AND `wt`.`language` = :lang AND `wt`.`main_lang` != 1)            
            WHERE `wo`.`main_lang` = 1 AND `wo`.`language` != :lang AND `wt`.`block_id` IS NULL
            GROUP BY `wo`.`block_id`", $aBind);
    }

    public function getBlocksWithOutdatedTranslations ($sLang)
    {
        $aBind = array('lang' => $sLang, 'module' => $this->_aObject['module']);
        return $this->getColumn("
            SELECT `wo`.`block_id`
            FROM `{$this->_sTableBlocksWithRevisions}` AS `wo`
            INNER JOIN `sys_pages_blocks` AS `b` ON (`wo`.`block_id` = `b`.`id` AND `b`.`module` = :module)
            INNER JOIN `{$this->_sTableBlocksWithRevisions}` AS `wt` ON (`wo`.`block_id` = `wt`.`block_id` AND `wt`.`language` = :lang AND `wt`.`main_lang` != 1)
            WHERE `wo`.`main_lang` = 1 AND `wo`.`language` != :lang
            GROUP BY `wo`.`block_id`
            HAVING MAX(`wt`.`added`) < MAX(`wo`.`added`)", $aBind);
    }

    public function deleteRevisions ($iBlockId, $sLang, $aRevisions)
    {
        $aBind = array('block' => $iBlockId, 'language' => $sLang);
        $i = $this->query("DELETE FROM `{$this->_sTableBlocksWithRevisions}` WHERE `block_id` = :block AND `language` = :language AND `revision` IN(" . $this->implode_escape($aRevisions) . ")", $aBind);
        if ($i) {
            // check if main language revisions was deleted
            $aRow = $this->getOne("SELECT `block_id` FROM `{$this->_sTableBlocksWithRevisions}` WHERE `block_id` = :block AND `main_lang` = 1 LIMIT 1", array('block' => $iBlockId));
            if (!$aRow) {
                // if main lang was deleted then mark latest translation from any lang as main
                $this->query("UPDATE `{$this->_sTableBlocksWithRevisions}` SET `main_lang` = 1 WHERE `block_id` = :block ORDER BY `added` DESC LIMIT 1", array('block' => $iBlockId));
            }
        }
        return $i;
    }

    public function getPageByBlockId ($iBlockId)
    {
        return $this->getRow("SELECT `p`.`title`, `p`.`uri` FROM `sys_objects_page` AS `p` INNER JOIN `sys_pages_blocks` AS `b` ON (`p`.`object` = `b`.`object` AND `b`.`id` = ? AND `p`.`module` = ?) LIMIT 1", array($iBlockId, $this->_aObject['module']));
    }

    public function getPages ($aAllExceptSpecified = array(), $aOnlySpecified = array())
    {
        $sWhere = '';
        if ($aOnlySpecified)
            $sWhere = " AND `uri` IN(" . $this->implode_escape($aOnlySpecified) . ")";
        elseif ($aAllExceptSpecified)
            $sWhere = " AND `uri` NOT IN(" . $this->implode_escape($aAllExceptSpecified) . ")";
        return $this->getAll("SELECT `title`, `uri`, `object` FROM `sys_objects_page` WHERE `module` = ?" . $sWhere, array($this->_aObject['module']));
    }

    public function insertPage ($sUri, $sUrl, $sTitleLangKey, $iType = 1, $iLayoutId = 20, $iVisibleForLevels = 2147483647, $sClass = 'BxTemplPageWiki')
    {
        $oQueryPageBuilder = new BxDolStudioBuilderPageQuery();
        $iPageId = $oQueryPageBuilder->insertPage($this->_aObject['module'] . '_' . str_replace('-', '_', $sUri), $this->_aObject['module'], $sUri, $sUrl, $sTitleLangKey, $iType, $iLayoutId, $iVisibleForLevels, $sClass);
        if ($iPageId)
            $oQueryPageBuilder->updatePage($iPageId, ['sticky_columns' => 1]);
        return $iPageId;
    }

    public static function deleteAllRevisions ($mixedBlockIds)
    {
        $oDb = BxDolDb::getInstance();
        if (!is_array($mixedBlockIds))
            $mixedBlockId = array($mixedBlockIds);

        // TODO: remake to use $this->_sTableBlocksWithRevisions
        return $oDb->query("DELETE FROM `sys_pages_wiki_blocks` WHERE `block_id` IN(" . $oDb->implode_escape($mixedBlockIds) . ")");
    }
}

/** @} */
