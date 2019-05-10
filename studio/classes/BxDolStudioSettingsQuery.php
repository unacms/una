<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
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
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause .= "AND `tt`.`id`=:id";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'name' => $aParams['value']
                );

                $sWhereClause .= "AND `tt`.`name`=:name";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'all':
                if(!empty($aParams['in_group'])) {
                    if(!is_array($aParams['in_group']))
                        $aParams['in_group'] = array($aParams['in_group']);

                    $sWhereClause .= "AND `tt`.`group` IN (" . $this->implode_escape($aParams['in_group']) . ")";
                }

                if(!empty($aParams['not_in_group'])) {
                    if(!is_array($aParams['not_in_group']))
                        $aParams['not_in_group'] = array($aParams['not_in_group']);

                    $sWhereClause .= "AND `tt`.`group` NOT IN (" . $this->implode_escape($aParams['not_in_group']) . ")";
                }
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
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause .= "AND `tc`.`id`=:id";
                $sLimitClause .= "LIMIT 1";
                break;
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'name' => $aParams['value']
                );

                $sSelectClause .= ", `tt`.`name` AS `type_name`, `tt`.`group` AS `type_group`";
                $sJoinClause .= "LEFT JOIN `sys_options_types` AS `tt` ON `tc`.`type_id`=`tt`.`id` ";
                $sWhereClause .= "AND `tc`.`name`=:name";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'all_key_name':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'name';
                break;

            case 'by_type_id_key_name':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'name';
                $aMethod['params'][2] = array(
                	'type_id' => $aParams['value']
                );

                $sWhereClause .= "AND `tc`.`type_id`=:type_id";
                break;

            case 'by_type_name_key_name':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'name';

                $sJoinClause = "LEFT JOIN `sys_options_types` AS `tt` ON `tc`.`type_id`=`tt`.`id` ";
                if(isset($aParams['category_name']) && !empty($aParams['category_name'])) {
                	if(is_string($aParams['category_name']))
                		$aParams['category_name'] = array($aParams['category_name']);

                    $sWhereClause .= "AND `tt`.`name`=:name AND `tc`.`name` IN (" . $this->implode_escape($aParams['category_name']) . ")";
                    $aMethod['params'][2] = array(
                    	'name' => $aParams['type_name']
                    );
                }
                else {
                    $sWhereClause .= "AND `tt`.`name`=:name AND `tc`.`hidden`=:hidden";
					$aMethod['params'][2] = array(
	                	'name' => $aParams['type_name'],
						'hidden' => $aParams['hidden']
	                );
                }
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
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );
                
                $sWhereClause .= "AND `tm`.`id`=:id";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'name' => $aParams['value']
                );

                $sWhereClause .= "AND `tm`.`name`=:name";
                $sLimitClause .= "LIMIT 1";
                break;

			case 'by_type':
				$aMethod['params'][1] = array(
                	'type' => $aParams['value']
                );

                $sWhereClause .= "AND `tm`.`type`=:type";
                break;

			case 'by_category':
				$aMethod['params'][1] = array(
                	'category' => $aParams['value']
                );

                $sWhereClause .= "AND `tm`.`category`=:category";
                break;

			case 'by_type_category':
				$aMethod['params'][1] = array(
                	'type' => $aParams['mix_type'],
					'category' => $aParams['mix_category']
                );

				$sWhereClause .= "AND `tm`.`type`=:type AND `tm`.`category`=:category";
                break;
        }

		if(!empty($aParams['active'])) {
			if((int)$aParams['active'] == 1)
				$aMethod['name'] = 'getRow';
			$aMethod['params'][1]['active'] = $aParams['active'];

			$sWhereClause .= " AND `tm`.`active`=:active";
		}

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tm`.`id` AS `id`,
                `tm`.`type` AS `type`,
                `tm`.`category` AS `category`,
                `tm`.`name` AS `name`,
                `tm`.`title` AS `title`,
                `tm`.`active` AS `active`,
                `tm`.`published` AS `published`,
                `tm`.`editable` AS `editable` " . $sSelectClause . "
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
				$aMethod['params'][1] = 'option';
				$aMethod['params'][2] = 'value';
				$aMethod['params'][3] = array(
                	'mix_id' => $aParams['value']
                );

                $sWhereClause .= "AND `tmo`.`mix_id`=:mix_id ";

                if(!empty($aParams['for_export'])) {
                	$sJoinClause .= "LEFT JOIN `sys_options` AS `to` ON `tmo`.`option`=`to`.`name` ";
                	$sWhereClause .= "AND `to`.`type` NOT IN ('file', 'image')";
                }
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tmo`.`option` AS `option`,
                `tmo`.`mix_id` AS `mix_id`,
                `tmo`.`value` AS `value` " . $sSelectClause . "
            FROM `sys_options_mixes2options` AS `tmo` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

	public function insertMixesOptions($aParamsSet)
    {
        if(empty($aParamsSet))
            return false;

        return (int)$this->query("INSERT INTO `sys_options_mixes2options` SET " . $this->arrayToSQL($aParamsSet)) > 0;
    }

	function deleteMixesOptions($aParamsWhere)
    {
        if(empty($aParamsWhere))
            return false;

        $sSql = "DELETE FROM `sys_options_mixes2options` WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql) !== false;
    }

    function duplicateMixesOptions($iIdFrom, $iIdTo)
    {
    	$aBindings = array(
    		'mix_id_from' => $iIdFrom,
    		'mix_id_to' => $iIdTo
    	);

    	$sSql = "INSERT INTO `sys_options_mixes2options`(`option`, `mix_id`, `value`) SELECT `option`, :mix_id_to, `value` FROM `sys_options_mixes2options` WHERE `mix_id`=:mix_id_from";
    	return $this->query($sSql, $aBindings) !== false;
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
				$aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );
                
                $sWhereClause .= "AND `to`.`id`=:id";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'name' => $aParams['value']
                );

                $sWhereClause .= "AND `to`.`name`=:name";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'by_category_id':
            	$aMethod['params'][1] = array(
                	'category_id' => $aParams['value']
                );

                $sWhereClause .= "AND `to`.`category_id`=:category_id";
                break;

            case 'by_category_name':
            	$aMethod['params'][1] = array(
                	'name' => $aParams['value']
                );

                $sJoinClause .= "LEFT JOIN `sys_options_categories` AS `tc` ON `to`.`category_id`=`tc`.`id` ";
                $sWhereClause .= "AND `tc`.`name`=:name";
                break;

            case 'by_category_name_full':
            	$aMethod['params'][1] = array(
                	'name' => $aParams['value']
                );

                $sSelectClause .= ", `tc`.`name` AS `category_name`, `tt`.`name` AS `type_name`";
                $sJoinClause .= "LEFT JOIN `sys_options_categories` AS `tc` ON `to`.`category_id`=`tc`.`id` LEFT JOIN `sys_options_types` AS `tt` ON `tc`.`type_id`=`tt`.`id` ";
                $sWhereClause .= "AND `tc`.`name`=:name";
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
