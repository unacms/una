<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleDb');

class BxTimelineDb extends BxDolModuleDb
{
    var $_oConfig;
    /*
     * Constructor.
     */
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;
    }

    function getAlertHandlerId()
    {
    	$sQuery = $this->prepare("SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=? LIMIT 1", $this->_oConfig->getSystemName('alert'));
        return (int)$this->getOne($sQuery);
    }

    function insertData($aData)
    {
        foreach($aData['handlers'] as $aHandler) {
            //--- Delete module related events ---//
            $this->deleteEvent(array('type' => $aHandler['alert_unit'], 'action' => $aHandler['alert_action']));

            $sContent = '';
            if($aHandler['type'] == BX_TIMELINE_HANDLER_TYPE_INSERT)
            	$sContent = serialize(array(
            		'module_name' => $aHandler['module_name'],
            		'module_method' => $aHandler['module_method'],
            		'module_params' => $aHandler['module_params'],
            		'module_class' => $aHandler['module_class'],
            		'groupable' => $aHandler['groupable'],
            		'group_by' => $aHandler['group_by']
            	));

            //--- Update Timeline Handlers ---//
            $sQuery = $this->prepare("INSERT INTO 
            		`" . $this->_sPrefix . "handlers`
            	SET
            		`type`=?,
            		`alert_unit`=?, 
            		`alert_action`=?, 
            		`content`=?", $aHandler['type'], $aHandler['alert_unit'], $aHandler['alert_action'], $sContent);

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

    function deleteData($aData)
    {
        foreach($aData['handlers'] as $aHandler) {
            //--- Delete module related events ---//
            $this->deleteEvent(array('type' => $aHandler['alert_unit'], 'action' => $aHandler['alert_action']));

            //--- Update Wall Handlers ---//
            $sQuery = $this->prepare("DELETE FROM 
            		`" . $this->_sPrefix . "handlers` 
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

    function getHandlers($aParams = array())
    {
        $sMethod = 'getAll';
        $sWhereClause = '';

        if(!empty($aParams))
	        switch($aParams['type']) {}

        $sSql = "SELECT
                `id` AS `id`,
                `type` AS `type`, 
                `alert_unit` AS `alert_unit`,
                `alert_action` AS `alert_action`,
                `content` AS `content`
            FROM `" . $this->_sPrefix . "handlers`
            WHERE 1 AND `alert_unit` NOT LIKE ('" . $this->_oConfig->getPrefix('common_post') . "%')" . $sWhereClause;

        return $this->$sMethod($sSql);
    }

    function insertEvent($aParamsSet)
    {
    	if(empty($aParamsSet))
    		return 0;

    	$aSet = array();
        foreach($aParamsSet as $sKey => $sValue)
           $aSet[] = $this->prepare("`" . $sKey . "`=?", $sValue);
           
        if((int)$this->query("INSERT INTO `" . $this->_sPrefix . "events` SET " . implode(", ", $aSet) . ", `date`=UNIX_TIMESTAMP()") <= 0)
            return 0;

        $iId = (int)$this->lastId();
        if($iId > 0 && isset($aParamsSet['owner_id']) && (int)$aParamsSet['owner_id'] > 0) {
			//--- Wall -> Update for Alerts Engine ---//
            bx_import('BxDolAlerts');
            $oAlert = new BxDolAlerts('bx_' . $this->_oConfig->getUri(), 'update', $aParamsSet['owner_id']);
            $oAlert->alert();
            //--- Wall -> Update for Alerts Engine ---//
        }

        return $iId;
    }
    function updateEvent($aParamsSet, $aParamsWhere)
    {
    	if(empty($aParamsSet) || empty($aParamsWhere))
    		return false;

        $aSet = array();
        foreach($aParamsSet as $sKey => $sValue)
           $aSet[] = $this->prepare("`" . $sKey . "`=?", $sValue);

		$aWhere = array();
        foreach($aParamsWhere as $sKey => $sValue)
           $aWhere[] = $this->prepare("`" . $sKey . "`=?", $sValue);

        $sSql = "UPDATE `" . $this->_sPrefix . "events` SET " . implode(", ", $aSet) . " WHERE " . implode(" AND ", $aWhere);
        return $this->query($sSql);
    }

    function deleteEvent($aParams, $sWhereAddon = "")
    {
        $aWhere = array();
        foreach($aParams as $sKey => $sValue)
           $aWhere[] = $this->prepare("`" . $sKey . "`=?", $sValue);

        $sSql = "DELETE FROM `" . $this->_sPrefix . "events` WHERE " . implode(" AND ", $aWhere) . $sWhereAddon;
        return $this->query($sSql);
    }

    function getEvents($aParams)
    {
    	$sMethod = 'getAll';
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sWhereModuleFilter = '';
        if(isset($aParams['modules']) && !empty($aParams['modules']) && is_array($aParams['modules']))
        	$sWhereModuleFilter = "AND `type` IN (" . $this->implode_escape($aParams['modules']) . ") ";

        if(isset($aParams['timeline'])) {            
            $iDays = (int)$aParams['timeline'];
            $iNowMorning = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $sWhereClause .= $this->prepare("AND `date`>=? ", ($iNowMorning - 86400 * $iDays));
        }

        switch($aParams['type']) {
            case 'id':
            	$sMethod = 'getRow';
                $sWhereClause = $this->prepare("AND `te`.`id`=? ", $aParams['object_id']);
                $sLimitClause = "LIMIT 1";
                break;

            case 'owner':
		        if($sWhereModuleFilter == '') {
					$aHidden = $this->_oConfig->getHandlersHidden();
					$sWhereModuleFilter = is_array($aHidden) && !empty($aHidden) ? "AND `th`.`id` NOT IN (" . $this->implode_escape($aHidden) . ") " : "";
				}

                if(!empty($aParams['owner_id'])) {
                    if(!is_array($aParams['owner_id']))
                        $sWhereClause .= $this->prepare("AND `te`.`owner_id`=? ", $aParams['owner_id']);
                    else
                        $sWhereClause .= "AND `te`.`owner_id` IN (" . $this->implode_escape($aParams['owner_id']) . ") ";
                }

                $sWhereClause .= isset($aParams['filter']) ? $this->_getFilterAddon($aParams['owner_id'], $aParams['filter']) : '';
                $sWhereClause .= $sWhereModuleFilter;
                $sOrderClause = isset($aParams['order']) ? "ORDER BY `te`.`date` " . strtoupper($aParams['order']) : "";
                $sLimitClause = isset($aParams['count']) ? "LIMIT " . $aParams['start'] . ", " . $aParams['count'] : "";
                break;

            case 'last':
            	$sMethod = 'getRow';

		        if($sWhereModuleFilter == '') {
					$aHidden = $this->_oConfig->getHandlersHidden();
					$sWhereModuleFilter = is_array($aHidden) && !empty($aHidden) ? "AND `th`.`id` NOT IN (" . $this->implode_escape($aHidden) . ") " : "";
				}

                if(!empty($aParams['owner_id'])) {
                    if(!is_array($aParams['owner_id']))
                        $sWhereClause .= $this->prepare("AND `te`.`owner_id`=? ", $aParams['owner_id']);
                    else
                        $sWhereClause .= "AND `te`.`owner_id` IN (" . $this->implode_escape($aParams['owner_id']) . ") ";
                }

                $sWhereClause .= isset($aParams['filter']) ? $this->_getFilterAddon($aParams['owner_id'], $aParams['filter']) : '';
                $sWhereClause .= $sWhereModuleFilter;
                $sOrderClause = "ORDER BY `te`.`date` ASC";
                $sLimitClause = "LIMIT 1";
                break;
        }

        $sSql = "SELECT
                `te`.`id` AS `id`,
                `te`.`owner_id` AS `owner_id`,
                `te`.`object_id` AS `object_id`,
                `te`.`type` AS `type`,
                `te`.`action` AS `action`,
                `te`.`content` AS `content`,
                `te`.`date` AS `date`,
                ROUND((UNIX_TIMESTAMP() - `te`.`date`)/86400) AS `ago_days`
            FROM `" . $this->_sPrefix . "events` AS `te`
            LEFT JOIN `" . $this->_sPrefix . "handlers` AS `th` ON `te`.`type`=`th`.`alert_unit` AND `te`.`action`=`th`.`alert_action` " . $sJoinClause . " 
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return $this->$sMethod($sSql);
    }

    public function getMaxDuration($iOwnerId, $sFilter, $aModules)
    {
		$aEvent = $this->getEvents(array('type' => 'last', 'owner_id' => $iOwnerId, 'filter' => $sFilter, 'modules' => $aModules));
        if(empty($aEvent) || !is_array($aEvent))
            return 0;

        return (int)$aEvent['ago_days'];
    }
    
    
    
    
    
    
    
    
    function deleteEventCommon($aParams)
    {
        return $this->deleteEvent($aParams, " AND `type` LIKE '" . $this->_oConfig->getCommonPostPrefix() . "%'");
    }
    function getUser($mixed, $sType = 'id')
    {
        switch($sType) {
            case 'id':
                $sWhereClause = "`ID`='" . $mixed . "'";
                break;
            case 'username':
                $sWhereClause = "`NickName`='" . $mixed . "'";
                break;
        }

        $sSql = "SELECT `ID` AS `id`, `Couple` AS `couple`, `NickName` AS `username`, `Password` AS `password`, `Email` AS `email`, `Sex` AS `sex`, `Status` AS `status` FROM `Profiles` WHERE " . $sWhereClause . " LIMIT 1";
        $aUser = $this->getRow($sSql);

        if(empty($aUser))
            $aUser = array('id' => 0, 'couple' => 0, 'username' => _t('_wall_anonymous'), 'password' => '', 'email' => '', 'sex' => 'male');

        return $aUser;
    }

    //--- View Events Functions ---//


    function getEventsCount($iOwnerId, $sFilter, $sTimeline, $aModules)
    {
        $sWhereClause = "";
        if(!empty($iOwnerId)) {
            if(!is_array($iOwnerId))
                $sWhereClause = "`owner_id`='" . $iOwnerId . "' ";
            else
                $sWhereClause = "`owner_id` IN ('" . implode("','", $iOwnerId) . "') ";
        }

    	if(!empty($sTimeline) && strpos($sTimeline, BX_WALL_DIVIDER_TIMELINE) !== false) {
            list($iTLStart, $iTLEnd) = explode(BX_WALL_DIVIDER_TIMELINE, $sTimeline);

            $iNowMorning = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $iNowEvening = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
            $sWhereClause .= "AND `date`>='" . ($iNowMorning - 86400 * $iTLEnd) . "' AND `date`<='" . ($iNowEvening - 86400 * $iTLStart) . "' ";
        }

        if(!empty($aModules) && is_array($aModules))
        	$sWhereClause .= "AND `type` IN ('" . implode("','", $aModules) . "') ";

		$sWhereClause .= $this->_getFilterAddon($iOwnerId, $sFilter);

        $sSql = "SELECT COUNT(*) FROM `" . $this->_sPrefix . "events` WHERE " . $sWhereClause . " LIMIT 1";
        return $this->getOne($sSql);
    }

    function updateSimilarObject($iId, &$oAlert, $sDuration = 'day')
    {
        $sType = $oAlert->sUnit;
        $sAction = $oAlert->sAction;

        //Check handler
        $aHandler = $this->_oConfig->getHandlers($sType . '_' . $sAction);
        if(empty($aHandler) || !is_array($aHandler) || (int)$aHandler['groupable'] != 1)
            return false;

        //Check content's extra values
        if(isset($aHandler['group_by']) && !empty($aHandler['group_by']) && (!isset($oAlert->aExtras[$aHandler['group_by']]) || empty($oAlert->aExtras[$aHandler['group_by']])))
            return false;

        $sWhereClause = "";
        switch($sDuration) {
            case 'day':
                $iDayStart  = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                $iDayEnd  = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
                $sWhereClause .= "AND `date`>" . $iDayStart . " AND `date`<" . $iDayEnd . " ";
                break;
        }

        if(isset($aHandler['group_by']))
            $sWhereClause .= "AND `content` LIKE '%" . $oAlert->aExtras[$aHandler['group_by']] . "%' ";

        $sSql = "UPDATE `" . $this->_sPrefix . "events`
            SET
                `object_id`=CONCAT(`object_id`, '," . $oAlert->iObject . "'),
                `title`='',
                `description`='',
                `date`=UNIX_TIMESTAMP()
            WHERE
                `id`<>'" . $iId . "' AND
                `owner_id`='" . $oAlert->iSender . "' AND
                `type`='" . $sType . "' AND
                `action`='" . $sAction . "' " . $sWhereClause;
        $mixedResult = $this->query($sSql);

        if((int)$mixedResult > 0)
            $this->deleteEvent(array('id' => $iId));

        return $mixedResult;
    }

    //--- Comment Functions ---//
    function getCommentsCount($iId)
    {
        $sSql = "SELECT COUNT(`cmt_id`) FROM `" . $this->_sPrefix . "comments` WHERE `cmt_object_id`='" . $iId . "' AND `cmt_parent_id`='0' LIMIT 1";
        return (int)$this->getOne($sSql);
    }

    //--- Shared Media Functions ---//
    function getSharedCategory($sType, $iId)
    {
        $aType2Db = array(
            'sharedPhoto' => array('table' =>'bx_shared_photo_files', 'id' => 'medID'),
            'sharedMusic' => array('table' => 'RayMp3Files', 'id' => 'ID'),
            'sharedVideo' => array('table' => 'RayVideoFiles', 'id' => 'ID')
        );

        $sSql = "SELECT `Categories` FROM `" . $aType2Db[$sType]['table'] . "` WHERE `" . $aType2Db[$sType]['id'] . "`='" . $iId . "' LIMIT 1";
        return $this->getOne($sSql);
    }

    //--- Private functions ---//
    function _getFilterAddon($iOwnerId, $sFilter)
    {
        switch($sFilter) {
            case BX_TIMELINE_FILTER_OWNER:
                $sFilterAddon = " AND `te`.`action`='' AND `te`.`object_id`='" . $iOwnerId . "' ";
                break;
            case BX_TIMELINE_FILTER_OTHER:
                $sFilterAddon = " AND `te`.`action`='' AND `te`.`object_id`<>'" . $iOwnerId . "' ";
                break;
            case BX_TIMELINE_FILTER_ALL:
            default:
                $sFilterAddon = "";
        }
        return $sFilterAddon;
    }
}

/** @} */ 
