<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioSettingsQuery extends BxDolStudioPageQuery
{
    function __construct()
    {
        parent::__construct();
    }

    function getTypes($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tt`.`order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `tt`.`id`=?", $aParams['value']);
                $sLimitClause .= "LIMIT 1";
                break;
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `tt`.`name`=?", $aParams['value']);
                $sLimitClause .= "LIMIT 1";
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tt`.`id` AS `id`,
                `tt`.`group` AS `group`,
                `tt`.`name` AS `name`,
                `tt`.`caption` AS `caption`,
                `tt`.`icon` AS `icon`,
                `tt`.`order` AS `order` " . $sSelectClause . "
            FROM `sys_options_types` AS `tt` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function getCategories($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tc`.`order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `tc`.`id`=?", $aParams['value']);
                $sLimitClause .= "LIMIT 1";
                break;
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $sSelectClause .= ", `tt`.`name` AS `type_name`, `tt`.`group` AS `type_group`";
                $sJoinClause .= "LEFT JOIN `sys_options_types` AS `tt` ON `tc`.`type_id`=`tt`.`id` ";
                $sWhereClause .= $this->prepare("AND `tc`.`name`=?", $aParams['value']);
                $sLimitClause .= "LIMIT 1";
                break;
            case 'all_key_name':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'name';
                break;
            case 'by_type_id_key_name':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'name';
                $sWhereClause .= $this->prepare("AND `tc`.`type_id`=?", $aParams['value']);
                break;
            case 'by_type_name_key_name':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'name';
                $sJoinClause = "LEFT JOIN `sys_options_types` AS `tt` ON `tc`.`type_id`=`tt`.`id` ";
                if(isset($aParams['category_name']) && $aParams['category_name'] != '')
                    $sWhereClause .= $this->prepare("AND `tt`.`name`=? AND `tc`.`name`=?", $aParams['type_name'], $aParams['category_name']);
                else
                    $sWhereClause .= $this->prepare("AND `tt`.`name`=? AND `tc`.`hidden`=?", $aParams['type_name'], $aParams['hidden']);
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tc`.`id` AS `id`,
                `tc`.`type_id` AS `type_id`,
                `tc`.`name` AS `name`,
                `tc`.`caption` AS `caption`,
                `tc`.`order` AS `order`" . $sSelectClause . "
            FROM `sys_options_categories` AS `tc` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }
    function getOptions($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `to`.`order` ASC";

        switch($aParams['type']) {
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `to`.`name`=?", $aParams['value']);
                $sLimitClause .= "LIMIT 1";
                break;
            case 'by_category_id':
                $sWhereClause .= $this->prepare("AND `to`.`category_id`=?", $aParams['value']);
                break;
            case 'by_category_name':
                $sJoinClause .= "LEFT JOIN `sys_options_categories` AS `tc` ON `to`.`category_id`=`tc`.`id` ";
                $sWhereClause .= $this->prepare("AND `tc`.`name`=?", $aParams['value']);
                break;
            case 'by_category_name_full':
                $sSelectClause .= ", `tc`.`name` AS `category_name`, `tt`.`name` AS `type_name`";
                $sJoinClause .= "LEFT JOIN `sys_options_categories` AS `tc` ON `to`.`category_id`=`tc`.`id` LEFT JOIN `sys_options_types` AS `tt` ON `tc`.`type_id`=`tt`.`id` ";
                $sWhereClause .= $this->prepare("AND `tc`.`name`=?", $aParams['value']);
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `to`.`id` AS `id`,
                `to`.`category_id` AS `category_id`,
                `to`.`name` AS `name`,
                `to`.`caption` AS `caption`,
                `to`.`value` AS `value`,
                `to`.`type` AS `type`,
                `to`.`extra` AS `extra`,
                `to`.`check` AS `check`,
                `to`.`check_params` AS `check_params`,
                `to`.`check_error` AS `check_error`,
                `to`.`order` AS `order`" . $sSelectClause . "
            FROM `sys_options` AS `to` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }
}

/** @} */
