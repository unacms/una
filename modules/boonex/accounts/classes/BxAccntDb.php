<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Accounts Accounts
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAccntDb extends BxBaseModGeneralDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getAccountFields()
    {
        return $this->getFields('sys_accounts');
    }

    public function getAccountIds()
    {
    	return $this->getColumn("SELECT `id` FROM `sys_accounts` WHERE 1", "id");
    }

    public function updateAccount($aSet, $aWhere)
    {
    	return (int)$this->query("UPDATE `sys_accounts` SET " . $this->arrayToSQL($aSet) . " WHERE " . $this->arrayToSQL($aWhere, ' AND ')) > 0;
    }
    
    public function getEntriesNumByParams ($aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;
        
        $sSql = "SELECT COUNT(*) FROM `sys_accounts` WHERE 1";
        
        foreach($aParams as $aValue){
            $sSql .= ' AND ' . (isset($aValue['table'])? '`' . $aValue['table'] .'`.' : '') . '`' . $aValue['key'] ."` " . $aValue['operator'] . " '" . $aValue['value'] . "'";
        }
        
        $sQuery = $this->prepare($sSql);
        return $this->getOne($sQuery);
    }
}

/** @} */
