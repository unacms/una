<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Directory Directory
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxDirDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function insertCategoryType($aParamsSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet))
            return 0;

        if((int)$this->query("INSERT INTO `" . $CNF['TABLE_CATEGORIES_TYPES'] . "` SET " . $this->arrayToSQL($aParamsSet)) <= 0)
            return 0;

        return (int)$this->lastId();
    }

    public function deleteCategoryType($aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsWhere))
            return false;

        return (int)$this->query("DELETE FROM `" . $CNF['TABLE_CATEGORIES_TYPES'] . "` WHERE " . $this->arrayToSQL($aParamsWhere, ' AND ')) <= 0;
    }

    public function getCategoryTypes($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sSelectClause = "`tct`.*";
        $sWhereClause = $sOrderClause = "";
        
        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `tct`.`id`=:id";
                break;
            
            case 'all':
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " 
            FROM `" . $CNF['TABLE_CATEGORIES_TYPES'] . "` AS `tct`
            WHERE 1" . $sWhereClause . " " . $sOrderClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
    
    public function getCategories($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sSelectClause = "`tc`.*";
        $sWhereClause = "";
        $sOrderClause = "`tc`.`order` ASC";

        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `tc`.`id`=:id";
                break;

            case 'parent_id':
                $aMethod['params'][1] = array(
                    'parent_id' => $aParams['parent_id']
                );

            $sWhereClause = " AND `tc`.`parent_id`=:parent_id";
            break;

            case 'parent_id_count':
                $aMethod['name'] = 'getOne';
                $aMethod['params'][1] = array(
                    'parent_id' => $aParams['parent_id']
                );

            $sSelectClause = "COUNT(`tc`.`id`)";
            $sWhereClause = " AND `tc`.`parent_id`=:parent_id";
            break;
        
            case 'parent_id_order':
                $aMethod['name'] = 'getOne';
                $aMethod['params'][1] = array(
                    'parent_id' => $aParams['parent_id']
                );

            $sSelectClause = "MAX(`tc`.`order`)";
            $sWhereClause = " AND `tc`.`parent_id`=:parent_id";
            break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " 
            FROM `" . $CNF['TABLE_CATEGORIES'] . "` AS `tc`
            WHERE 1" . $sWhereClause . " " . $sOrderClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function cloneDisplay($sDisplayName, $sNewDisplayName, $sNewDisplayTitle)
    {
        $aDisplay = $this->getRow("SELECT * FROM `sys_form_displays` WHERE `display_name`=:display_name", array('display_name' => $sDisplayName));
        if(empty($aDisplay) || !is_array($aDisplay))
            return false;
        
        unset($aDisplay['id']);
        $aDisplay['display_name'] = $sNewDisplayName;
        $aDisplay['title'] = $sNewDisplayTitle;

        if((int)$this->query("INSERT INTO `sys_form_displays` SET " . $this->arrayToSQL($aDisplay)) <= 0)
            return false;

        $iNewDisplayId = (int)$this->lastId();

        if((int)$this->query("INSERT INTO `sys_form_display_inputs` SELECT NULL, '" . $sNewDisplayName . "', `input_name`, `visible_for_levels`, `active`, `order` FROM `sys_form_display_inputs` WHERE `display_name`=:display_name AND `active`='1'", array('display_name' => $sDisplayName)) <= 0)
            return false;

        return true;
    }
}

/** @} */
