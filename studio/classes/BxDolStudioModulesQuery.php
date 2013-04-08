<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolModuleQuery');

class BxDolStudioModulesQuery extends BxDolModuleQuery {
    function BxDolStudioModulesQuery() {
        parent::BxDolModuleQuery();
    }

    function getModulesBy($aParams, &$aItems, $bReturnCount = true) {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tm`.`id` ASC";

        switch($aParams['type']) {
            case 'all':
                break;
            case 'active':
                $sWhereClause = "AND `tm`.`enabled`='1'";
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . " 
        		`tm`.`id` AS `id`, 
        		`tm`.`title` AS `title`, 
        		`tm`.`vendor` AS `vendor`, 
        		`tm`.`version` AS `version`, 
        		`tm`.`product_url` AS `product_url`,
        		`tm`.`update_url` AS `update_url`, 
        		`tm`.`path` AS `path`, 
        		`tm`.`uri` AS `uri`, 
        		`tm`.`class_prefix` AS `class_prefix`, 
        		`tm`.`db_prefix` AS `db_prefix`, 
        		`tm`.`date` AS `date`, 
        		`tm`.`enabled` AS `enabled`" . $sSelectClause . "
			FROM `sys_modules` AS `tm`
			WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }
}
/** @} */