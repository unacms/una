<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Sites Sites
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleDb');

/*
 * Notes module database queries
 */
class BxSitesDb extends BxDolModuleDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    function isOwner($aWhere)
    {
        return $this->is('owners', $aWhere);
    }

    function insertOwner($aValues)
    {
        return $this->insert('owners', $aValues);
    }

    function updateOwner($aUpdate, $aWhere)
    {
        return $this->update('owners', $aUpdate, $aWhere);
    }

    function isAccount($aWhere)
    {
        return $this->is('accounts', $aWhere);
    }

    function insertAccount($aValues)
    {
        return $this->insert('accounts', $aValues);
    }

    function updateAccount($aUpdate, $aWhere)
    {
        return $this->update('accounts', $aUpdate, $aWhere);
    }

    function getAccount($aParams)
    {
        $sMethod = 'getRow';
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = '';
        switch($aParams['type']) {
            case 'id':
                $sWhereClause = $this->prepare("AND `ta`.`id`=?", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;
            case 'owner_id':
                $sWhereClause = $this->prepare("AND `ta`.`owner_id`=?", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;
            case 'token':
                $sWhereClause = $this->prepare("AND `tpd`.`token`=?", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;
            case 'profile_id':
                $sWhereClause = $this->prepare("AND `tpd`.`profile_id`=?", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;
            case 'profile_sid':
                $sWhereClause = $this->prepare("AND `tpd`.`profile_sid`=?", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;
        }

        $sSql = "SELECT
                `ta`.`id`,
                `ta`.`owner_id`,
                `to`.`trials` AS `owner_trials`,
                `ta`.`email`,
                `ta`.`domain`,
                `ta`.`title`,
                `ta`.`created`,
                `ta`.`paid`,
                `ta`.`status`,
                `tpd`.`id` AS `pd_id`,
                `tpd`.`type` AS `pd_type`,
                `tpd`.`token` AS `pd_token`,
                `tpd`.`profile_id` AS `pd_profile_id`,
                `tpd`.`profile_sid` AS `pd_profile_sid`" . $sSelectClause . "
            FROM `" . $this->_sPrefix . "accounts` AS `ta`
            LEFT JOIN `" . $this->_sPrefix . "owners` AS `to` ON `ta`.`owner_id`=`to`.`id`
            LEFT JOIN `" . $this->_sPrefix . "payment_details` AS `tpd` ON `ta`.`id`=`tpd`.`account_id` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return $this->$sMethod($sSql);
    }

    function getPaymentHistory($aParams)
    {
        $sMethod = 'getAll';
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = '';
        switch($aParams['type']) {
            case 'account_id':
                $sWhereClause = $this->prepare("AND `tph`.`account_id`=?", $aParams['value']);
                $sOrderClause = "ORDER BY `tph`.`when` DESC";
                break;
            case 'account_id_last':
                $sMethod = "getRow";
                $sWhereClause = $this->prepare("AND `tph`.`account_id`=?", $aParams['value']);
                $sOrderClause = "ORDER BY `tph`.`when` DESC";
                $sLimitClause = "LIMIT 1";
                break;
        }

        $sSql = "SELECT
                `tph`.`id` AS `id`,
                `tph`.`account_id` AS `account_id`,
                `tph`.`type` AS `type`,
                `tph`.`transaction` AS `transaction`,
                `tph`.`amount` AS `amount`,
                `tph`.`when` AS `when`,
                `tph`.`when_next` AS `when_next`" . $sSelectClause . "
            FROM `" . $this->_sPrefix . "payment_history` AS `tph` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return $this->$sMethod($sSql);
    }

    function insertPaymentDetails($aValues)
    {
        return $this->insert('payment_details', $aValues);
    }

    function updatePaymentDetails($aUpdate, $aWhere)
    {
        return $this->update('payment_details', $aUpdate, $aWhere);
    }

    function deletePaymentDetails($aWhere)
    {
        return $this->delete('payment_details', $aWhere);
    }

    function insertPaymentHistory($aValues)
    {
        return $this->insert('payment_history', $aValues);
    }

    function updatePaymentHistory($aUpdate, $aWhere)
    {
        return $this->update('payment_history', $aUpdate, $aWhere);
    }

    function deletePaymentHistory($aWhere)
    {
        return $this->delete('payment_history', $aWhere);
    }

    protected function is($sTable, $aWhere)
    {
        $sWhere = '';
        foreach($aWhere as $sKey => $sValue)
            $sWhere .= $this->prepare(" AND `" . $sKey . "`=?", $sValue);

        return (int)$this->query("SELECT `id` FROM `" . $this->_sPrefix . $sTable. "` WHERE 1" . $sWhere) > 0;
    }

    protected function insert($sTable, $aValues)
    {
        $sSql = "";
        foreach($aValues as $sKey => $sValue)
           $sSql .= $this->prepare("`" . $sKey . "`=?, ", $sValue);
        $sSql = "INSERT INTO `" . $this->_sPrefix . $sTable. "` SET " . substr($sSql, 0, -2);

        if((int)$this->query($sSql) > 0)
            return (int)$this->lastId();

        return 0;
    }

    protected function update($sTable, $aUpdate, $aWhere)
    {
        $sUpdate = $sWhere = "";

        foreach($aUpdate as $sKey => $sValue)
            $sUpdate .= $this->prepare("`" . $sKey . "`=?, ", $sValue);
        $sUpdate = substr($sUpdate, 0, -2);

        foreach($aWhere as $sKey => $sValue)
            $sWhere .= $this->prepare(" AND `" . $sKey . "`=?", $sValue);

        return (int)$this->query("UPDATE `" . $this->_sPrefix . $sTable . "` SET " . $sUpdate . " WHERE 1" . $sWhere) > 0;
    }

    protected function delete($sTable, $aWhere)
    {
        $sWhere = "";
        foreach($aWhere as $sKey => $sValue)
            $sWhere .= $this->prepare(" AND `" . $sKey . "`=?", $sValue);

        return (int)$this->query("DELETE FROM `" . $this->_sPrefix . $sTable . "` WHERE 1" . $sWhere) > 0;
    }
}

/** @} */
