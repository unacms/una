<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModNotificationsResponse');

class BxTimelineResponse extends BxBaseModNotificationsResponse
{
    public function __construct()
    {
        parent::__construct();

        bx_import('BxDolModule');
        $this->_oModule = BxDolModule::getInstance('bx_timeline');
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
    	$iPrivacyView = $this->_getPrivacyView($oAlert->aExtras);
        if($iPrivacyView == BX_DOL_PG_HIDDEN)
            return;

        $aHandler = $this->_oModule->_oConfig->getHandlers($oAlert->sUnit . '_' . $oAlert->sAction);
        switch($aHandler['type']) {
            case BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT:
                $sContent = !empty($oAlert->aExtras) && is_array($oAlert->aExtras) ? serialize(bx_process_input($oAlert->aExtras)) : '';

                $iId = $this->_oModule->_oDb->insertEvent(array(
                    'owner_id' => $oAlert->iSender,
                    'type' => $oAlert->sUnit,
                    'action' => $oAlert->sAction,
                    'object_id' => $oAlert->iObject,
                    'object_privacy_view' => $iPrivacyView,
                    'content' => $sContent,
                    'title' => '',
                    'description' => ''
                ));

                if(!empty($iId))
                    $this->_oModule->onPost($iId);

                //TODO: Remove the call and the function itself if GROUPING feature won't be used.
                $this->_oModule->_oDb->updateSimilarObject($iId, $oAlert);
                break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_UPDATE:
                $sContent = !empty($oAlert->aExtras) && is_array($oAlert->aExtras) ? serialize(bx_process_input($oAlert->aExtras)) : '';

                $this->_oModule->_oDb->updateEvent(array('object_privacy_view' => $iPrivacyView, 'content' => $sContent), array('type' => $oAlert->sUnit, 'object_id' => $oAlert->iObject));
                break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_DELETE:
            	if($oAlert->sUnit == 'profile' && $oAlert->sAction == 'delete') {
            		$aEvents = $this->_oModule->_oDb->getEvents(array('browse' => 'owner_id', 'value' => $oAlert->iObject));
            		foreach($aEvents as $aEvent)
            			$this->_oModule->deleteEvent($aEvent);

            		if(isset($oAlert->aExtras['delete_with_content']) && $oAlert->aExtras['delete_with_content']) {
						$aEvents = $this->_oModule->_oDb->getEvents(array('browse' => 'common_by_object', 'value' => $oAlert->iObject));
						foreach($aEvents as $aEvent)
	            			$this->_oModule->deleteEvent($aEvent);
            		}
            		break;
            	}

            	$aEvent = $this->_oModule->_oDb->getEvents(array('browse' => 'descriptor', 'type' => $oAlert->sUnit, 'object_id' => $oAlert->iObject));
            	$this->_oModule->deleteEvent($aEvent);
                break;
        }
    }
}

/** @} */
