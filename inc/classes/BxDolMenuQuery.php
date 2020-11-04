<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

define('BX_MENU_LAST_ITEM_ORDER', 9999);

/**
 * Database queries for menus.
 * @see BxDolMenu
 */
class BxDolMenuQuery extends BxDolDb
{
    protected $_aObject;

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getMenuObject ($sObject)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT `o`.*, `t`.`template` FROM `sys_objects_menu` AS `o` INNER JOIN `sys_menu_templates` AS `t` ON (`t`.`id` = `o`.`template_id`) WHERE `o`.`object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }

    static public function getMenuTriggers($sTriggerName)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_menu_items` WHERE `set_name` = ? ORDER BY `order` DESC", $sTriggerName);
        return $oDb->getAll($sQuery);
    }

    static public function addMenuItemToSet($aMenuItem)
    {
        $oDb = BxDolDb::getInstance();

        if (empty($aMenuItem['set_name']))
            return false;

        // check if menu item already exists, 
        // so the menu item position will not reset when it's unnecessary
        $sQuery = $oDb->prepare("SELECT `id` FROM `sys_menu_items` WHERE `set_name` = ? AND `name` = ?", $aMenuItem['set_name'], $aMenuItem['name']);
        if ($oDb->getOne($sQuery))
            return true;
      
        // get order
        if (empty($aMenuItem['order'])) {
            $sQuery = $oDb->prepare("SELECT `order` FROM `sys_menu_items` WHERE `set_name` = ? AND `active` = 1 AND `order` != ? ORDER BY `order` DESC LIMIT 1", $aMenuItem['set_name'], BX_MENU_LAST_ITEM_ORDER);
            $iProfileMenuOrder = (int)$oDb->getOne($sQuery);
            $aMenuItem['order'] = $iProfileMenuOrder + 1;
        }

        // add new item
        unset($aMenuItem['id']);
        return $oDb->query("INSERT INTO `sys_menu_items` SET " . $oDb->arrayToSQL($aMenuItem));
    }

    public function getMenuItems()
    {
        return $this->getMenuItemsFromSet($this->_aObject['set_name']);
    }

    public function getMenuItemsFromSet($sSetName)
    {
        $sQuery = $this->prepare("SELECT * FROM `sys_menu_items` WHERE `set_name` = ? ORDER BY `order` ASC", $sSetName);
        return $this->getAllWithKey($sQuery, 'name');
    }

    public function getMenuItemsBy($aParams = array())
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $aBindings = array();

    	$sSelectClause = '*';
    	$sWhereClause = $sGroupClause = $sOrderClause = '';
    	$sLimitClause = isset($aParams['start']) && !empty($aParams['per_page']) ? " LIMIT " . $aParams['start'] . ", " . $aParams['per_page'] : "";

    	if(!empty($aParams['type']))
            switch($aParams['type']) {
                case 'set_name':
                    $aBindings['set_name'] = $aParams['set_name'];

                    $sWhereClause = 'AND `set_name` = :set_name';
                    $sOrderClause = '`order` ASC';
                    break;
                    
                case 'set_name_duplicates':
                    $aMethod['name'] = 'getColumn';
                    $aBindings['set_name'] = $aParams['set_name'];

                    $sSelectClause = '`name`';
                    $sWhereClause = 'AND `set_name` = :set_name';
                    $sGroupClause = '`name` HAVING COUNT(`id`) > 1';
                    break;
            }

        if(!empty($sGroupClause))
            $sGroupClause = " GROUP BY " . $sGroupClause;

        if(!empty($sOrderClause))
            $sOrderClause = " ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `sys_menu_items` WHERE 1 " . $sWhereClause . $sGroupClause . $sOrderClause . $sLimitClause;
        $aMethod['params'][] = $aBindings;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
    
    public function getMenuTemplateById($iId, $bFromCache = true)
    {
        $sSql = $this->prepare("SELECT * FROM `sys_menu_templates` WHERE `id`=? LIMIT 1", $iId);
        return $bFromCache ? $this->fromMemory('sys_menu_templates_' . $iId, 'getRow', $sSql) : $this->getRow($sSql);
    }
}

/** @} */
