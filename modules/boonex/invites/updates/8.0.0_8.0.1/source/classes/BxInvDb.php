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

bx_import('BxDolModuleDb');

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
    	$sMethod = 'getAll';
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`{$this->_sTableInvites}`.*";

        switch($aParams['type']) {
            case 'count_by_account':
                $sMethod = 'getOne';
                $sSelectClause = "COUNT(`{$this->_sTableInvites}`.`id`) AS `count`";
                $sWhereClause = $this->prepare("AND `{$this->_sTableInvites}`.`account_id`=? ", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;
        }

        $sSql = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . " " . $sSelectClause . "
            FROM `{$this->_sTableInvites}` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        $aEntries = $this->$sMethod($sSql);
        if(!$bReturnCount)
        	return $aEntries;

		return array($aEntries, (int)$this->getOne("SELECT FOUND_ROWS()"));
    }

	public function deleteInvites($aParams)
    {
        $sSql = "DELETE FROM `{$this->_sTableInvites}` WHERE " . $this->arrayToSQL($aParams, " AND ");
        return $this->query($sSql);
    }

	public function getRequests($aParams, $bReturnCount = false)
    {
    	$sMethod = 'getAll';
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`{$this->_sTableRequests}`.*";

        switch($aParams['type']) {
        	case 'by_id':
        		$sMethod = 'getRow';
        		$sWhereClause = $this->prepare("AND `{$this->_sTableRequests}`.`id`=? ", $aParams['value']);
        		$sLimitClause = "LIMIT 1";
        		break;

            case 'count_all':
                $sMethod = 'getOne';
                $sSelectClause = "COUNT(`{$this->_sTableRequests}`.`id`) AS `count`";
                $sLimitClause = "LIMIT 1";
                break;
        }

        $sSql = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . " " . $sSelectClause . "
            FROM `{$this->_sTableRequests}` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        $aEntries = $this->$sMethod($sSql);
        if(!$bReturnCount)
        	return $aEntries;

		return array($aEntries, (int)$this->getOne("SELECT FOUND_ROWS()"));
    }
}

/** @} */
