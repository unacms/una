<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolAclQuery');

class BxDolStudioPermissionsQuery extends BxDolAclQuery
{
    function __construct()
    {
        parent::__construct();
    }

    function getPrices($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tap`.`Order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `tap`.`id`=?", $aParams['value']);
                break;
            case 'by_level_id':
                $sWhereClause .= $this->prepare("AND `tap`.`IDLevel`=?", $aParams['value']);
                break;
            case 'by_level_id_pair':
                $aMethod['name'] = "getPairs";
                $aMethod['params'][1] = 'days';
                $aMethod['params'][2] = 'price';
                $sWhereClause .= $this->prepare("AND `tap`.`IDLevel`=?", $aParams['value']);
                break;
            case 'by_level_id_duration':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `tap`.`IDLevel`=? AND `tap`.`Days`=?", $aParams['level_id'], $aParams['days']);
                break;
            case 'counter_by_levels':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'level_id';
                $aMethod['params'][2] = 'counter';
                $sSelectClause = ", COUNT(*) AS `counter`";
                $sGroupClause = "GROUP BY `tap`.`IDLevel`";
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tap`.`id` AS `id`,
                `tap`.`IDLevel` AS `level_id`,
                `tap`.`Days` AS `days`,
                `tap`.`Price` AS `price`,
                `tap`.`Order` AS `order`" . $sSelectClause . "
            FROM `sys_acl_level_prices` AS `tap` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function getPriceOrderMax($iLevelId)
    {
        $sSql = $this->prepare("SELECT MAX(`Order`) FROM `sys_acl_level_prices` WHERE `IDLevel`=?", $iLevelId);
        return (int)$this->getOne($sSql);
    }

    function deletePrices($aParams)
    {
        $sWhereClause = "";

        switch($aParams['type']) {
            case 'by_level_id':
                $sWhereClause .= $this->prepare("AND `IDLevel`=?", $aParams['value']);
                break;
        }

        return (int)$this->query("DELETE FROM `sys_acl_level_prices` WHERE 1 " . $sWhereClause) > 0;
    }
}
/** @} */
