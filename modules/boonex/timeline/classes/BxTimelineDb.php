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

bx_import('BxBaseModNotificationsDb');

class BxTimelineDb extends BxBaseModNotificationsDb
{
    protected $_sTablesShareTrack;

    protected $_aTablesMedia;
    protected $_aTablesMedia2Events;

    /*
     * Constructor.
     */
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
        $this->_sTableSharesTrack = $this->_sPrefix . 'shares_track';

        $this->_aTablesMedia = array(
        	BX_TIMELINE_MEDIA_PHOTO => $this->_sPrefix . 'photos',
        	BX_TIMELINE_MEDIA_VIDEO => $this->_sPrefix . 'videos' 
        );
        $this->_aTablesMedia2Events = array(
        	BX_TIMELINE_MEDIA_PHOTO => $this->_sPrefix . 'photos2events',
        	BX_TIMELINE_MEDIA_VIDEO => $this->_sPrefix . 'videos2events'
        );
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
    	$bSystem = $this->_oConfig->isSystem($sType, $sAction);

        if($bSystem)
            $aParams = array('browse' => 'descriptor', 'type' => $sType, 'action' => $sAction, 'object_id' => $iObjectId);
        else
            $aParams = array('browse' => 'id', 'value' => $iObjectId);

		$aShared = $this->getEvents($aParams);
		if($bSystem && (empty($aShared) || !is_array($aShared))) {
			$iId = $this->insertEvent(array(
				'owner_id' => 0,
				'type' => $sType,
				'action' => $sAction,
				'object_id' => $iObjectId,
				'object_privacy_view' => $this->_oConfig->getPrivacyViewDefault(),
				'content' => '',
				'title' => '',
				'description' => '',
				'hidden' => 1
			));

			$aShared = $this->getEvents(array('browse' => 'id', 'value' => $iId));
		}

