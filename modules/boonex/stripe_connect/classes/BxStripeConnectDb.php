<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxStripeConnectDb extends BxBaseModConnectDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

	public function getAccount($aParams = array())
    {
    	$CNF = &$this->_oConfig->CNF;
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sJoinClause = $sWhereClause = "";
        switch($aParams['type']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                	'id' => $aParams['id']
                );

                $sWhereClause = " AND `te`.`id`=:id";
                break;

			case 'author':
				$aMethod['name'] = 'getRow';
				$aMethod['params'][1] = array(
                	'author' => $aParams['author']
                );

				$sWhereClause = " AND `te`.`author`=:author";
				break;
        }

        $aMethod['params'][0] = "SELECT
        		`te`.*
            FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `te`" . $sJoinClause . "
            WHERE 1" . $sWhereClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

	public function insertAccount($aSet)
    {
        $sQuery = "REPLACE INTO `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET " . $this->arrayToSQL($aSet);
        return (int)$this->query($sQuery) > 0;
    }

	public function updateAccount($aSet, $aWhere)
    {
        $sQuery = "UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET " . $this->arrayToSQL($aSet) . " WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1");
        return (int)$this->query($sQuery) > 0;
    }

	public function deleteAccount($aWhere)
    {
    	if(empty($aWhere))
    		return false;

        return $this->query("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE " . $this->arrayToSQL($aWhere, ' AND ')) !== false;
    }

    public function hasAccount($iAuthor)
    {
        return (int)$this->getOne("SELECT `id` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `author` = :author LIMIT 1", array(
    		'author' => $iAuthor,
    	)) > 0;
    }
}

/** @} */
