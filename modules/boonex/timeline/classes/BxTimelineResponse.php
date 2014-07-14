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
        if(is_array($oAlert->aExtras) && isset($oAlert->aExtras['from_wall']) && (int)$oAlert->aExtras['from_wall'] == 1)
            $this->_responseInner($oAlert);
        else
            $this->_responseOuter($oAlert);
    }

    protected function _responseOuter($oAlert)
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
                $this->_oModule->_oDb->deleteEvent(array('type' => $oAlert->sUnit, 'object_id' => $oAlert->iObject));

        		$aEvents = $this->_oModule->_oDb->getEvents(array('browse' => 'shared_by_descriptor', 'type' => $oAlert->sUnit));
				foreach($aEvents as $aEvent) {
					$aContent = unserialize($aEvent['content']);
					if(isset($aContent['type']) && $aContent['type'] == $oAlert->sUnit && isset($aContent['object_id']) && (int)$aContent['object_id'] == $oAlert->iObject)
						$this->_oModule->_oDb->deleteEvent(array('id' => (int)$aEvent['id']));
				}
                break;
        }
    }

    //TODO: Remove if it's not used.
    protected function _responseInner($oAlert)
    {
        $this->_oModule->_iOwnerId = (int)$oAlert->aExtras['owner_id'];
        $sMedia = strtolower(str_replace('bx_', '', $oAlert->sUnit));
        $aMediaInfo = $this->_oModule->_oTemplate->getCommonMedia($sMedia, $oAlert->iObject);

        $iId = $this->_oModule->_oDb->insertEvent(array(
            'owner_id' => $this->_oModule->_iOwnerId,
            'type' => $this->_oModule->_oConfig->getPrefix('common_post') . $sMedia,
            'action' => '',
            'object_id' => $this->_oModule->_getUserId(),
            'object_privacy_view' => $this->_getPrivacyView($oAlert->aExtras),
            'content' => serialize(array(
                'type' => $sMedia,
                'id' => $oAlert->iObject,
            )),
            'title' => bx_process_input($aMediaInfo['title']),
            'description' => bx_process_input($aMediaInfo['description'])
        ));

        if(!empty($iId))
            $this->_oModule->onPost($iId);

        echo $this->_oModule->_oTemplate->_wrapInTagJsCode("parent." . $this->_oModule->_sJsPostObject . "._getPost(null, " . $iId . ");");
    }
}

/** @} */
