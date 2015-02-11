<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioPermissionsQuery extends BxDolAclQuery
{
    function __construct()
    {
        parent::__construct();
    }

    function isLevelUsed($iId)
    {
        $sSql = $this->prepare("SELECT UNIX_TIMESTAMP(MAX(`DateExpires`)) as `MaxDateExpires` FROM `sys_acl_levels_members` WHERE `IDLevel`=?", $iId);
        return (int)$this->getOne($sSql) > time();
    }

    function getLevelOrderMax()
    {
        return (int)$this->getOne("SELECT MAX(`Order`) FROM `sys_acl_levels` WHERE 1");
    }

    function updateLevels($iId, $aFields)
    {
        $sSql = "UPDATE `sys_acl_levels` SET `" . implode("`=?, `", array_keys($aFields)) . "`=?  WHERE `ID`=?";
        $sSql = call_user_func_array(array($this, 'prepare'), array_merge(array($sSql), array_values($aFields), array($iId)));
        return (int)$this->query($sSql) > 0;
    }

    function deleteLevel($aParams)
    {
        $sWhereClause = $sLimitClause = "";

        switch($aParams['type']) {
            case 'by_id':
                $sWhereClause .= $this->prepare("AND `tal`.`ID`=?", $aParams['value']);
                break;
        }

        $sSql = "DELETE FROM `tal`, `tam`
            USING `sys_acl_levels` AS `tal`
            LEFT JOIN `sys_acl_matrix` AS `tam` ON `tal`.`ID`=`tam`.`IDLevel`
            WHERE 1 " . $sWhereClause . " " . $sLimitClause;
        return (int)$this->query($sSql) > 0;
    }

    function switchAction($iLevelId, $iActionId, $bEnable)
    {
        if($bEnable)
            $sSql = $this->prepare("INSERT INTO `sys_acl_matrix`(`IDLevel`, `IDAction`) VALUES(?, ?)", $iLevelId, $iActionId);
        else
            $sSql = $this->prepare("DELETE FROM `sys_acl_matrix` WHERE `IDLevel`=? AND `IDAction`=?", $iLevelId, $iActionId);
        return (int)$this->query($sSql) > 0;
    }

    function getOptions($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tam`.`IDAction` ASC";

        switch($aParams['type']) {
            case 'by_level_action_ids':
                $aMethod['name'] = 'getRow';
                $sSelectClause = ", `taa`.`Title` AS `action_title`, `taa`.`Countable` AS `action_countable`";
                $sJoinClause = "LEFT JOIN `sys_acl_actions` AS `taa` ON `tam`.`IDAction`=`taa`.`ID` ";
                $sWhereClause = $this->prepare(" AND `tam`.`IDLevel`=? AND `tam`.`IDAction`=? ", $aParams['level_id'], $aParams['action_id']);
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tam`.`IDLevel` AS `level_id`,
                `tam`.`IDAction` AS `action_id`,
                `tam`.`AllowedCount` AS `allowed_count`,
                `tam`.`AllowedPeriodLen` AS `allowed_period_len`,
                `tam`.`AllowedPeriodStart` AS `allowed_period_start`,
                `tam`.`AllowedPeriodEnd` AS `allowed_period_end`,
                `tam`.`AdditionalParamValue` AS `additional_param_value`" . $sSelectClause . "
            FROM `sys_acl_matrix` AS `tam` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function updateOptions($iLevelId, $iActionId, $aFields)
    {
        $sSql = "UPDATE `sys_acl_matrix` SET `" . implode("`=?, `", array_keys($aFields)) . "`=?  WHERE `IDLevel`=? AND `IDAction`=?";
        $sSql = call_user_func_array(array($this, 'prepare'), array_merge(array($sSql), array_values($aFields), array($iLevelId, $iActionId)));
        return (int)$this->query($sSql) > 0;
    }

    function deleteActions($aParams)
    {
        $sWhereClause = "";

        switch($aParams['type']) {
            case 'by_level_id':
                $sWhereClause .= $this->prepare("AND `IDLevel`=?", $aParams['value']);
                break;
        }

        return (int)$this->query("DELETE FROM `sys_acl_matrix` WHERE 1 " . $sWhereClause) > 0;
    }
}

/** @} */
