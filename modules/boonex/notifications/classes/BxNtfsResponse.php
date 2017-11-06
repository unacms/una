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
        switch($aHandler['type']) {
            case BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT:
            	$sMethod = 'getInsertData' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
            	if(!method_exists($this, $sMethod))
            		$sMethod = 'getInsertData';

                $aDataItems = $this->$sMethod($oAlert, $aHandler);
                foreach($aDataItems as $aDataItem) {
                    $iId = $this->_oModule->_oDb->insertEvent($aDataItem);
                    if(!empty($iId)) {
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
    	return array(
    	    array(
    			'owner_id' => $oAlert->iSender,
    			'type' => $oAlert->sUnit,
    			'action' => $oAlert->sAction,
    			'object_id' => $oAlert->iObject,
    			'object_owner_id' => $this->_getObjectOwnerId($oAlert->aExtras),
    			'object_privacy_view' => $this->_getObjectPrivacyView($oAlert->aExtras),
    			'subobject_id' => $this->_getSubObjectId($oAlert->aExtras),
    			'content' => '',
        		'allow_view_event_to' => $this->_oModule->_oConfig->getPrivacyViewDefault('event'),
    			'processed' => 0
    	    )
		);
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

	/**
     * Custom insert data getter for sys_profiles_friends -> connection_added and connection_removed alerts. 
     */
    protected function getInsertDataSysProfilesFriendsConnectionAdded(&$oAlert, &$aHandler)
    {
        if(empty($oAlert->aExtras['mutual']))
            return array();

        $iObjectPrivacyView = $this->_getObjectPrivacyView($oAlert->aExtras);
        $iPrivacyView = $this->_oModule->_oConfig->getPrivacyViewDefault('event');

    	return array(
    	    array(
    			'owner_id' => $oAlert->aExtras['initiator'],
    			'type' => $oAlert->sUnit,
    			'action' => $oAlert->sAction,
    			'object_id' => $oAlert->aExtras['content'],
    			'object_owner_id' => $oAlert->aExtras['content'],
    			'object_privacy_view' => $iObjectPrivacyView,
    			'subobject_id' => 0,
    			'content' => '',
        		'allow_view_event_to' => $iPrivacyView,
    			'processed' => 0
    	    ),
    	    array(
    			'owner_id' => $oAlert->aExtras['content'],
    			'type' => $oAlert->sUnit,
    			'action' => $oAlert->sAction,
    			'object_id' => $oAlert->aExtras['initiator'],
    			'object_owner_id' => $oAlert->aExtras['initiator'],
    			'object_privacy_view' => $iObjectPrivacyView,
    			'subobject_id' => 0,
    			'content' => '',
        		'allow_view_event_to' => $iPrivacyView,
    			'processed' => 0
    	    ),
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
        $aEvent = $this->_oModule->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
        if(empty($aEvent) || !is_array($aEvent))
            return;

        $aTypes = array(BX_NTFS_DTYPE_EMAIL, BX_NTFS_DTYPE_PUSH);
        $aTypesToSend = array();
        foreach($aTypes as $sType) {
            $aHidden = $this->_oModule->_oConfig->getHandlersHidden($sType);
            if(in_array($aHandler['id'], $aHidden))
                continue;

            $sMethodPostfix = bx_gen_method_name($sType);
            $sMethodGet = 'getNotification' . $sMethodPostfix;
            $sMethodSend = 'sendNotification' . $sMethodPostfix;
            if(!method_exists($this->_oModule->_oTemplate, $sMethodGet) || !method_exists($this, $sMethodSend))
                continue;

            //--- Get event.
            $aTypesToSend[$sType] = array(
            	'method' => $sMethodSend,
                'content' => $this->_oModule->_oTemplate->$sMethodGet($aEvent)
            );
        }

        if(empty($aTypesToSend) || !is_array($aTypesToSend))
            return;

        //--- Get subscribers.
        $oConnection = BxDolConnection::getObjectInstance($this->_oModule->_oConfig->getObject('conn_subscriptions'));
        $aRecipients = $oConnection->getConnectedInitiators($aEvent['owner_id']);

        //--- Get content owner.
        if((int)$aEvent['owner_id'] != (int)$aEvent['object_owner_id'] && !in_array($aEvent['object_owner_id'], $aRecipients))
            $aRecipients[] = $aEvent['object_owner_id'];

        $oPrivacyInt = BxDolPrivacy::getObjectInstance($this->_oModule->_oConfig->getObject('privacy_view'));
        $oPrivacyExt = $this->_oModule->_oConfig->getPrivacyObject($aEvent['type'] . '_' . $aEvent['action']);
        foreach($aRecipients as $iRecipient) {
            $oProfile = BxDolProfile::getInstance($iRecipient);
            if(!$oProfile)
                continue;

            if(!bx_srv($oProfile->getModule(), 'act_as_profile'))
                continue;

            if($oPrivacyExt !== false && !$oPrivacyExt->check($aEvent['id'], $iRecipient)) 
    		    continue;

            if($oPrivacyInt !== false && !$oPrivacyInt->check($aEvent['id'], $iRecipient))
                continue;

            foreach($aTypesToSend as $aType)
                $this->{$aType['method']}($oProfile, $aType['content']);
        }
    }

    protected function sendNotificationEmail($oProfile, $sContent)
    {
        sendMailTemplate('bx_notifications_new_event', $oProfile->getAccountId(), $oProfile->id(), array(
            'content' => $sContent
        ));
    }

    protected function sendNotificationPush($oProfile, $aContent)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sAppId = getParam('sys_push_app_id');
        $sRestApi = getParam('sys_push_rest_api');
        if(empty($sAppId) || empty($sRestApi))
            return;

		$sLanguage = BxDolStudioLanguagesUtils::getInstance()->getCurrentLangName(false);

		$aFields = array(
			'app_id' => $sAppId,
			'filters' => array(
		        array("field" => "tag", "key" => "user", "relation" => "=", "value" => $oProfile->id())
            ),
			'contents' => array(
    			 $sLanguage => $aContent['message']
    		),
			'headings' => array(
				 $sLanguage => _t('_bx_ntfs_push_new_event_subject', getParam('site_title'))
			),
			'url' => $aContent['url'],
			'chrome_web_icon' => $aContent['icon']
		);

		$oChannel = curl_init();
		curl_setopt($oChannel, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($oChannel, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8',
			'Authorization: Basic ' . $sRestApi
		));
		curl_setopt($oChannel, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($oChannel, CURLOPT_HEADER, false);
		curl_setopt($oChannel, CURLOPT_POST, true);
		curl_setopt($oChannel, CURLOPT_POSTFIELDS, json_encode($aFields));
		curl_setopt($oChannel, CURLOPT_SSL_VERIFYPEER, false);

		$sResult = curl_exec($oChannel);
		curl_close($oChannel);
    }
}

/** @} */
