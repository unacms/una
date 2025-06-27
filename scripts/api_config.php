<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCoreScripts Scripts
 * @{
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

class BxDolApiConfig
{
    protected $_oDb;

    public function __construct() 
    {
        $this->_oDb = BxDolDb::getInstance();
    }

    public function getQueries()
    {
        $sResult = $this->_getQueriesPages();
        $sResult .= $this->_getQueriesMenus();

        echo "<pre>" . $sResult . "</pre>";
    }
    
    protected function _getQueriesPages()
    {
        $aPages = $this->_oDb->getAll("SELECT `object`, `config_api` FROM `sys_objects_page` WHERE `config_api` <> '' ORDER BY `id` ASC");

        $sResult = "\n\n-- PAGES:\n";
        foreach($aPages as $aPage)
            $sResult .= $this->_oDb->prepareAsString("UPDATE `sys_objects_page` SET `config_api`=? WHERE `object`=?;\n", $aPage['config_api'], $aPage['object']);

        $aBlocks = $this->_oDb->getAll("SELECT `object`, `module`, `title_system`, `title`, `config_api` FROM `sys_pages_blocks` WHERE `config_api` <> '' ORDER BY `id` ASC");
        
        $sResult .= "\n\n-- PAGE BLOCKS:\n";
        foreach($aBlocks as $aBlock)
            $sResult .= $this->_oDb->prepareAsString("UPDATE `sys_pages_blocks` SET `config_api`=? WHERE `object`=? AND `module`=? AND `title_system`=? AND `title`=?;\n", $aBlock['config_api'], $aBlock['object'], $aBlock['module'], $aBlock['title_system'], $aBlock['title']);

        return $sResult;
    }
    
    protected function _getQueriesMenus()
    {
        $aMenus = $this->_oDb->getAll("SELECT `object`, `config_api` FROM `sys_objects_menu` WHERE `config_api` <> '' ORDER BY `id` ASC");

        $sResult = "\n\n-- MENUS:\n";
        foreach($aMenus as $aMenu)
            $sResult .= $this->_oDb->prepareAsString("UPDATE `sys_objects_menu` SET `config_api`=? WHERE `object`=?;\n", $aMenu['config_api'], $aMenu['object']);

        $aItems = $this->_oDb->getAll("SELECT `set_name`, `module`, `name`, `config_api` FROM `sys_menu_items` WHERE `config_api` <> '' ORDER BY `id` ASC");

        $sResult .= "\n\n-- MENU ITEMS:\n";
        foreach($aItems as $aItem)
            $sResult .= $this->_oDb->prepareAsString("UPDATE `sys_menu_items` SET `config_api`=? WHERE `set_name`=? AND `module`=? AND `name`=?;\n", $aItem['config_api'], $aItem['set_name'], $aItem['module'], $aItem['name']);

        return $sResult;
    }
}

$o = new BxDolApiConfig();
$o->getQueries();

/** @} */
