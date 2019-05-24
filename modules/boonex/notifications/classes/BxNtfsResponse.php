<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Notifications Notifications
 * @ingroup     UnaModules
 *
 * @{
 */

class BxNtfsResponse extends BxBaseModNotificationsResponse
{
    public function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance('bx_notifications');
    }

    /**
     * Overwritten method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
    	$iObjectPrivacyView = $this->_getObjectPrivacyView($oAlert->aExtras);
        if($iObjectPrivacyView == BX_DOL_PG_HIDDEN)
            return;

        $aHandler = $this->_oModule->_oConfig->getHandlers($oAlert->sUnit . '_' . $oAlert->sAction);
        if(empty($aHandler) || !is_array($aHandler))
            return;

        $iSilentMode = $this->_getSilentMode($oAlert->aExtras);
        if($iSilentMode == BX_NTFS_SLTMODE_ABSOLUTE)
            return;

        switch($aHandler['type']) {
            case BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT:
            	$sMethod = 'getInsertData' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
            	if(!method_exists($this, $sMethod))
                    $sMethod = 'getInsertData';

                $aDataItems = $this->$sMethod($oAlert, $aHandler);
                foreach($aDataItems as $aDataItem) {
                    $iId = $this->_oModule->_oDb->insertEvent($aDataItem);
                    if(!empty($iId)) {
                        if($iSilentMode != BX_NTFS_SLTMODE_SITE)
                            $this->sendNotifications($iId, $oAlert, $aHandler);

                        $this->_oModule->onPost($iId);
                    }
                }
                break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_UPDATE:
                $this->_oModule->_oDb->updateEvent(array('object_privacy_view' => $iObjectPrivacyView), array('type' => $oAlert->sUnit, 'object_id' => $oAlert->iObject));
                break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_DELETE:
                if($oAlert->sUnit == 'profile' && $oAlert->sAction == 'delete') {
                    $this->_oModule->_oDb->deleteEvent(array('owner_id' => $oAlert->iObject));

                    $this->_oModule->_oDb->deleteEvent(array('action' => 'connection_added', 'object_id' => $oAlert->iObject));
                    break;
            	}

            	$sMethod = 'getDeleteData' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
            	if(!method_exists($this, $sMethod))
                    $sMethod = 'getDeleteData';

                $aDataItems = $this->$sMethod($oAlert, $aHandler);
                foreach($aDataItems as $aDataItem)
                    $this->_oModule->_oDb->deleteEvent($aDataItem);
                break;
        }
    }

    protected function getInsertData(&$oAlert, &$aHandler)
    {
        $iOwnerId = $oAlert->iSender;
        $iObjectPrivacyView = $this->_getObjectPrivacyView($oAlert->aExtras);

        if($iObjectPrivacyView < 0)
            $iOwnerId = abs($iObjectPrivacyView);
        
        $mixedSubobjectId = $this->_getSubObjectId($oAlert->aExtras);
        if(!is_array($mixedSubobjectId))
            $mixedSubobjectId = array($mixedSubobjectId);

        $aResult = array();
        foreach($mixedSubobjectId as $iSubobjectId)
            $aResult[] = array(
                'owner_id' => $iOwnerId,
                'type' => $oAlert->sUnit,
                'action' => $oAlert->sAction,
                'object_id' => $oAlert->iObject,
                'object_owner_id' => $this->_getObjectOwnerId($oAlert->aExtras),
                'object_privacy_view' => $iObjectPrivacyView,
                'subobject_id' => $iSubobjectId,
                'content' => '',
                'allow_view_event_to' => $this->_oModule->_oConfig->getPrivacyViewDefault('event'),
                'processed' => 0
    	    );

    	return $aResult;
    }

    protected function getDeleteData(&$oAlert, &$aHandler)
    {
        $aHandlers = $this->_oModule->_oDb->getHandlers(array('type' => 'by_group_key_type', 'group' => $aHandler['group']));

    	return array(
    	    array(
            	'type' => $oAlert->sUnit, 
            	'action' => $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'], 
            	'object_id' => $oAlert->iObject,
            	'subobject_id' => $this->_getSubObjectId($oAlert->aExtras)
            )
        );
    }

    protected function getDeleteDataBxTimelineDelete(&$oAlert, &$aHandler)
    {
        $aHandlers = $this->_oModule->_oDb->getHandlers(array('type' => 'by_group_key_type', 'group' => $aHandler['group']));

    	return array(
    	    array(
            	'type' => $oAlert->sUnit, 
            	'action' => $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'], 
            	'object_id' => $oAlert->iObject,
            	'subobject_id' => $this->_getSubObjectId($oAlert->aExtras)
            ),
            array(
            	'action' => 'timeline_post_common', 
            	'subobject_id' => $oAlert->iObject
            )
        );
    }

	/**
     * Custom insert data getter for sys_profiles_friends -> connection_added and connection_removed alerts. 
     */
    protected function getInsertDataSysProfilesFriendsConnectionAdded(&$oAlert, &$aHandler)
    {
        $iObjectPrivacyView = $this->_getObjectPrivacyView($oAlert->aExtras);
        $iPrivacyView = $this->_oModule->_oConfig->getPrivacyViewDefault('event');

        $aContent = array(
            'request' => empty($oAlert->aExtras['mutual']) ? 1 : 0
        );

    	return array(
    	    array(
                'owner_id' => $oAlert->aExtras['initiator'],
                'type' => $oAlert->sUnit,
                'action' => $oAlert->sAction,
                'object_id' => $oAlert->aExtras['content'],
                'object_owner_id' => $oAlert->aExtras['content'],
                'object_privacy_view' => $iObjectPrivacyView,
                'subobject_id' => 0,
                'content' => serialize($aContent),
                'allow_view_event_to' => $iPrivacyView,
                'processed' => 0
    	    )
        );
    }
    
    protected function getDeleteDataSysProfilesFriendsConnectionRemoved(&$oAlert, &$aHandler)
    {
        $aHandlers = $this->_oModule->_oDb->getHandlers(array('type' => 'by_group_key_type', 'group' => $aHandler['group']));

        $sAction = $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'];
    	return array(
    	    array(
    	        'owner_id' => $oAlert->aExtras['initiator'],
            	'type' => $oAlert->sUnit, 
            	'action' => $sAction, 
            	'object_id' => $oAlert->aExtras['content']
            ),
            array(
            	'owner_id' => $oAlert->aExtras['content'],
            	'type' => $oAlert->sUnit, 
            	'action' => $sAction, 
            	'object_id' => $oAlert->aExtras['initiator']
            )
        );
    }

    /**
     * Custom insert data getter for sys_profiles_subscriptions -> connection_added and connection_removed alerts. 
     */
    protected function getInsertDataSysProfilesSubscriptionsConnectionAdded(&$oAlert, &$aHandler)
    {
    	return array(
    	    array(
                'owner_id' => $oAlert->aExtras['initiator'],
                'type' => $oAlert->sUnit,
                'action' => $oAlert->sAction,
                'object_id' => $oAlert->aExtras['content'],
                'object_owner_id' => $oAlert->aExtras['content'],
                'object_privacy_view' => $this->_getObjectPrivacyView($oAlert->aExtras),
                'subobject_id' => 0,
                'content' => '',
                'allow_view_event_to' => $this->_oModule->_oConfig->getPrivacyViewDefault('event'),
                'processed' => 0
    	    )
        );
    }

    protected function getDeleteDataSysProfilesSubscriptionsConnectionRemoved(&$oAlert, &$aHandler)
    {
        $aHandlers = $this->_oModule->_oDb->getHandlers(array('type' => 'by_group_key_type', 'group' => $aHandler['group']));

    	return array(
    	    array(
    	        'owner_id' => $oAlert->aExtras['initiator'],
            	'type' => $oAlert->sUnit, 
            	'action' => $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'], 
            	'object_id' => $oAlert->aExtras['content']
            )
        );
    }

    protected function sendNotifications($iId, &$oAlert, &$aHandler)
    {
        $aDeliveryTypes = array();

        $iSilentMode = $this->_getSilentMode($oAlert->aExtras);
        switch($iSilentMode) {
            case BX_NTFS_SLTMODE_ABSOLUTE:
            case BX_NTFS_SLTMODE_SITE:
                return;

            case BX_NTFS_SLTMODE_SITE_EMAIL:
                $aDeliveryTypes[] = BX_BASE_MOD_NTFS_DTYPE_EMAIL;
                break;

            case BX_NTFS_SLTMODE_SITE_PUSH:
                $aDeliveryTypes[] = BX_BASE_MOD_NTFS_DTYPE_PUSH;
                break;

            default:
                $aDeliveryTypes = array(BX_BASE_MOD_NTFS_DTYPE_EMAIL, BX_BASE_MOD_NTFS_DTYPE_PUSH);
        }

        $aEvent = $this->_oModule->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
        if(empty($aEvent) || !is_array($aEvent))
            return;

        $aSendUsing = array();
        foreach($aDeliveryTypes as $sDeliveryType) {
            $aHidden = $this->_oModule->_oConfig->getHandlersHidden($sDeliveryType);
            if(in_array($aHandler['id'], $aHidden))
                continue;

            $sMethodPostfix = bx_gen_method_name($sDeliveryType);
            $sMethodGet = 'getNotification' . $sMethodPostfix;
            $sMethodSend = 'sendNotification' . $sMethodPostfix;
            if(!$this->_oModule->_oTemplate->isMethodExists($sMethodGet) || !method_exists($this->_oModule, $sMethodSend))
                continue;

            $mixedNotification = $this->_oModule->_oTemplate->$sMethodGet($aEvent);
            if($mixedNotification === false)
                continue;

            $aSendUsing[$sDeliveryType] = array(
            	'method' => $sMethodSend,
                'notification' => $mixedNotification
            );
        }

        if(empty($aSendUsing) || !is_array($aSendUsing))
            return;

        $iOwner = (int)$aEvent['owner_id'];
        $aRecipients = array();

        //--- Get recipients: Subscribers.
        $oConnection = BxDolConnection::getObjectInstance($this->_oModule->_oConfig->getObject('conn_subscriptions'));
        $aSubscribers = $oConnection->getConnectedInitiators($iOwner);
        if(!empty($aSubscribers) && is_array($aSubscribers)) {
            $oOwner = BxDolProfile::getInstance($iOwner);
            $sSettingType = bx_srv($oOwner->getModule(), 'act_as_profile') ? BX_NTFS_STYPE_FOLLOW_MEMBER : BX_NTFS_STYPE_FOLLOW_CONTEXT;

            foreach($aSubscribers as $iSubscriber) 
                $this->_addRecipient($iSubscriber, $sSettingType, $aRecipients);
        }

        //--- Get recipients: Content owner.
        $iObjectOwner = (int)$aEvent['object_owner_id'];
        if($iOwner != $iObjectOwner)
            $this->_addRecipient($iObjectOwner, BX_NTFS_STYPE_PERSONAL, $aRecipients);

        $bDeliveryTimeout = $this->_oModule->_oConfig->getDeliveryTimeout() > 0;

        //--- Check recipients and send notifications.
        $oPrivacyInt = BxDolPrivacy::getObjectInstance($this->_oModule->_oConfig->getObject('privacy_view'));
        $oPrivacyExt = $this->_oModule->_oConfig->getPrivacyObject($aEvent['type'] . '_' . $aEvent['action']);
        foreach($aRecipients as $iRecipient => $aSettingTypes) {
            $oProfile = BxDolProfile::getInstance($iRecipient);
            if(!$oProfile)
                continue;

            if(!bx_srv($oProfile->getModule(), 'act_as_profile'))
                continue;

            if($oPrivacyExt !== false && !$oPrivacyExt->check($aEvent['id'], $iRecipient)) 
                continue;

            if($oPrivacyInt !== false && !$oPrivacyInt->check($aEvent['id'], $iRecipient))
                continue;

            foreach($aSendUsing as $sDeliveryType => $aDeliveryType)
                foreach($aSettingTypes as $sSettingType) {
                    $aSetting = $this->_oModule->_oDb->getSetting(array('by' => 'tsu_allowed', 'handler_id' => $aHandler['id'], 'delivery' => $sDeliveryType, 'type' => $sSettingType, 'user_id' => $iRecipient));
                    if(empty($aSetting) || !is_array($aSetting))
                        continue;

                    if((int)$aSetting['active_adm'] == 0 || (int)$aSetting['active_pnl'] == 0)
                        continue;

                    /**
                     * 'break' is essential in the next two conditions to avoid 
                     * duplicate sending to the same recipient.
                     */
                    if($bDeliveryTimeout && $this->_oModule->_oDb->queueAdd(array(
                        'profile_id' => $iRecipient, 
                        'event_id' => $aEvent['id'], 
                        'delivery' => $sDeliveryType,
                        'content' => serialize($aDeliveryType['notification']),
                        'date' => time()
                    )) !== false)
                        break;

                    if($this->_oModule->{$aDeliveryType['method']}($oProfile, $aDeliveryType['notification']) !== false)
                        break;
                }
        }
    }

    protected function _getObjectOwnerId($aExtras)
    {
        $iResult = parent::_getObjectOwnerId($aExtras);
        if(!empty($iResult))
            return $iResult;

        if(isset($aExtras['meta']))
            return (int)$aExtras['meta'];

        return 0;
    }

    protected function _getSilentMode($aExtras)
    {
        if(isset($aExtras['silent_mode']))
            return (int)$aExtras['silent_mode'];

        return BX_NTFS_SLTMODE_DISABLED;
    }

    protected function _addRecipient($iUser, $sSettingType, &$aRecipients)
    {
        if(!isset($aRecipients[$iUser]))
            $aRecipients[$iUser] = array();

        $aRecipients[$iUser][] = $sSettingType;
    }
}

/** @} */
