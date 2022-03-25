<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioOptionsQuery extends BxDolDb implements iBxDolSingleton
{
    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        $sClass = __CLASS__;
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new $sClass();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function getTypes($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tt`.`order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['value']
                ];

                $sWhereClause .= "AND `tt`.`id`=:id";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'name' => $aParams['value']
                ];

                $sWhereClause .= "AND `tt`.`name`=:name";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'all':
                if(!empty($aParams['in_group'])) {
                    if(!is_array($aParams['in_group']))
                        $aParams['in_group'] = [$aParams['in_group']];

                    $sWhereClause .= "AND `tt`.`group` IN (" . $this->implode_escape($aParams['in_group']) . ")";
                }

                if(!empty($aParams['not_in_group'])) {
                    if(!is_array($aParams['not_in_group']))
                        $aParams['not_in_group'] = [$aParams['not_in_group']];

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
        $aItems = call_user_func_array([$this, $aMethod['name']], $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    public function getTypeId($sName)
    {
    	$aType = [];
    	$this->getTypes(['type' => 'by_name', 'value' => $sName], $aType, false);

    	return !empty($aType) && is_array($aType) ? $aType['id'] : 0;
    }

    public function getCategories($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tc`.`order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['value']
                ];

                $sWhereClause .= "AND `tc`.`id`=:id";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'name' => $aParams['value']
                ];

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
                $aMethod['params'][2] = [
                    'type_id' => $aParams['value']
                ];

                $sWhereClause .= "AND `tc`.`type_id`=:type_id";
                break;

            case 'by_type_name_key_name':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'name';

                $sJoinClause = "LEFT JOIN `sys_options_types` AS `tt` ON `tc`.`type_id`=`tt`.`id` ";
                if(isset($aParams['category_name']) && !empty($aParams['category_name'])) {
                    if(is_string($aParams['category_name']))
                        $aParams['category_name'] = [$aParams['category_name']];

                    $sWhereClause .= "AND `tt`.`name`=:name AND `tc`.`name` IN (" . $this->implode_escape($aParams['category_name']) . ")";
                    $aMethod['params'][2] = [
                    	'name' => $aParams['type_name']
                    ];
                }
                else {
                    $sWhereClause .= "AND `tt`.`name`=:name AND `tc`.`hidden`=:hidden";
                    $aMethod['params'][2] = [
                        'name' => $aParams['type_name'],
                        'hidden' => $aParams['hidden']
                    ];
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
        $aItems = call_user_func_array([$this, $aMethod['name']], $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    public function getCategoryId($sName)
    {
    	$aCategory = [];
    	$this->getCategories(['type' => 'by_name', 'value' => $sName], $aCategory, false);

    	return !empty($aCategory) && is_array($aCategory) ? $aCategory['id'] : 0;
    }

    public function getMixes($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tm`.`name` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['value']
                ];
                
                $sWhereClause .= "AND `tm`.`id`=:id";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'name' => $aParams['value']
                ];

                $sWhereClause .= "AND `tm`.`name`=:name";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'by_type':
                $aMethod['params'][1] = [
                    'type' => $aParams['value']
                ];

                $sWhereClause .= "AND `tm`.`type`=:type";
                break;

            case 'by_category':
                $aMethod['params'][1] = [
                    'category' => $aParams['value']
                ];

                $sWhereClause .= "AND `tm`.`category`=:category";
                break;

            case 'by_type_category':
                $aMethod['params'][1] = [
                    'type' => $aParams['mix_type'],
                    'category' => $aParams['mix_category']
                ];

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
        $aItems = call_user_func_array([$this, $aMethod['name']], $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    public function updateMixes($aParamsSet, $aParamsWhere)
    {
        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        $sSql = "UPDATE `sys_options_mixes` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return (int)$this->query($sSql) > 0;
    }

    public function deleteMixes($aParamsWhere)
    {
        if(empty($aParamsWhere))
            return false;

        $sSql = "DELETE FROM `sys_options_mixes` WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql) !== false;
    }

    public function getMixesOptions($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['type']) {
            case 'by_mix_id_pair_option_value':
                $aMethod['name'] = 'getPairs'; 
                $aMethod['params'][1] = 'option';
                $aMethod['params'][2] = 'value';
                $aMethod['params'][3] = [
                    'mix_id' => $aParams['value']
                ];

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
        $aItems = call_user_func_array([$this, $aMethod['name']], $aMethod['params']);

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

    public function deleteMixesOptions($aParamsWhere)
    {
        if(empty($aParamsWhere))
            return false;

        $sSql = "DELETE FROM `sys_options_mixes2options` WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql) !== false;
    }

    public function duplicateMixesOptions($iIdFrom, $iIdTo)
    {
    	$aBindings = [
            'mix_id_from' => $iIdFrom,
            'mix_id_to' => $iIdTo
    	];

    	$sSql = "INSERT INTO `sys_options_mixes2options`(`option`, `mix_id`, `value`) SELECT `option`, :mix_id_to, `value` FROM `sys_options_mixes2options` WHERE `mix_id`=:mix_id_from";
    	return $this->query($sSql, $aBindings) !== false;
    }

    public function getOptions($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `to`.`order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['value']
                ];

                $sWhereClause .= "AND `to`.`id`=:id";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'name' => $aParams['value']
                ];

                $sWhereClause .= "AND `to`.`name`=:name";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'by_category_id':
            	$aMethod['params'][1] = [
                    'category_id' => $aParams['value']
                ];

                $sWhereClause .= "AND `to`.`category_id`=:category_id";
                break;

            case 'by_category_name':
            	$aMethod['params'][1] = [
                    'name' => $aParams['value']
                ];

                $sJoinClause .= "LEFT JOIN `sys_options_categories` AS `tc` ON `to`.`category_id`=`tc`.`id` ";
                $sWhereClause .= "AND `tc`.`name`=:name";
                break;

            case 'by_category_name_full':
            	$aMethod['params'][1] = [
                    'name' => $aParams['value']
                ];

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
        $aItems = call_user_func_array([$this, $aMethod['name']], $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }
}

/** @} */
