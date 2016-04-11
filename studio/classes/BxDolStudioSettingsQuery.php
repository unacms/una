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

    function getTypeId($sName)
    {
    	$aType = array();
    	$this->getTypes(array('type' => 'by_name', 'value' => $sName), $aType, false);

    	return !empty($aType) && is_array($aType) ? $aType['id'] : 0;
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
                if(isset($aParams['category_name']) && !empty($aParams['category_name'])) {
                	if(is_string($aParams['category_name']))
                		$aParams['category_name'] = array($aParams['category_name']);

                    $sWhereClause .= $this->prepare("AND `tt`.`name`=? AND `tc`.`name` IN (" . $this->implode_escape($aParams['category_name']) . ")", $aParams['type_name']);
                }
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

    function getCategoryId($sName)
    {
    	$aCategory = array();
    	$this->getCategories(array('type' => 'by_name', 'value' => $sName), $aCategory, false);

    	return !empty($aCategory) && is_array($aCategory) ? $aCategory['id'] : 0;
    }

    function getMixes($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tm`.`name` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `tm`.`id`=?", $aParams['value']);
                $sLimitClause .= "LIMIT 1";
                break;
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `tm`.`name`=?", $aParams['value']);
                $sLimitClause .= "LIMIT 1";
                break;
			case 'by_type':
				$sJoinClause = "LEFT JOIN `sys_options_types` AS `tt` ON `tm`.`type_id`=`tt`.`id`";
                $sWhereClause .= $this->prepare("AND `tt`.`name`=?", $aParams['value']);
                break;
			case 'by_category':
				$sJoinClause = "LEFT JOIN `sys_options_categories` AS `tc` ON `tm`.`category_id`=`tc`.`id`";
                $sWhereClause .= $this->prepare("AND `tc`.`name`=?", $aParams['value']);
                break;
			case 'by_type_category':
				$sJoinClause = "LEFT JOIN `sys_options_types` AS `tt` ON `tm`.`type_id`=`tt`.`id` LEFT JOIN `sys_options_categories` AS `tc` ON `tm`.`category_id`=`tc`.`id`";
				$sWhereClause .= $this->prepare("AND `tt`.`name`=? AND `tc`.`name`=?", $aParams['type'], $aParams['category']);
                break;
        }

		if(!empty($aParams['active'])) {
			if((int)$aParams['active'] == 1)
				$aMethod['name'] = 'getRow';

			$sWhereClause .= $this->prepare(" AND `tm`.`active`=?", $aParams['active']);
		}

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tm`.`id` AS `id`,
                `tm`.`category_id` AS `category_id`,
                `tm`.`name` AS `name`,
                `tm`.`title` AS `title`,
                `tm`.`active` AS `active` " . $sSelectClause . "
            FROM `sys_options_mixes` AS `tm` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

	function updateMixes($aParamsSet, $aParamsWhere)
    {
        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        $sSql = "UPDATE `sys_options_mixes` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return (int)$this->query($sSql) > 0;
    }

	function deleteMixes($aParamsWhere)
    {
        if(empty($aParamsWhere))
            return false;

        $sSql = "DELETE FROM `sys_options_mixes` WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql) !== false;
    }

	function getMixesOptions($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['type']) {
			case 'by_mix_id_pair_option_value':
				$aMethod['name'] = 'getPairs'; 
				$aMethod['params'][1] = 'option_id';
				$aMethod['params'][2] = 'value';
                $sWhereClause .= $this->prepare("AND `tmo`.`mix_id`=?", $aParams['value']);
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tmo`.`option_id` AS `option_id`,
                `tmo`.`mix_id` AS `mix_id`,
                `tmo`.`value` AS `value` " . $sSelectClause . "
            FROM `sys_options_mixes2options` AS `tmo` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

	function deleteMixesOptions($aParamsWhere)
    {
        if(empty($aParamsWhere))
            return false;

        $sSql = "DELETE FROM `sys_options_mixes2options` WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql) !== false;
    }

    function getOptions($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `to`.`order` ASC";

        switch($aParams['type']) {
        	case 'by_id':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `to`.`id`=?", $aParams['value']);
                $sLimitClause .= "LIMIT 1";
                break;
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
