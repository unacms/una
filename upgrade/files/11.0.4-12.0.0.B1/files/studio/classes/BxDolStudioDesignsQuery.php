<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioDesignsQuery extends BxDolStudioPageQuery
{
    function __construct()
    {
        parent::__construct();
    }

    function getTemplatesBy($aParams)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = " `tm`.`id` ASC ";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause .= " AND `tm`.`id`=:id";
                break;

            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'name' => $aParams['value']
                );

                $sWhereClause .= " AND `tm`.`name`=:name";
                break;

            case 'active':
                $sWhereClause = " AND `tm`.`enabled`='1'";
                break;
            case 'all':
                $sOrderClause = " `tm`.`uri` ASC ";
                break;
            case 'all_by_id':
            	$aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause .= " AND `tm`.`id`=:id";
                $sOrderClause = " `tm`.`uri` ASC ";
                break;

            case 'all_key_id':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'id';

                if(isset($aParams['template']) && (int)$aParams['template'] != 0) {
                	$aMethod['params'][2] = array(
	                	'id' => $aParams['template']
	                );

                    $sWhereClause .= " AND `tm`.`id`=:id";
                }
                break;
        }

        $aMethod['params'][0] = "SELECT 
                `tm`.`id` AS `id`,
                `tm`.`name` AS `name`,
                `tm`.`title` AS `title`,
                `tm`.`enabled` AS `enabled`" . $sSelectClause . "
            FROM `sys_modules` AS `tm`" . $sJoinClause . "
            WHERE 1 AND `tm`.`type`='" . BX_DOL_MODULE_TYPE_TEMPLATE . "'" . $sWhereClause . "
            ORDER BY" . $sOrderClause . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
}

/** @} */
