<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

/**
 * Database queries for pages.
 * @see BxDolPage
 */
class BxDolPageQuery extends BxDolDb
{
    protected $_aObject;

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getPageObject ($sObject)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT `o`.*, `l`.`template`, `l`.`cells_number` FROM `sys_objects_page` AS `o` INNER JOIN `sys_pages_layouts` AS `l` ON (`l`.`id` = `o`.`layout_id`) WHERE `o`.`object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }

    static public function getPageObjectNameByURI($sURI)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT `object` FROM `sys_objects_page` WHERE `uri` = ?", $sURI);
        return $oDb->getOne($sQuery);
    }

	static public function getPageTriggers($sTriggerName)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_pages_blocks` WHERE `object` = ? ORDER BY `id` ASC", $sTriggerName);
        return $oDb->getAll($sQuery);
    }

    static public function addPageBlockToPage($aPageBlock)
    {
        $oDb = BxDolDb::getInstance();

        if (empty($aPageBlock['object']))
            return false;

        if (empty($aPageBlock['order'])) {
        	$iCellId = !empty($aPageBlock['cell_id']) ? (int)$aPageBlock['cell_id'] : 1;
            $sQuery = $oDb->prepare("SELECT `order` FROM `sys_pages_blocks` WHERE `object` = ? AND `cell_id` = ? AND `active` = 1 ORDER BY `order` DESC LIMIT 1", $aPageBlock['object'], $iCellId);
            $aPageBlock['order'] = (int)$oDb->getOne($sQuery) + 1;
        }

        $sQuery = $oDb->prepare("DELETE FROM `sys_pages_blocks` WHERE `object` = ? AND `type` = ? AND `title` = ?", $aPageBlock['object'], $aPageBlock['type'], $aPageBlock['title']);
        $oDb->query($sQuery);

        unset($aPageBlock['id']);
        return $oDb->query("INSERT INTO `sys_pages_blocks` SET " . $oDb->arrayToSQL($aPageBlock));
    }

    public function getPageBlocks()
    {
        $aRet = array ();
        for ($i = 1 ; $i <= $this->_aObject['cells_number'] ; ++$i) {
            $sQuery = $this->prepare("SELECT * FROM `sys_pages_blocks` WHERE `object` = ? AND `cell_id` = ? AND `active` = 1 ORDER BY `order` ASC", $this->_aObject['object'], $i);
            $aRet['cell_'.$i] = $this->getAll($sQuery);
        }
        return $aRet;
    }

    public function getPageBlock($iBlockId)
    {
        $sQuery = $this->prepare("SELECT * FROM `sys_pages_blocks` WHERE `object` = ? AND `id` = ?", $this->_aObject['object'], $iBlockId);
        return $this->getRow($sQuery);
    }

    public function getPageBlockContent($iId)
    {
        $sQuery = $this->prepare("SELECT `content` FROM `sys_pages_blocks` WHERE `id` = ?", $iId);
        return $this->getOne($sQuery);
    }

}

/** @} */
