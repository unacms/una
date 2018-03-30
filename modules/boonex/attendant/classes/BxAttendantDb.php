<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Attendant Attendant
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolModuleDb');

class BxAttendantDb extends BxBaseModGeneralDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;
        $CNF = $oConfig->CNF;
        $this->_sTableEntries = $CNF['TABLE_ENTRIES'];
    }
    
    public function getEvents($aParams, $bReturnCount = false)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`{$this->_sTableEntries}`.*";

        switch($aParams['type']) {
            case 'active_by_action_and_object_id':
                $aMethod['name'] = 'getAll';
                $aMethod['params'][1] = array(
                    'action' => $aParams['action'],
                    'object_id' => $aParams['object_id']
                );
                $sWhereClause = "AND `{$this->_sTableEntries}`.`" . $CNF['FIELD_ACTION'] . "` =:action  AND `{$this->_sTableEntries}`.`" . $CNF['FIELD_OBJECT_ID'] . "` =:object_id AND `" . $CNF['FIELD_PROCESSED'] . "` IS NULL";
                break;
            case 'all':
                $aMethod['name'] = 'getAll';
                $aMethod['params'][1] = array(
                    'request_id' => $aParams['value']
                );
                break;
        }

        $sSql = "SELECT {select} FROM `{$this->_sTableEntries}` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " {order} {limit}";
        
        $aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array($sSelectClause, $sOrderClause, $sLimitClause), $sSql);
        $aEntries = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
        
        if(!$bReturnCount)
            return $aEntries;

        $aMethod['name'] = 'getOne';
        $aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array("COUNT(*)", "", ""), $sSql);

        return array($aEntries, (int)call_user_func_array(array($this, $aMethod['name']), $aMethod['params']));
    }
    
    public function addEvent($sMethod, $sAction, $iObjectId)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'method' => $sMethod,
            'action' => $sAction,
            'added' => time(),
            'object_id' => $iObjectId
        );
        $this->query("INSERT INTO `" . $CNF['TABLE_ENTRIES'] . "` (`" . $CNF['FIELD_METHOD'] . "`, `" . $CNF['FIELD_ACTION'] . "`, `" . $CNF['FIELD_ADDED'] . "`, `" . $CNF['FIELD_OBJECT_ID'] . "`) values (:method, :action, :added, :object_id)", $aBindings);
    }
    
    public function setEventProcessed($id)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'processed' => time()
        );
        $this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET   `" . $CNF['FIELD_PROCESSED'] . "` = :processed", $aBindings);
    }
}

/** @} */
