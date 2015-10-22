<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseNotifications Base classes for Notifications like modules
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxBaseModNotificationsDb extends BxDolModuleDb
{
    protected $_oConfig;

    protected $_sTable;
    protected $_sTableHandlers;

    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;

		$this->_sTable = $this->_sPrefix . 'events';
		$this->_sTableHandlers = $this->_sPrefix . 'handlers';
    }

    public function getAlertHandlerId()
    {
        $sQuery = $this->prepare("SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=? LIMIT 1", $this->_oConfig->getObject('alert'));
        return (int)$this->getOne($sQuery);
    }

    public function insertData($aData)
    {
    	$aHandlerDescriptor = $this->_oConfig->getHandlerDescriptor();

    	//--- Update Timeline Handlers ---//
        foreach($aData['handlers'] as $aHandler) {
            $sContent = '';
            if($aHandler['type'] == BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT) {
            	if(empty($aHandler['module_class']))
            		$aHandler['module_class'] = 'Module';

            	$sContent = serialize(array_intersect_key($aHandler, $aHandlerDescriptor));
            }

            $sQuery = $this->prepare("INSERT INTO
                    `{$this->_sTableHandlers}`
                SET
                	`group`=?,
                    `type`=?,
                    `alert_unit`=?,
                    `alert_action`=?,
                    `content`=?", $aHandler['group'], $aHandler['type'], $aHandler['alert_unit'], $aHandler['alert_action'], $sContent);

            $this->query($sQuery);
        }

        //--- Update System Alerts ---//
        $iHandlerId = $this->getAlertHandlerId();
        foreach($aData['alerts'] as $aAlert) {
            $sQuery = $this->prepare("INSERT INTO
                    `sys_alerts`
                SET
                    `unit`=?,
                    `action`=?,
                    `handler_id`=?", $aAlert['unit'], $aAlert['action'], $iHandlerId);

            $this->query($sQuery);
        }
    }

    public function deleteData($aData)
    {
    	//--- Update Timeline Handlers ---//
        foreach($aData['handlers'] as $aHandler) {
            $sQuery = $this->prepare("DELETE FROM
                    `{$this->_sTableHandlers}`
                WHERE
                    `alert_unit`=? AND
                    `alert_action`=?
                LIMIT 1", $aHandler['alert_unit'], $aHandler['alert_action']);

            $this->query($sQuery);
        }

        //--- Update System Alerts ---//
        $iHandlerId = $this->getAlertHandlerId();
        foreach($aData['alerts'] as $aAlert) {
            $sQuery = $this->prepare("DELETE FROM
                    `sys_alerts`
                WHERE
                    `unit`=? AND
                    `action`=? AND
                    `handler_id`=?
                LIMIT 1", $aAlert['unit'], $aAlert['action'], $iHandlerId);

            $this->query($sQuery);
        }
    }

    public function deleteModuleEvents($aData)
    {
		//Delete system events.
    	foreach($aData['handlers'] as $aHandler)
            $this->deleteEvent(array('type' => $aHandler['alert_unit'], 'action' => $aHandler['alert_action']));
    }

	public function activateModuleEvents($aData, $bActivate = true)
    {
    	$iActivate = $bActivate ? 1 : 0;

    	//Activate (deactivate) system events.
    	foreach($aData['handlers'] as $aHandler)    		
            $this->updateEvent(array('active' => $iActivate), array('type' => $aHandler['alert_unit'], 'action' => $aHandler['alert_action']));
    }

    public function getHandlers($aParams = array())
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sWhereClause = '';

        if(!empty($aParams))
            switch($aParams['type']) {
 				case 'by_group_key_type':
 					$aMethod['name'] = 'getAllWithKey';
 					$aMethod['params'][1] = 'type';

 					$sWhereClause = $this->prepare("AND `group`=?", $aParams['group']);
 					break;
            }

        $aMethod['params'][0] = "SELECT * FROM `{$this->_sTableHandlers}` WHERE 1 " . $sWhereClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertEvent($aParamsSet)
    {
        if(empty($aParamsSet))
            return 0;

        $aSet = array();
        foreach($aParamsSet as $sKey => $sValue)
           $aSet[] = $this->prepare("`" . $sKey . "`=?", $sValue);

		if(!isset($aParamsSet['date']))
			$aSet[] = "`date`=UNIX_TIMESTAMP()";

        if((int)$this->query("INSERT INTO `{$this->_sTable}` SET " . implode(", ", $aSet)) <= 0)
            return 0;

        return (int)$this->lastId();
    }

    public function updateEvent($aParamsSet, $aParamsWhere)
    {
        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        $sSql = "UPDATE `{$this->_sTable}` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql);
    }

    public function deleteEvent($aParams, $sWhereAddon = "")
    {
        $sSql = "DELETE FROM `{$this->_sTable}` WHERE " . $this->arrayToSQL($aParams, " AND ") . $sWhereAddon;
        return $this->query($sSql);
    }

	public function getEvents($aParams, $bReturnCount = false)
    {
        list($sMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause) = $this->_getSqlPartsEvents($aParams);

        $sSql = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . $sSelectClause . "
                `{$this->_sTable}`.*
            FROM `{$this->_sTable}`
            LEFT JOIN `{$this->_sTableHandlers}` ON `{$this->_sTable}`.`type`=`{$this->_sTableHandlers}`.`alert_unit` AND `{$this->_sTable}`.`action`=`{$this->_sTableHandlers}`.`alert_action` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        $aEntries = $this->$sMethod($sSql);
        if(!$bReturnCount)
        	return $aEntries;

		return array($aEntries, (int)$this->getOne("SELECT FOUND_ROWS()"));
    }

	protected function _getSqlPartsEvents($aParams)
    {
    	$sMethod = 'getAll';
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['browse']) {
            case 'id':
                $sMethod = 'getRow';
                $sWhereClause = $this->prepare("AND `{$this->_sTable}`.`id`=? ", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;

			case 'first':
				$sMethod = 'getRow';
				list($sJoinClause, $sWhereClause) = $this->_getSqlPartsEventsList($aParams);
				$sOrderClause = "ORDER BY `{$this->_sTable}`.`date` DESC";
				$sLimitClause = "LIMIT 1";
				break;

			case 'last':
				$sMethod = 'getRow';
				list($sJoinClause, $sWhereClause) = $this->_getSqlPartsEventsList($aParams);
				$sOrderClause = "ORDER BY `{$this->_sTable}`.`date` ASC";
				$sLimitClause = "LIMIT 1";
				break;

			case 'list':
				list($sJoinClause, $sWhereClause) = $this->_getSqlPartsEventsList($aParams);
				$sOrderClause = "ORDER BY `{$this->_sTable}`.`date` DESC";
				$sLimitClause = isset($aParams['per_page']) ? "LIMIT " . $aParams['start'] . ", " . $aParams['per_page'] : "";
				break;
        }

        return array($sMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);
    }

    protected function _getSqlPartsEventsList($aParams)
    {
    	$sJoinClause = $sWhereClause = "";

    	return array($sJoinClause, $sWhereClause);
    }
}

/** @} */
