<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Invites Invites
 * @ingroup     TridentModules
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

        $this->_sTableInvites = $this->_sPrefix . 'invites';
        $this->_sTableRequests = $this->_sPrefix . 'requests';
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

	public function getRequests($aParams, $bReturnCount = false)
    {
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`{$this->_sTableRequests}`.*";

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
