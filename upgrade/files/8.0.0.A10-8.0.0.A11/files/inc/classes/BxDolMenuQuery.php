<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

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

        if (empty($aMenuItem['order'])) {
            $sQuery = $oDb->prepare("SELECT `order` FROM `sys_menu_items` WHERE `set_name` = ? AND `active` = 1 AND `order` != 9999 ORDER BY `order` DESC LIMIT 1", $aMenuItem['set_name']);
            $iProfileMenuOrder = (int)$oDb->getOne($sQuery);
            $aMenuItem['order'] = $iProfileMenuOrder + 1;
        }

        $sQuery = $oDb->prepare("DELETE FROM `sys_menu_items` WHERE `set_name` = ? AND `name` = ?", $aMenuItem['set_name'], $aMenuItem['name']);
        $oDb->query($sQuery);

        unset($aMenuItem['id']);
        return $oDb->query("INSERT INTO `sys_menu_items` SET " . $oDb->arrayToSQL($aMenuItem));
    }

    public function getMenuItems()
    {
        $sQuery = $this->prepare("SELECT * FROM `sys_menu_items` WHERE `set_name` = ? ORDER BY `order` ASC", $this->_aObject['set_name']);
        return $this->getAll($sQuery);
    }

}

/** @} */
