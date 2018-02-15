<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Invites Invites
 * @ingroup     UnaModules
 *
 * @{
 */

class BxInvDb extends BxDolModuleDb
{
    protected $_oConfig;

    protected $_sTableInvites;
    protected $_sTableRequests;

    /*
     * Constructor.
     */
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;
        $CNF = $oConfig->CNF;
        $this->_sTableInvites = $CNF['TABLE_INVITES'];
        $this->_sTableRequests = $CNF['TABLE_REQUESTS'];
    }

	public function getInvites($aParams, $bReturnCount = false)
    {
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`{$this->_sTableInvites}`.*";

        switch($aParams['type']) {
            case 'count_by_account':
            	$aMethod['name'] = 'getOne';
                $aMethod['params'][1] = array(
                	'account_id' => $aParams['value']
                );

                $sSelectClause = "COUNT(`{$this->_sTableInvites}`.`id`) AS `count`";
                $sWhereClause = "AND `{$this->_sTableInvites}`.`account_id`=:account_id ";
                $sLimitClause = "LIMIT 1";
                break;
        }

        $sSql = "SELECT {select} FROM `{$this->_sTableInvites}` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " {order} {limit}";

        $aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array($sSelectClause, $sOrderClause, $sLimitClause), $sSql);
        $aEntries = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
        	return $aEntries;

        $aMethod['name'] = 'getOne';
		$aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array("COUNT(*)", "", ""), $sSql);

		return array($aEntries, (int)call_user_func_array(array($this, $aMethod['name']), $aMethod['params']));
    }

	public function deleteInvites($aParams)
    {
        $sSql = "DELETE FROM `{$this->_sTableInvites}` WHERE " . $this->arrayToSQL($aParams, " AND ");
        return $this->query($sSql);
    }
    
    public function attachInviteToRequest($iReqestId, $iInviteId)
    {
        $aBindings = array(
            'id' => $iReqestId,
            'invite_id' => $iInviteId
        );
        $this->query("UPDATE `{$this->_sTableRequests}` SET invite_id=:invite_id WHERE id=:id", $aBindings);
    }
    
    public function attachAccountIdToInvite($iAccountId, $sKey)
    {
        $aBindings = array(
            'joined_account_id' => $iAccountId,
            'keyvalue' => $sKey,
            'date_joined' => time(),
        );
        $this->query("UPDATE `{$this->_sTableInvites}` SET `joined_account_id`=:joined_account_id, `date_joined`=:date_joined WHERE `key`=:keyvalue", $aBindings);
    }
    
    public function updateDateSeenForInvite($sKey)
    {
        $aBindings = array(
            'date_seen' => time(),
            'keyvalue' => $sKey
        );
        $this->query("UPDATE `{$this->_sTableInvites}` SET `date_seen`=:date_seen WHERE `key`=:keyvalue", $aBindings);
    }
    
    public function insertInvite($iAccountId, $iProfileId, $sKey, $sEmail, $iDate) 
    {
        $aBindings = array(
			'account_id' => $iAccountId,
			'profile_id' => $iProfileId,
			'keyvalue' => $sKey,
			'email' => $sEmail,
			'date' => $iDate
		);
        $this->query("INSERT `{$this->_sTableInvites}` (account_id, profile_id, `key`, email, date) VALUES (:account_id, :profile_id, :keyvalue, :email, :date)", $aBindings);  
        return (int)$this->lastId();
    }

	public function getRequests($aParams, $bReturnCount = false)
    {
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sWhereClause = $sOrderClause = $sLimitClause = "";
        $sJoinClause = " LEFT JOIN `{$this->_sTableInvites}` ON `bx_inv_invites`.`id` = `bx_inv_requests`.`invite_id` ";
        $sSelectClause = "`{$this->_sTableRequests}`.*, `{$this->_sTableInvites}`.`date_seen`, `{$this->_sTableInvites}`.`date_joined`, `{$this->_sTableInvites}`.`joined_account_id` AS joined_account, `{$this->_sTableInvites}`.`date` AS `date_invite` ";

        switch($aParams['type']) {
        	case 'by_id':
        		$aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

        		$sWhereClause = "AND `{$this->_sTableRequests}`.`id`=:id ";
        		$sLimitClause = "LIMIT 1";
        		break;

            case 'count_all':
            	$aMethod['name'] = 'getOne';

                $sSelectClause = "COUNT(`{$this->_sTableRequests}`.`id`) AS `count`";
                $sLimitClause = "LIMIT 1";
                break;
        }

        $sSql = "SELECT {select} FROM `{$this->_sTableRequests}` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " {order} {limit}";

        $aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array($sSelectClause, $sOrderClause, $sLimitClause), $sSql);
        $aEntries = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
        	return $aEntries;

		$aMethod['name'] = 'getOne';
		$aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array("COUNT(*)", "", ""), $sSql);

		return array($aEntries, (int)call_user_func_array(array($this, $aMethod['name']), $aMethod['params']));
    }
}

/** @} */
