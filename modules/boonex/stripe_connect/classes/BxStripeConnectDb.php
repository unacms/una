<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxStripeConnectDb extends BxBaseModConnectDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getAccount($aParams = [])
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

    	$sJoinClause = $sWhereClause = "";
        switch($aParams['sample']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause = " AND `te`.`id`=:id";
                break;

            case 'profile_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'profile_id' => $aParams['profile_id']
                ];

                $sWhereClause = " AND `te`.`profile_id`=:profile_id";
                break;
            
            case 'account_id':
                $aMethod['name'] = 'getRow';
                
                if(!empty($aParams['live_account_id'])) {
                    $aMethod['params'][1] = [
                        'live_account_id' => $aParams['live_account_id']
                    ];

                    $sWhereClause = " AND `te`.`live_account_id`=:live_account_id";
                }

                if(!empty($aParams['test_account_id'])) {
                    $aMethod['params'][1] = [
                        'test_account_id' => $aParams['test_account_id']
                    ];

                    $sWhereClause = " AND `te`.`test_account_id`=:test_account_id";
                }
                break;
        }

        $aMethod['params'][0] = "SELECT `te`.*
            FROM `" . $CNF['TABLE_ENTRIES'] . "` AS `te`" . $sJoinClause . "
            WHERE 1" . $sWhereClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function insertAccount($aSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($aSet[$CNF['FIELD_ADDED']]))
            $aSet[$CNF['FIELD_ADDED']] = time();

        if(!isset($aSet[$CNF['FIELD_CHANGED']]))
            $aSet[$CNF['FIELD_CHANGED']] = time();

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_ENTRIES'] . "` SET " . $this->arrayToSQL($aSet)) > 0;
    }

    public function updateAccount($aSet, $aWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($aSet[$CNF['FIELD_CHANGED']]))
            $aSet[$CNF['FIELD_CHANGED']] = time();

        return (int)$this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET " . $this->arrayToSQL($aSet) . " WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1")) > 0;
    }

    public function deleteAccount($aWhere)
    {
    	if(empty($aWhere))
            return false;

        return $this->query("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE " . $this->arrayToSQL($aWhere, ' AND ')) !== false;
    }

    public function hasAccount($iProfileId, $sMode)
    {
        return (int)$this->getOne("SELECT `id` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `profile_id` = :profile_id AND `" . $sMode . "_account_id` <> '' LIMIT 1", [
            'profile_id' => $iProfileId,
    	]) > 0;
    }

    /*
     * Commissions methods
     */
    public function getCommissions($aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

        $sSelectClause = "`tc`.*";
        $sWhereClause = $sOrderClause = "";
        if(!empty($aParams['type']))
            switch($aParams['type']) {
                case 'max_order':
                    $aMethod['name'] = 'getOne';
                    $aMethod['params'][1] = [];

                    $sSelectClause = "IFNULL(MAX(`tc`.`order`), 0)";
                    break;

                case 'id':
                    $aMethod['name'] = 'getRow';
                    $aMethod['params'][1] = [
                        'id' => $aParams['id']
                    ];

                    $sWhereClause = " AND `tc`.`id`=:id";
                    break;

                case 'acl_id':
                    $aMethod['name'] = 'getRow';
                    $aMethod['params'][1] = [
                        'acl_id' => $aParams['acl_id']
                    ];

                    $sWhereClause = " AND `tc`.`acl_id`=:acl_id";
                    break;

                case 'all':
                    if(!empty($aParams['active'])) 
                        $sWhereClause = " AND `tc`.`active`='1'";
                    break;
            }

        if(!empty($sOrderClause))
            $sOrderClause = " ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . "
            FROM `" . $CNF['TABLE_COMMISSIONS'] . "` AS `tc`
            WHERE 1" . $sWhereClause . $sOrderClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }
}

/** @} */
