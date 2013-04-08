<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolDb');

/**
 * Database queries for menus.
 * @see BxDolMenu
 */
class BxDolMenuQuery extends BxDolDb {
    protected $_aObject;

    public function __construct($aObject) {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getMenuObject ($sObject) {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT `o`.*, `t`.`template` FROM `sys_objects_menu` AS `o` INNER JOIN `sys_menu_templates` AS `t` ON (`t`.`id` = `o`.`template_id`) WHERE `o`.`object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);        
        if (!$aObject || !is_array($aObject)) 
            return false;
        
        return $aObject;
    }


    public function getMenuItems() {
        $sQuery = $this->prepare("SELECT * FROM `sys_menu_items` WHERE `set_name` = ? ORDER BY `order` ASC", $this->_aObject['set_name']);
        return $this->getAll($sQuery);
    }

}

/** @} */
