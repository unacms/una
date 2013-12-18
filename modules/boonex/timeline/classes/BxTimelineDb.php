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

    var $_sTable;
    var $_sTableHandlers;
    var $_sTablesShareTrack;

    /*
     * Constructor.
     */
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;

        $this->_sTable = $this->_sPrefix . 'events';
        $this->_sTableHandlers = $this->_sPrefix . 'handlers';
        $this->_sTableSharesTrack = $this->_sPrefix . 'shares_track';
    }

    public function getAlertHandlerId()
    {
    	$sQuery = $this->prepare("SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=? LIMIT 1", $this->_oConfig->getSystemName('alert'));
        return (int)$this->getOne($sQuery);
    }

    public function insertData($aData)
    {
        foreach($aData['handlers'] as $aHandler) {
            //--- Delete module related events ---//
            $this->deleteEvent(array('type' => $aHandler['alert_unit'], 'action' => $aHandler['alert_action']));

            $sContent = '';
            if($aHandler['type'] == BX_TIMELINE_HANDLER_TYPE_INSERT)
            	$sContent = serialize(array(
            		'module_name' => $aHandler['module_name'],
            		'module_method' => $aHandler['module_method'],
            		'module_class' => !empty($aHandler['module_class']) ? $aHandler['module_class'] : 'Module',
            		'groupable' => $aHandler['groupable'],
            		'group_by' => $aHandler['group_by']
            	));

            //--- Update Timeline Handlers ---//
            $sQuery = $this->prepare("INSERT INTO 
            		`{$this->_sTableHandlers}`
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

    public function deleteData($aData)
    {
        foreach($aData['handlers'] as $aHandler) {
            //--- Delete module related events ---//
            $this->deleteEvent(array('type' => $aHandler['alert_unit'], 'action' => $aHandler['alert_action']));

            //--- Update Wall Handlers ---//
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

    public function getHandlers($aParams = array())
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
            FROM `{$this->_sTableHandlers}`
            WHERE 1 AND `alert_unit` NOT LIKE ('" . $this->_oConfig->getPrefix('common_post') . "%')" . $sWhereClause;

        return $this->$sMethod($sSql);
    }

    public function insertEvent($aParamsSet)
    {
    	if(empty($aParamsSet))
    		return 0;

    	$aSet = array();
        foreach($aParamsSet as $sKey => $sValue)
           $aSet[] = $this->prepare("`" . $sKey . "`=?", $sValue);

        if((int)$this->query("INSERT INTO `{$this->_sTable}` SET " . implode(", ", $aSet) . ", `date`=UNIX_TIMESTAMP()") <= 0)
            return 0;

        return (int)$this->lastId();
    }

    public function updateEvent($aParamsSet, $aParamsWhere)
    {
    	if(empty($aParamsSet) || empty($aParamsWhere))
    		return false;

        $aSet = array();
        foreach($aParamsSet as $sKey => $sValue)
           $aSet[] = $this->prepare("`" . $sKey . "`=?", $sValue);

		$aWhere = array();
        foreach($aParamsWhere as $sKey => $sValue)
           $aWhere[] = $this->prepare("`" . $sKey . "`=?", $sValue);

        $sSql = "UPDATE `{$this->_sTable}` SET " . implode(", ", $aSet) . " WHERE " . implode(" AND ", $aWhere);
        return $this->query($sSql);
    }

    public function deleteEvent($aParams, $sWhereAddon = "")
    {
        $aWhere = array();
        foreach($aParams as $sKey => $sValue)
           $aWhere[] = $this->prepare("`" . $sKey . "`=?", $sValue);

        $sSql = "DELETE FROM `{$this->_sTable}` WHERE " . implode(" AND ", $aWhere) . $sWhereAddon;
        return $this->query($sSql);
    }

    public function getEvents($aParams)
    {
    	$sMethod = 'getAll';
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['browse']) {
            case 'id':
            	$sMethod = 'getRow';
                $sWhereClause = $this->prepare("AND `{$this->_sTable}`.`id`=? ", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;

			case 'descriptor':
            	$sMethod = 'getRow';
                $sWhereClause = $this->prepare("AND `{$this->_sTable}`.`type`=? AND `{$this->_sTable}`.`action`=? AND `{$this->_sTable}`.`object_id`=? ", $aParams['type'], $aParams['action'], $aParams['object_id']);
                $sLimitClause = "LIMIT 1";
                break;

			case 'last':
			case 'list':
		        //--- Apply filter
		    	if(isset($aParams['filter']))
					$sWhereClause .= $this->_getFilterAddon($aParams['owner_id'], $aParams['filter']);

				//--- Apply timeline
		        if(isset($aParams['timeline']) && !empty($aParams['timeline'])) {
		        	$iYear = (int)$aParams['timeline'];
		        	$sWhereClause .= $this->prepare("AND `date`<=? ", mktime(23, 59, 59, 12, 31, $iYear));
		        }

		        //--- Apply modules or handlers filter
		        $sWhereModuleFilter = '';
		        if(isset($aParams['modules']) && !empty($aParams['modules']) && is_array($aParams['modules']))
		        	$sWhereModuleFilter = "AND `type` IN (" . $this->implode_escape($aParams['modules']) . ") ";

		    	if($sWhereModuleFilter == '') {
					$aHidden = $this->_oConfig->getHandlersHidden();
					$sWhereModuleFilter = is_array($aHidden) && !empty($aHidden) ? "AND `th`.`id` NOT IN (" . $this->implode_escape($aHidden) . ") " : "";
				}

				if($sWhereModuleFilter == '')
					$sWhereClause .= $sWhereModuleFilter;

				//--- Check type
				if(!empty($aParams['owner_id']))
					switch($aParams['type']) {
						case BX_TIMELINE_TYPE_OWNER:
							$sWhereClause .= $this->prepare("AND `{$this->_sTable}`.`owner_id`=? ", $aParams['owner_id']);
							break;

						case BX_TIMELINE_TYPE_CONNECTIONS:
							bx_import('BxDolConnection');
							$oConnection = BxDolConnection::getObjectInstance($this->_oConfig->getObject('conn_subscriptions'));
		
							$aQueryParts = $oConnection->getConnectedContentAsSQLParts($this->_sPrefix . "events", 'owner_id', $aParams['owner_id']);
							$sJoinClause .= ' ' . $aQueryParts['join'];

							$iUserId = bx_get_logged_profile_id();
							$sCommonPostPrefix = $this->_oConfig->getPrefix('common_post');

							$sWhereClause .= "AND IF(SUBSTRING(`{$this->_sTable}`.`type`, 1, " . strlen($sCommonPostPrefix) . ") = '" . $sCommonPostPrefix . "', `{$this->_sTable}`.`object_id` <> " . $iUserId . ", 1)";
							break;
					}

                switch($aParams['browse']) {
                	case 'last':
                		$sMethod = 'getRow';
                		$sOrderClause = "ORDER BY `{$this->_sTable}`.`date` ASC";
                		$sLimitClause = "LIMIT 1";
                		break;
                	case 'list':
                		$sOrderClause = "ORDER BY `{$this->_sTable}`.`date` DESC";
                		$sLimitClause = isset($aParams['per_page']) ? "LIMIT " . $aParams['start'] . ", " . $aParams['per_page'] : "";
                		break;
                }
                break;
        }

        $sSql = "SELECT
                `{$this->_sTable}`.`id` AS `id`,
                `{$this->_sTable}`.`owner_id` AS `owner_id`,
                `{$this->_sTable}`.`type` AS `type`,
                `{$this->_sTable}`.`action` AS `action`,
                `{$this->_sTable}`.`object_id` AS `object_id`,
                `{$this->_sTable}`.`content` AS `content`,
                `{$this->_sTable}`.`rate` AS `rate`,
                `{$this->_sTable}`.`votes` AS `votes`,
                `{$this->_sTable}`.`comments` AS `comments`,
                `{$this->_sTable}`.`title` AS `title`,
                `{$this->_sTable}`.`description` AS `description`,
                `{$this->_sTable}`.`shares` AS `shares`,
                `{$this->_sTable}`.`date` AS `date`,
                YEAR(FROM_UNIXTIME(`{$this->_sTable}`.`date`)) AS `year`
            FROM `{$this->_sTable}`
            LEFT JOIN `{$this->_sTableHandlers}` ON `{$this->_sTable}`.`type`=`{$this->_sTableHandlers}`.`alert_unit` AND `{$this->_sTable}`.`action`=`{$this->_sTableHandlers}`.`alert_action` " . $sJoinClause . " 
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return $this->$sMethod($sSql);
    }

    public function getMaxDuration($aParams)
    {
    	$aParams['browse'] = 'last';
    	if(isset($aParams['timeline']))
    		unset($aParams['timeline']);

		$aEvent = $this->getEvents($aParams);
        if(empty($aEvent) || !is_array($aEvent))
            return 0;

		$iNowYear = date('Y', time());
		return (int)$aEvent['year'] < $iNowYear ? (int)$aEvent['year'] : 0;
    }

    //--- Share related methods ---//
    public function insertShareTrack($iEventId, $iAuthorId, $sAuthorIp, $iSharedId)
    {
    	$iNow = time();
    	$iAuthorNip = ip2long($sAuthorIp);
		$sQuery = $this->prepare("INSERT INTO `{$this->_sTableSharesTrack}` SET `event_id` = ?, `author_id` = ?, `author_nip` = ?, `shared_id` = ?, `date` = ?", $iEventId, $iAuthorId, $iAuthorNip, $iSharedId, $iNow);
		return (int)$this->query($sQuery) > 0;
    }

    public function deleteShareTrack($iEventId)
    {
    	$sQuery = $this->prepare("DELETE FROM `{$this->_sTableSharesTrack}` WHERE `event_id` = ?", $iEventId);
		return (int)$this->query($sQuery) > 0;
    }

	public function updateShareCounter($iId, $iCounter, $iIncrement = 1)
    {
    	return (int)$this->updateEvent(array('shares' => (int)$iCounter + $iIncrement), array('id' => $iId)) > 0;
    }
    
    public function getShared($sType, $sAction, $iObjectId)
    {
    	if($this->_oConfig->isSystem($sType, $sAction))
			$aParams = array('browse' => 'descriptor', 'type' => $sType, 'action' => $sAction, 'object_id' => $iObjectId);
		else 
			$aParams = array('browse' => 'id', 'value' => $iObjectId);

		return $this->getEvents($aParams);
    }

	function getSharedBy($iSharedId)
    {
    	$sQuery = $this->prepare("SELECT `author_id` FROM `{$this->_sTableSharesTrack}` WHERE `shared_id`=?", $iSharedId);
    	return $this->getColumn($sQuery);
    }

	//--- Photo uploader related methods ---//
	public function savePhoto($iEventId, $iPhId)
	{
		$sQuery = $this->prepare("INSERT INTO `" . $this->_sPrefix . "photos2events` SET `event_id`=?, `photo_id`=?", $iEventId, $iPhId);
		return (int)$this->query($sQuery) > 0;
	}

	public function deletePhotos($iEventId)
	{
		$sQuery = $this->prepare("DELETE FROM `" . $this->_sPrefix . "photos2events` WHERE `event_id` = ?", $iEventId);
		return (int)$this->query($sQuery) > 0;
	}

	public function getPhotos($iEventId, $iOffset = 0)
	{
		$sLimitAddon = '';
		if($iOffset != 0)
			$sLimitAddon = $this->prepare(" OFFSET ?", $iOffset);

		$sQuery = $this->prepare("SELECT
				 `tpe`.`photo_id` AS `id`
			FROM `" . $this->_sPrefix . "photos2events` AS `tpe` 
			LEFT JOIN `" . $this->_sPrefix . "photos` AS `tp` ON `tpe`.`photo_id` = `tp`.`id` 
			WHERE `tpe`.`event_id` = ?" . $sLimitAddon, $iEventId);

		return $this->getColumn($sQuery);
	}

	//--- Link attach related methods ---//
	public function getUnusedLinks($iUserId, $iLinkId = 0)
	{
		$sMethod = 'getAll';

		$sWhereAddon = '';
		if(!empty($iLinkId)) {
			$sMethod = 'getRow';
			$sWhereAddon = $this->prepare(" AND `tl`.`id`=?", $iLinkId);
		}

		$sQuery = $this->prepare("SELECT 
				`tl`.`id` AS `id`,
				`tl`.`profile_id` AS `profile_id`,
				`tl`.`url` AS `url`,
				`tl`.`title` AS `title`,
				`tl`.`text` AS `text`,
				`tl`.`added` AS `added`
			FROM `" . $this->_sPrefix . "links` AS `tl`
			LEFT JOIN `" . $this->_sPrefix . "links2events` AS `tle` ON `tl`.`id`=`tle`.`link_id`
			WHERE `tl`.`profile_id`=? AND ISNULL(`tle`.`event_id`)" . $sWhereAddon . "
			ORDER BY `tl`.`added` DESC", $iUserId);

		return $this->$sMethod($sQuery);
	}

	public function deleteUnusedLinks($iUserId, $iLinkId = 0)
	{
		$sWhereAddon = '';
		if(!empty($iLinkId))
			$sWhereAddon = $this->prepare(" AND `id`=?", $iLinkId);

		$sQuery = $this->prepare("DELETE FROM `" . $this->_sPrefix . "links` WHERE `profile_id`=?" . $sWhereAddon, $iUserId);
		return $this->query($sQuery);
	}

	public function saveLink($iEventId, $iLinkId)
	{
		$sQuery = $this->prepare("INSERT INTO `" . $this->_sPrefix . "links2events` SET `event_id`=?, `link_id`=?", $iEventId, $iLinkId);
		return (int)$this->query($sQuery) > 0;
	}

	public function deleteLinks($iEventId)
	{
		$sQuery = $this->prepare("DELETE FROM `tl`, `tle` USING `" . $this->_sPrefix . "links` AS `tl` LEFT JOIN `" . $this->_sPrefix . "links2events` AS `tle` ON `tl`.`id`=`tle`.`link_id` WHERE `tle`.`event_id` = ?", $iEventId);
		return (int)$this->query($sQuery) > 0;
	}

	public function getLinks($iEventId)
	{
		$sQuery = $this->prepare("SELECT 
				`tl`.`id` AS `id`,
				`tl`.`profile_id` AS `profile_id`,
				`tl`.`url` AS `url`,
				`tl`.`title` AS `title`,
				`tl`.`text` AS `text`,
				`tl`.`added` AS `added`
			FROM `" . $this->_sPrefix . "links` AS `tl`
			LEFT JOIN `" . $this->_sPrefix . "links2events` AS `tle` ON `tl`.`id`=`tle`.`link_id`
			WHERE `tle`.`event_id`=?", $iEventId);

		return $this->getAll($sQuery);
	}

	protected function _getFilterAddon($iOwnerId, $sFilter)
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

        $sSql = "UPDATE `{$this->_sTable}`
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
}

/** @} */ 