        return $aShared;
    }

    function getSharedBy($iSharedId)
    {
        $sQuery = $this->prepare("SELECT `author_id` FROM `{$this->_sTableSharesTrack}` WHERE `shared_id`=?", $iSharedId);
        return $this->getColumn($sQuery);
    }

    //--- Photo uploader related methods ---//
    public function saveMedia($sType, $iEventId, $iItemId)
    {
    	$sTable = $this->_aTablesMedia2Events[$sType];

        $sQuery = $this->prepare("INSERT INTO `" . $sTable . "` SET `event_id`=?, `media_id`=?", $iEventId, $iItemId);
        return (int)$this->query($sQuery) > 0;
    }

    public function deleteMedia($sType, $iEventId)
    {
    	$sTable = $this->_aTablesMedia2Events[$sType];

        $sQuery = $this->prepare("DELETE FROM `" . $sTable . "` WHERE `event_id` = ?", $iEventId);
        return (int)$this->query($sQuery) > 0;
    }

    public function getMedia($sType, $iEventId, $iOffset = 0)
    {
    	$sTableMedia = $this->_aTablesMedia[$sType];
    	$sTableMedia2Events = $this->_aTablesMedia2Events[$sType];

        $sLimitAddon = '';
        if($iOffset != 0)
            $sLimitAddon = $this->prepare(" OFFSET ?", $iOffset);

        $sQuery = $this->prepare("SELECT
                 `tme`.`media_id` AS `id`
            FROM `" . $sTableMedia2Events . "` AS `tme`
            LEFT JOIN `" . $sTableMedia . "` AS `tm` ON `tme`.`media_id`=`tm`.`id`
            WHERE `tme`.`event_id`=?" . $sLimitAddon, $iEventId);

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
                $sFilterAddon = " AND `{$this->_sTable}`.`action`='' AND `{$this->_sTable}`.`object_id`='" . $iOwnerId . "' ";
                break;
            case BX_TIMELINE_FILTER_OTHER:
                $sFilterAddon = " AND `{$this->_sTable}`.`action`='' AND `{$this->_sTable}`.`object_id`<>'" . $iOwnerId . "' ";
                break;
            case BX_TIMELINE_FILTER_ALL:
            default:
                $sFilterAddon = "";
        }
        return $sFilterAddon;
    }

    protected function _getSqlPartsEvents($aParams)
    {
    	$sMethod = 'getAll';
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['browse']) {
        	case 'owner_id':
        		$sWhereClause = $this->prepare("AND `{$this->_sTable}`.`owner_id`=? ", $aParams['value']);
        		break;

        	case 'common_by_object':
        		$sCommonPostPrefix = $this->_oConfig->getPrefix('common_post');
        		$sWhereClause = $this->prepare("AND SUBSTRING(`{$this->_sTable}`.`type`, 1, " . strlen($sCommonPostPrefix) . ")='" . $sCommonPostPrefix . "' AND `{$this->_sTable}`.`object_id`=? ", $aParams['value']);
        		break;

            case 'descriptor':
                $sMethod = 'getRow';
                $sWhereClause = "";

                if(isset($aParams['type']))
                	$sWhereClause .= $this->prepare("AND `{$this->_sTable}`.`type`=? ", $aParams['type']);
				if(isset($aParams['action']))
					$sWhereClause .= $this->prepare("AND `{$this->_sTable}`.`action`=? ", $aParams['action']);
				if(isset($aParams['object_id']))
					$sWhereClause .= $this->prepare("AND `{$this->_sTable}`.`object_id`=? ", $aParams['object_id']);

				$sLimitClause = "LIMIT 1";
                break;

            case 'shared_by_descriptor':
            	$sWhereClause = "";

            	if(isset($aParams['type']))
                	$sWhereClause .= "AND `{$this->_sTable}`.`content` LIKE '%" . $this->escape($aParams['type']) . "%'";

                if(isset($aParams['action']))
                	$sWhereClause .= "AND `{$this->_sTable}`.`content` LIKE '%" . $this->escape($aParams['action']) . "%'";
                break;

            default:
            	list($sMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause) = parent::_getSqlPartsEvents($aParams);
        }

		$sSelectClause .= "YEAR(FROM_UNIXTIME(`{$this->_sTable}`.`date`)) AS `year`, ";

        return array($sMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);
    }

    protected function _getSqlPartsEventsList($aParams)
    {
    	$sJoinClause = $sWhereClause = "";

		if(isset($aParams['active']))
        	$sWhereClause .= $this->prepare("AND `{$this->_sTable}`.`active`=? ", (int)$aParams['active']);

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
        if(!empty($aParams['modules']) && is_array($aParams['modules']))
        	$sWhereModuleFilter = "AND `" . $this->_sTable . "`.`type` IN (" . $this->implode_escape($aParams['modules']) . ") ";

		if($sWhereModuleFilter == '') {
        	$aHidden = $this->_oConfig->getHandlersHidden();
			$sWhereModuleFilter = !empty($aHidden) && is_array($aHidden) ? "AND `" . $this->_sTableHandlers . "`.`id` NOT IN (" . $this->implode_escape($aHidden) . ") " : "";
		}

		if($sWhereModuleFilter != '')
			$sWhereClause .= $sWhereModuleFilter;

		//--- Check type
		switch($aParams['type']) {
			case BX_BASE_MOD_NTFS_TYPE_OWNER:
				if(empty($aParams['owner_id']))
					break;

				$sWhereClause .= $this->prepare("AND `{$this->_sTable}`.`owner_id`=? ", $aParams['owner_id']);
				break;

			case BX_BASE_MOD_NTFS_TYPE_CONNECTIONS:
				if(empty($aParams['owner_id']))
					break;

				bx_import('BxDolConnection');
				$oConnection = BxDolConnection::getObjectInstance($this->_oConfig->getObject('conn_subscriptions'));

				$aQueryParts = $oConnection->getConnectedContentAsSQLParts($this->_sPrefix . "events", 'owner_id', $aParams['owner_id']);
				$sJoinClause .= ' ' . $aQueryParts['join'];

				$iUserId = bx_get_logged_profile_id();
				$sCommonPostPrefix = $this->_oConfig->getPrefix('common_post');

				$sWhereClause .= "AND IF(SUBSTRING(`{$this->_sTable}`.`type`, 1, " . strlen($sCommonPostPrefix) . ") = '" . $sCommonPostPrefix . "', `{$this->_sTable}`.`object_id` <> " . $iUserId . ", 1) ";
				break;

			case BX_BASE_MOD_NTFS_TYPE_PUBLIC:
				$sCommonPostPrefix = $this->_oConfig->getPrefix('common_post');
				$sWhereClause .= "AND SUBSTRING(`{$this->_sTable}`.`type`, 1, " . strlen($sCommonPostPrefix) . ") <> '" . $sCommonPostPrefix . "' ";
				break;
		}

		return array($sJoinClause, $sWhereClause);
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
