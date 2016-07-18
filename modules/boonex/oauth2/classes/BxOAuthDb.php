<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    OAuth2 OAuth2 server
 * @ingroup     TridentModules
 *
 * @{
 */

class BxOAuthDb extends BxDolModuleDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

	function getClientsBy($aParams = array())
    {
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['type']) {
            case 'client_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'client_id' => $aParams['client_id']
                );

                $sWhereClause .= "AND `tc`.`client_id`=:client_id";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'user_id':
                $aMethod['params'][1] = array(
                	'user_id' => $aParams['user_id']
                );

                $sWhereClause .= "AND `tc`.`user_id`=:user_id";
                break;
        }

        $aMethod['params'][0] = "SELECT * " . $sSelectClause . "
            FROM `bx_oauth_clients` AS `tc` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    function getClientTitle($sClientId)
    {
        $sQuery = $this->prepare("SELECT `title` FROM `bx_oauth_clients` WHERE `client_id` = ?", $sClientId);
        return $this->getOne($sQuery);
    }

	function addClient($aClient)
    {
        $mixedResult = $this->query("INSERT INTO `bx_oauth_clients` SET " . $this->arrayToSQL($aClient));
        return (int)$mixedResult > 0 ? $this->lastId() : false;
    }

    function deleteClients($aClients)
    {        
        foreach ($aClients as $sClientId) {
            $sQuery = $this->prepare("DELETE FROM `bx_oauth_clients` WHERE `client_id` = ?", $sClientId);
            $this->query($sQuery);
        }
    }

	function deleteClientsBy($aParams = array())
    {
    	if(empty($aParams))
    		return false;

        return $this->query("DELETE FROM `bx_oauth_clients` WHERE " . $this->arrayToSQL($aParams, ' AND '));
    }

    function getSavedProfile($aProfiles)
    {
        $aIds = array_keys($aProfiles);
        return $this->getOne("SELECT `user_id` FROM `bx_oauth_refresh_tokens` WHERE `user_id` IN (" . $this->implode_escape($aIds) . ") LIMIT 1");
    }

}

/** @} */
