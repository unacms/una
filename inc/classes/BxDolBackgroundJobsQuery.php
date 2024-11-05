<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolBackgroundJobs
 */
class BxDolBackgroundJobsQuery extends BxDolDb
{
    public function __construct()
    {
    	parent::__construct();
    }

    public function getJobs($aParams = [])
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

    	$sSelectClause = "*";
    	$sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";
        switch($aParams['sample']) {
            case 'name':
                $aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = [
                    'name' => $aParams['name']
                ];

                $sWhereClause = " AND `name`=:name";
                break;

            case 'process':
                $sOrderClause = "`added` ASC";
                if(isset($aParams['with_priority']) && $aParams['with_priority'] === true)
                    $sOrderClause = "`priority` DESC, " . $sOrderClause;
                break;
        }

        $sOrderClause = !empty($sOrderClause) ? "ORDER BY " . $sOrderClause : $sOrderClause;
        $sLimitClause = !empty($sLimitClause) ? "LIMIT " . $sLimitClause : $sLimitClause;

        $aMethod['params'][0] = "SELECT
                " . $sSelectClause . "
            FROM `sys_background_jobs` " . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function addJob($sName, $sServiceCall, $iPriority = 0)
    {
        return $this->query("INSERT INTO `sys_background_jobs` SET `name` = :name, `added`=UNIX_TIMESTAMP(), `priority` = :priority, `service_call`=:service_call ON DUPLICATE KEY UPDATE `added`=UNIX_TIMESTAMP(), `priority` = :priority, `service_call`=:service_call", [
            'name' => $sName,
            'priority' => $iPriority,
            'service_call' => $sServiceCall
        ]) !== false;
    }

    public function updateJob($sName, $aParamsSet)
    {
        if(empty($sName))
            return false;

        return $this->updateJobExt($aParamsSet, [
            'name' => $sName
        ]);
    }

    public function updateJobExt($aParamsSet, $aParamsWhere)
    {
        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        return $this->query("UPDATE `sys_background_jobs` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }

    public function deleteJob($sName)
    {
        return $this->query("DELETE FROM `sys_background_jobs` WHERE `name`=:name", [
            'name' => $sName
        ]) !== false;
    }
}

/** @} */
