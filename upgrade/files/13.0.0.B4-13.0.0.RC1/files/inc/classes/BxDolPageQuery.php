<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
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

    static public function getPageObjectNameByURI($sURI, $sModule = false, $bSearchRedirects = false)
    {
        $oDb = BxDolDb::getInstance();
        $a = array('uri' => $sURI);
        $sQuery = "SELECT `object` FROM `sys_objects_page` WHERE `uri` = :uri";
        if ($sModule) {
            $a['module'] = $sModule;
            $sQuery .= " AND `module` = :module";
        }
        $sObject = $oDb->getOne($sQuery, $a);

        if ($bSearchRedirects && !$sObject) {
            $sQuery = "SELECT `p`.`object` FROM `sys_objects_page` AS `p` INNER JOIN `sys_seo_uri_rewrites` AS `r` ON (`p`.`uri` = `r`.`uri_orig`) WHERE `r`.`uri_rewrite` = :uri";
            if ($sModule) {
                $a['module'] = $sModule;
                $sQuery .= " AND `module` = :module";
            }
            $sObject = $oDb->getOne($sQuery, $a);
        }

        return $sObject;
    }
    
    static public function getContentInfoObjectNameByURI($sURI)
    {
        $oDb = BxDolDb::getInstance();
        $a = array('uri' => $sURI);
        $sQuery = "SELECT `module`, `content_info` FROM `sys_objects_page` WHERE `uri` = :uri";
        $aRow = $oDb->getRow($sQuery, $a);
        if ($aRow['content_info'] != '') 
            return $aRow['content_info'];
        
        return $aRow['module'];
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

        // check if block already exists, 
        // so the block position will not reset when it's unnecessary
        $sQuery = $oDb->prepare("SELECT `id` FROM `sys_pages_blocks` WHERE `object` = ? AND `type` = ? AND `title` = ?", $aPageBlock['object'], $aPageBlock['type'], $aPageBlock['title']);
        if ($oDb->getOne($sQuery))
            return true;
        
        // get order
        if (empty($aPageBlock['order'])) {
        	$iCellId = !empty($aPageBlock['cell_id']) ? (int)$aPageBlock['cell_id'] : 1;
            $sQuery = $oDb->prepare("SELECT `order` FROM `sys_pages_blocks` WHERE `object` = ? AND `cell_id` = ? AND `active` = 1 ORDER BY `order` DESC LIMIT 1", $aPageBlock['object'], $iCellId);
            $aPageBlock['order'] = (int)$oDb->getOne($sQuery) + 1;
        }

        // add new block
        unset($aPageBlock['id']);
        return $oDb->query("INSERT INTO `sys_pages_blocks` SET " . $oDb->arrayToSQL($aPageBlock));
    }

    static public function getPageType($iId)
    {
        return BxDolDb::getInstance()->getRow("SELECT * FROM `sys_pages_types` WHERE `id`=:id LIMIT 1", [
            'id' => $iId
        ]);
    }

    static public function getPageTypes()
    {
        return BxDolDb::getInstance()->getAll("SELECT * FROM `sys_pages_types` WHERE 1");
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

    public function getPageBlockContentPlaceholder($iId)
    {
        $sQuery = $this->prepare("SELECT `id`, `module`, `template` FROM `sys_pages_content_placeholders` WHERE `id` = ?", $iId);
        return $this->getRow($sQuery);
    }

    static public function getSeoUriRewrites()
    {
        $oDb = BxDolDb::getInstance();
        return $oDb->fromMemory('sys_seo_uri_rewrites', 'getPairs', 'SELECT `uri_orig`, `uri_rewrite` FROM `sys_seo_uri_rewrites`', 'uri_orig', 'uri_rewrite');
    }

    static public function getSeoLink($sModule, $sPageUri, $aCond = [])
    {
        $oDb = BxDolDb::getInstance();
        $sWhere = " 1 ";
        if ($aCond)
            $sWhere = $oDb->arrayToSQL($aCond, " AND ");
        return $oDb->getRow("SELECT `uri`, `param_name`, `param_value` FROM `sys_seo_links` WHERE " . $sWhere . " AND `module` = :module AND `page_uri` = :page_uri", [
            'module' => $sModule,
            'page_uri' => $sPageUri,
        ]);
    }

    static public function insertSeoLink($sModule, $sPageUri, $sSeoParamName, $sSeoParamValue, $sUri)
    {
        return BxDolDb::getInstance()->query("INSERT INTO `sys_seo_links` SET `module` = :module, `page_uri` = :page_uri, `param_name` = :param_name, `param_value` = :param_value, `uri` = :uri, `added` = :ts", [
            'module' => $sModule,
            'page_uri' => $sPageUri,
            'param_name' => $sSeoParamName,
            'param_value' => $sSeoParamValue,
            'uri' => $sUri,
            'ts' => time(),
        ]);
    }

    static public function deleteSeoLink($sModule, $sContentInfoObject, $sId)
    {
        return BxDolDb::getInstance()->query("DELETE FROM `sys_seo_links` WHERE `module` = :module AND `page_uri` IN (SELECT `uri` FROM `sys_objects_page` WHERE `module` = :content_info OR `content_info` = :content_info) AND `param_value` = :param_value", [
            'module' => $sModule,
            'content_info' => $sContentInfoObject,
            'param_value' => $sId,
        ]);
    }

    static public function deleteSeoLinkByParam($sParamName, $sId)
    {
        return BxDolDb::getInstance()->query("DELETE FROM `sys_seo_links` WHERE `param_name` = :param_name AND `param_value` = :param_value", [
            'param_name' => $sParamName,
            'param_value' => $sId,
        ]);
    }

    static public function deleteSeoLinkByModule($sModule)
    {
        return BxDolDb::getInstance()->query("DELETE FROM `sys_seo_links` WHERE `module` = :module", [
            'module' => $sModule,
        ]);
    }
}

/** @} */
