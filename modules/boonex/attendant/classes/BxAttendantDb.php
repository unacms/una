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
            case 'active_by_action_and_profile_id':
                $aMethod['name'] = 'getAll';
                $aMethod['params'][1] = array(
                    'action' => $aParams['action'],
                    'profile_id' => $aParams['profile_id']
                );
                $sWhereClause = "AND `{$this->_sTableEntries}`.`" . $CNF['FIELD_ACTION'] . "` =:action  AND `{$this->_sTableEntries}`.`" . $CNF['FIELD_PROFILE_ID'] . "` =:profile_id AND `" . $CNF['FIELD_PROCESSED'] . "` IS NULL";
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
    
    public function addEvent($sAction, $iObjectId, $sModule, $sEvent, $iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;
        $aBindings = array(
            'module' => $sModule,
            'event' => $sEvent,
            'action' => $sAction,
            'added' => time(),
            'object_id' => $iObjectId,
            'profile_id' => $iProfileId,
            'event' => $sEvent
        );
        
        $this->query("INSERT INTO `" . $CNF['TABLE_ENTRIES'] . "` (`" . $CNF['FIELD_MODULE'] . "`, `" . $CNF['FIELD_EVENT'] . "`, `" . $CNF['FIELD_ACTION'] . "`, `" . $CNF['FIELD_ADDED'] . "`, `" . $CNF['FIELD_OBJECT_ID'] . "`, `" . $CNF['FIELD_PROFILE_ID'] . "`) values (:module, :event, :action, :added, :object_id, :profile_id)", $aBindings);
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
