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

    public function getBlockContent ($iBlockId, $sLang, $iRevision = false)
    {
        $sWhere = '';
        $aBind = array('block' => $iBlockId, 'lang' => $sLang);
        if (false !== $iRevision) {
            $sWhere = " AND `revision` = :rev";
            $aBind['rev'] = $iRevision;
        }

        // get latest revision for specific language
        $aRow = $this->getRow("SELECT `block_id`, `revision`, `lang`, `main_lang`, `content`, `unsafe`, `notes`, `added` FROM `sys_pages_wiki_blocks` WHERE `block_id` = :block AND `lang` = :lang $sWhere ORDER BY `revision` DESC LIMIT 1", $aBind);

        // if translation isn't found for specific language then get latest revision for main language 
        if (!$aRow) {
            unset($aBind['lang']);
            $aRow = $this->getRow("SELECT `block_id`, `revision`, `lang`, `main_lang`, `content`, `unsafe`, `notes`, `added` FROM `sys_pages_wiki_blocks` WHERE `block_id` = :block AND `main_lang` = 1 $sWhere ORDER BY `revision` DESC LIMIT 1", $aBind);
        }

        return $aRow ? $aRow : false;
    }
}

/** @} */
