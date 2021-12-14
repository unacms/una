<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Xero Xero
 * @ingroup     UnaModules
 *
 * @{
 */

class BxXeroDb extends BxDolModuleDb
{
    protected $_oConfig;

    protected $_aDataKeys;

    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;

        $this->_aDataKeys = ['token', 'expires', 'tenant_id', 'refresh_token', 'id_token'];
    }

    public function setData($aData)
    {
        foreach($aData as $sKey => $sValue) 
            $this->setParam('bx_xero_' . $sKey, $sValue);
    }

    public function cleanData()
    {
        foreach($this->_aDataKeys as $sKey)
            $aData[$sKey] = $this->setParam('bx_xero_' . $sKey, '');
    }

    public function getData()
    {
        $aData = [];
        foreach($this->_aDataKeys as $sKey)
            $aData[$sKey] = $this->getParam('bx_xero_' . $sKey);

        return $aData;
    }
    
    public function getContact($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sSelectClause = "`tc`.*";
    	$sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";
        switch($aParams['type']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `tc`.`" . $CNF['FIELD_ID'] . "`=:id";
                break;

            case 'email':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'email' => $aParams['email']
                );

                $sWhereClause = " AND `tc`.`" . $CNF['FIELD_EMAIL'] . "`=:email";
                break;

            case 'all':
                break;
        }

        $sOrderClause = !empty($sOrderClause) ? " ORDER BY " . $sOrderClause : $sOrderClause;
        $sLimitClause = !empty($sLimitClause) ? " LIMIT " . $sLimitClause : $sLimitClause;

        $aMethod['params'][0] = "SELECT
            " . $sSelectClause . "
            FROM `" . $CNF['TABLE_CONTACTS'] . "` AS `tc`" . $sJoinClause . "
            WHERE 1" . $sWhereClause . $sOrderClause . $sLimitClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function isContact($sEmail) 
    {
        $aContact = $this->getContact([
            'type' => 'email', 
            'email' => $sEmail
        ]);

        return !empty($aContact) && is_array($aContact);
    }

    public function insertContact($aSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aSet))
            return false;

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_CONTACTS'] . "` SET " . $this->arrayToSQL($aSet)) > 0;
    }

    public function updateContact($aSet, $aWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aSet) || empty($aWhere))
            return false;

        return (int)$this->query("UPDATE `" . $CNF['TABLE_CONTACTS'] . "` SET " . $this->arrayToSQL($aSet) . " WHERE " . $this->arrayToSQL($aWhere, ' AND ')) > 0;
    }

    public function deleteContact($aWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aWhere))
            return false;

        return (int)$this->query("DELETE FROM `" . $CNF['TABLE_CONTACTS'] . "` WHERE " . $this->arrayToSQL($aWhere, ' AND ')) > 0;
    }
}

/** @} */
