<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notifications Notifications
 * @ingroup     TridentModules
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

                $iId = $this->_oModule->_oDb->insertEvent($this->$sMethod($oAlert));
 
				if(!empty($iId))
					$this->_oModule->onPost($iId);

				break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_UPDATE:
                $this->_oModule->_oDb->updateEvent(array('object_privacy_view' => $iObjectPrivacyView), array('type' => $oAlert->sUnit, 'object_id' => $oAlert->iObject));
                break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_DELETE:
        		if($oAlert->sUnit == 'profile' && $oAlert->sAction == 'delete') {
        			$this->_oModule->_oDb->deleteEvent(array('owner_id' => $oAlert->iObject));
					break;
            	}

            	$aHandlers = $this->_oModule->_oDb->getHandlers(array('type' => 'by_group_key_type', 'group' => $aHandler['group']));
                $this->_oModule->_oDb->deleteEvent(array(
                	'type' => $oAlert->sUnit, 
                	'action' => $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'], 
                	'object_id' => $oAlert->iObject,
                	'subobject_id' => $this->_getSubObjectId($oAlert->aExtras)
                ));
                break;
        }
    }

    protected function getInsertData(&$oAlert)
    {
    	return array(
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
		);
    }

    /**
     * Custom insert data getter for sys_profiles_subscriptions -> connection_added alert. 
     */
    protected function getInsertDataSysProfilesSubscriptionsConnectionAdded(&$oAlert)
    {
    	return array(
			'owner_id' => $oAlert->iSender,
			'type' => $oAlert->sUnit,
			'action' => $oAlert->sAction,
			'object_id' => $oAlert->aExtras['content'],
			'object_owner_id' => $oAlert->aExtras['content'],
			'object_privacy_view' => $this->_getObjectPrivacyView($oAlert->aExtras),
			'subobject_id' => 0,
			'content' => '',
    		'allow_view_event_to' => $this->_oModule->_oConfig->getPrivacyViewDefault('event'),
			'processed' => 0
		);
    }
}

/** @} */
