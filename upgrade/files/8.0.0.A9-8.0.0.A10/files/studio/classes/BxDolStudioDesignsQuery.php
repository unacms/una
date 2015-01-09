<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxDolStudioPageQuery');

class BxDolStudioDesignsQuery extends BxDolStudioPageQuery
{
    function __construct()
    {
        parent::__construct();
    }

    function getTemplatesBy($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = " `tm`.`id` ASC ";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare(" AND `tm`.`id`=?", $aParams['value']);
                break;
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare(" AND `tm`.`name`=?", $aParams['value']);
                break;
            case 'active':
                $sWhereClause = " AND `tm`.`enabled`='1'";
                break;
            case 'all':
                $sOrderClause = " `tm`.`uri` ASC ";
                break;
            case 'all_by_id':
                $sWhereClause .= $this->prepare(" AND `tm`.`id`=?", $aParams['value']);
                $sOrderClause = " `tm`.`uri` ASC ";
                break;
            case 'all_key_id':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'id';

                if(isset($aParams['template']) && (int)$aParams['template'] != 0)
                    $sWhereClause .= $this->prepare(" AND `tm`.`id`=?", $aParams['template']);
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tm`.`id` AS `id`,
                `tm`.`name` AS `name`,
                `tm`.`title` AS `title`,
                `tm`.`enabled` AS `enabled`" . $sSelectClause . "
            FROM `sys_modules` AS `tm`" . $sJoinClause . "
            WHERE 1 AND `tm`.`type`='" . BX_DOL_MODULE_TYPE_TEMPLATE . "'" . $sWhereClause . "
            ORDER BY" . $sOrderClause . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }
}

/** @} */
