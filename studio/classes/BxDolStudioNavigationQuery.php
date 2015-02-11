<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioNavigationQuery extends BxDolDb
{
    function __construct()
    {
        parent::__construct();
    }

    function getMenus($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tm`.`id` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $sWhereClause = $this->prepare(" AND `tm`.`id`=? ", $aParams['value']);
                break;
            case 'by_object':
                $aMethod['name'] = 'getRow';
                $sWhereClause = $this->prepare(" AND `tm`.`object`=? ", $aParams['value']);
                break;
            case 'by_set_name':
                $sWhereClause = $this->prepare(" AND `tm`.`set_name`=? ", $aParams['value']);
                break;
            case 'counter_by_modules':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'module';
                $aMethod['params'][2] = 'counter';
                $sSelectClause = ", COUNT(*) AS `counter`";
                $sGroupClause = "GROUP BY `tm`.`module`";
                break;
            case 'all':
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tm`.`id` AS `id`,
                `tm`.`object` AS `object`,
                `tm`.`title` AS `title`,
                `tm`.`set_name` AS `set_name`,
                `tm`.`module` AS `module`,
                `tm`.`template_id` AS `template_id`,
                `tm`.`deletable` AS `deletable`,
                `tm`.`active` AS `active`,
                `tm`.`override_class_name` AS `override_class_name`,
                `tm`.`override_class_file` AS `override_class_file`" . $sSelectClause . "
            FROM `sys_objects_menu` AS `tm` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function isSetExists($sName)
    {
        $sSql = $this->prepare("SELECT COUNT(*) FROM `sys_menu_sets` WHERE `set_name`=? LIMIT 1", $sName);
        return (int)$this->getOne($sSql) == 1;
    }

    function getSets($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tms`.`title` ASC";

        switch($aParams['type']) {
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $sSelectClause = ", COUNT(`tmi`.`id`) AS `items_count`";
                $sJoinClause = "LEFT JOIN `sys_menu_items` AS `tmi` ON `tms`.`set_name`=`tmi`.`set_name` AND `tmi`.`active`='1'";
                $sWhereClause = $this->prepare(" AND `tms`.`set_name`=? ", $aParams['value']);
                $sGroupClause = "GROUP BY `tms`.`set_name`";
                break;
            case 'by_module':
                $sWhereClause = $this->prepare(" AND `tms`.`module`=? ", $aParams['value']);
                break;
            case 'counter_by_modules':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'module';
                $aMethod['params'][2] = 'counter';
                $sSelectClause = ", COUNT(*) AS `counter`";
                $sGroupClause = "GROUP BY `tms`.`module`";
                break;
            case 'dump_by_name':
                $aMethod['name'] = 'getRow';
                $sWhereClause = $this->prepare(" AND `tms`.`set_name`=? ", $aParams['value']);
                break;
            case 'all':
                if(isset($aParams['except']))
                    $sWhereClause = $this->prepare(" AND `tms`.`set_name`<>? ", $aParams['except']);
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tms`.`set_name` AS `name`,
                `tms`.`set_name` AS `set_name`,
                `tms`.`module` AS `module`,
                `tms`.`title` AS `title`,
                `tms`.`deletable` AS `deletable`" . $sSelectClause . "
            FROM `sys_menu_sets` AS `tms` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function addSet($aFields)
    {
        $sSql = "INSERT INTO `sys_menu_sets` SET `" . implode("`=?, `", array_keys($aFields)) . "`=?";
        $sSql = call_user_func_array(array($this, 'prepare'), array_merge(array($sSql), array_values($aFields)));
        return (int)$this->query($sSql) > 0;
    }

    function getTemplates($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tmt`.`title` ASC";

        switch($aParams['type']) {
            case 'all':
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tmt`.`id` AS `id`,
                `tmt`.`template` AS `template`,
                `tmt`.`title` AS `title`" . $sSelectClause . "
            FROM `sys_menu_templates` AS `tmt` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function getItems($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tmi`.`order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $sWhereClause = $this->prepare(" AND `tmi`.`id`=? ", $aParams['value']);
                break;
            case 'by_set_name':
                $sWhereClause = $this->prepare(" AND `tmi`.`set_name`=? ", $aParams['value']);
                break;
            case 'counter_by_sets':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'set_name';
                $aMethod['params'][2] = 'counter';
                $sSelectClause = ", COUNT(*) AS `counter`";
                $sGroupClause = "GROUP BY `tmi`.`set_name`";
                break;
            case 'counter_by_modules':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'module';
                $aMethod['params'][2] = 'counter';
                $sSelectClause = ", COUNT(*) AS `counter`";
                $sGroupClause = "GROUP BY `tmi`.`module`";
                break;
            case 'all':
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tmi`.`id` AS `id`,
                `tmi`.`set_name` AS `set_name`,
                `tmi`.`module` AS `module`,
                `tmi`.`name` AS `name`,
                `tmi`.`title_system` AS `title_system`,
                `tmi`.`title` AS `title`,
                `tmi`.`link` AS `link`,
                `tmi`.`onclick` AS `onclick`,
                `tmi`.`target` AS `target`,
                `tmi`.`icon` AS `icon`,
                `tmi`.`submenu_object` AS `submenu_object`,
                `tmi`.`visible_for_levels` AS `visible_for_levels`,
                `tmi`.`active` AS `active`,
                `tmi`.`copyable` AS `copyable`,
                `tmi`.`order` AS `order`" . $sSelectClause . "
            FROM `sys_menu_items` AS `tmi` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function deleteItemsBy($aParams)
    {
        $sWhereClause = $sLimitClause = "";

        switch($aParams['type']) {
            case 'by_id':
                $sWhereClause = $this->prepare(" AND `tmi`.`id`=? ", $aParams['value']);
                break;
            case 'by_set_name':
                $sWhereClause = $this->prepare(" AND `tmi`.`set_name`=? ", $aParams['value']);
                break;
            case 'all':
                break;
        }

        $sSql = "DELETE FROM `tmi` USING `sys_menu_items` AS `tmi` WHERE 1 " . $sWhereClause . " " . $sLimitClause;
        return (int)$this->query($sSql) > 0;
    }

    function addItem($aItem)
    {
        $sSql = "INSERT INTO `sys_menu_items` SET `" . implode("`=?, `", array_keys($aItem)) . "`=?";
        $sSql = call_user_func_array(array($this, 'prepare'), array_merge(array($sSql), array_values($aItem)));
        return (int)$this->query($sSql) > 0 ? $this->lastId() : 0;
    }

    function updateItem($iId, $aFields)
    {
        $sSql = "UPDATE `sys_menu_items` SET `" . implode("`=?, `", array_keys($aFields)) . "`=?  WHERE `id`=?";
        $sSql = call_user_func_array(array($this, 'prepare'), array_merge(array($sSql), array_values($aFields), array($iId)));
        return $this->query($sSql);
    }

    function getItemOrderMax($sSetName)
    {
        $sSql = $this->prepare("SELECT MAX(`order`) FROM `sys_menu_items` WHERE `set_name`=? LIMIT 1", $sSetName);
        return (int)$this->getOne($sSql);
    }
}

/** @} */
