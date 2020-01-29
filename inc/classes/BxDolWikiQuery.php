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

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
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
        $aBind = array('block' => $iBlockId, 'language' => $sLang);
        if (false !== $iRevision) {
            $sWhere = " AND `revision` = :rev";
            $aBind['rev'] = $iRevision;
        }

        // get latest revision for specific language
        $aRow = $this->getRow("SELECT `block_id`, `revision`, `profile_id`, `language`, `main_lang`, `content`, `unsafe`, `notes`, `added` FROM `sys_pages_wiki_blocks` WHERE `block_id` = :block AND `language` = :language $sWhere ORDER BY `revision` DESC LIMIT 1", $aBind);

        // if translation isn't found for specific language then get latest revision for main language 
        if ($bAutoMainLang && !$aRow) {
            unset($aBind['language']);
            $aRow = $this->getRow("SELECT `block_id`, `revision`, `profile_id`, `language`, `main_lang`, `content`, `unsafe`, `notes`, `added` FROM `sys_pages_wiki_blocks` WHERE `block_id` = :block AND `main_lang` = 1 $sWhere ORDER BY `revision` DESC LIMIT 1", $aBind);
        }

        return $aRow ? $aRow : false;
    }

    public function getBlockHistory ($iBlockId, $sLang)
    {
        $aBind = array('block' => $iBlockId, 'language' => $sLang);
        return $this->getAll("SELECT `block_id`, `language`, `revision`, `profile_id`, `notes`, `added` FROM `sys_pages_wiki_blocks` WHERE `block_id` = :block AND `language` = :language ORDER BY `revision` DESC", $aBind);
    }

    public function deleteRevisions ($iBlockId, $sLang, $aRevisions)
    {
        $aBind = array('block' => $iBlockId, 'language' => $sLang);
        $i = $this->query("DELETE FROM `sys_pages_wiki_blocks` WHERE `block_id` = :block AND `language` = :language AND `revision` IN(" . $this->implode_escape($aRevisions) . ")", $aBind);
        if ($i) { 
            // check if main language revisions was deleted
            $aRow = $this->getOne("SELECT `block_id` FROM `sys_pages_wiki_blocks` WHERE `block_id` = :block AND `main_lang` = 1 LIMIT 1", array('block' => $iBlockId));
            if (!$aRow) {
                // if main lang was deleted then mark latest translation from any lang as main
                $this->query("UPDATE `sys_pages_wiki_blocks` SET `main_lang` = 1 WHERE `block_id` = :block ORDER BY `added` DESC LIMIT 1", array('block' => $iBlockId));
            }
        }
        return $i;
    }

    public static function deleteAllRevisions ($mixedBlockIds)
    {
        $oDb = BxDolDb::getInstance();
        if (!is_array($mixedBlockIds))
            $mixedBlockId = array($mixedBlockIds);
        return $oDb->query("DELETE FROM `sys_pages_wiki_blocks` WHERE `block_id` IN(" . $oDb->implode_escape($mixedBlockIds) . ")");
    }
}

/** @} */
