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
    				if(!empty($iId))
    					$this->_oModule->onPost($iId);
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
}

/** @} */
