<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineResponse extends BxBaseModNotificationsResponse
{
    public function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance('bx_timeline');
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
        if(method_exists($this, $sMethod))
            return $this->$sMethod($oAlert);

        $iObjectAuthorId = $this->_getObjectOwnerId($oAlert->aExtras);
    	$iObjectPrivacyView = $this->_getObjectPrivacyView($oAlert->aExtras);
        if($iObjectPrivacyView == BX_DOL_PG_HIDDEN)
            return;

        $aHandler = $this->_oModule->_oConfig->getHandlers($oAlert->sUnit . '_' . $oAlert->sAction);
        if(empty($aHandler) || !is_array($aHandler))
            return;

        $iSilentMode = $this->_oModule->getSilentMode($oAlert->aExtras);
        if(in_array($iSilentMode, array(BX_BASE_MOD_NTFS_SLTMODE_ABSOLUTE, BX_TIMELINE_SLTMODE_ABSOLUTE)))
            return;

        switch($aHandler['type']) {
            case BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT:
                $iOwnerId = abs($oAlert->iSender);
                if($iObjectPrivacyView < 0)
                    $iOwnerId = abs($iObjectPrivacyView);

                $sContent = '';
                if(!empty($oAlert->aExtras) && is_array($oAlert->aExtras))
                    $sContent = serialize(bx_process_input($oAlert->aExtras));

                $aEvent = array(
                    'owner_id' => $iOwnerId,
                    'type' => $oAlert->sUnit,
                    'action' => $oAlert->sAction,
                    'object_id' => $oAlert->iObject,
                    'object_owner_id' => $iObjectAuthorId,
                    'object_privacy_view' => $iObjectPrivacyView,
                    'content' => $sContent,
                    'title' => '',
                    'description' => ''
                );

                $sMethod = '_prepareEvent' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);
                if(method_exists($this, $sMethod))
                    $this->$sMethod($oAlert, $aEvent);

                $iId = $this->_oModule->_oDb->insertEvent($aEvent);
                if(!empty($iId))
                    $this->_oModule->onPost($iId);
                break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_UPDATE:
                $aHandlers = $this->_oModule->_oDb->getHandlers(array('type' => 'by_group_key_type', 'group' => $aHandler['group']));
                
                $aEvent = $this->_oModule->_oDb->getEvents(array(
                    'browse' => 'descriptor', 
                    'type' => $oAlert->sUnit, 
                    'action' => $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'],
                    'object_id' => $oAlert->iObject
                ));
                if(empty($aEvent) || !is_array($aEvent))
                    break;

                $aParamsSet = array(
                    'content' => !empty($oAlert->aExtras) && is_array($oAlert->aExtras) ? serialize(bx_process_input($oAlert->aExtras)) : ''
                );

                if($iObjectPrivacyView > 0 && !empty($iObjectAuthorId) && $iObjectAuthorId == $oAlert->iSender)
                    $aParamsSet = array_merge($aParamsSet, array(
                        'owner_id' => $oAlert->iSender,
                        'object_privacy_view' => $iObjectPrivacyView
                    ));
                else if($iObjectPrivacyView < 0)
                    $aParamsSet = array_merge($aParamsSet, array(
                        'owner_id' => abs($iObjectPrivacyView),
                        'object_privacy_view' => $iObjectPrivacyView 
                    ));

                $this->_oModule->_oDb->updateEvent($aParamsSet, array('id' => $aEvent[$CNF['FIELD_ID']]));

                //--- Delete feed cached
                $this->_oModule->_oDb->deleteCache(array('context_id' => 0)); //--- Delete cache for Public feed
                $this->_oModule->_oDb->deleteCache(array('context_id' => $aEvent[$CNF['FIELD_OWNER_ID']])); //--- Delete cache for old context
                if(isset($aParamsSet['owner_id']))
                    $this->_oModule->_oDb->deleteCache(array('context_id' => $aParamsSet['owner_id'])); //--- Delete cache for new context

                //--- Delete item cache.
                $oCacheItem = $this->_oModule->getCacheItemObject();
                $oCacheItem->delData($this->_oModule->_oConfig->getCacheItemKey($aEvent['id']));

                $aReposts = $this->_oModule->_oDb->getReposts($aEvent[$CNF['FIELD_ID']]);
                foreach($aReposts as $aRepost) {
                    //--- Delete cache for reposter context
                    $this->_oModule->_oDb->deleteCache(array('context_id' => $aRepost['author_id']));

                    //--- Delete item cache for repost.
                    $oCacheItem->delData($this->_oModule->_oConfig->getCacheItemKey($aRepost['event_id']));
                }
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

                    //--- Delete cached
                    $this->_oModule->_oDb->deleteCache(array('context_id' => $oAlert->iObject));
                    $this->_oModule->_oDb->deleteCache(array('profile_id' => $oAlert->iObject));
                    break;
                }

            	$aHandlers = $this->_oModule->_oDb->getHandlers(array('type' => 'by_group_key_type', 'group' => $aHandler['group']));

            	$aEvent = $this->_oModule->_oDb->getEvents(array(
                    'browse' => 'descriptor', 
                    'type' => $oAlert->sUnit,
                    'action' => $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'], 
                    'object_id' => $oAlert->iObject
            	));
                if(empty($aEvent) || !is_array($aEvent))
                    break;

            	$this->_oModule->deleteEvent($aEvent);
                break;
        }
    }

    protected function _prepareEventCommentAdded($oAlert, &$aEvent)
    {
        $aEvent = array_merge($aEvent, array(
            'object_id' => $oAlert->aExtras['comment_uniq_id'],
            'object_owner_id' => $oAlert->aExtras['comment_author_id'],
        ));
    }

    protected function _processSystemClearCache($oAlert)
    {
        if(!in_array($oAlert->aExtras['type'], array('all', 'custom')))
            return;

        $this->_clearCache();
    }

    protected function _processAccountConfirm($oAlert)
    {
        $this->_clearCache();
    }

    protected function _processAccountUnconfirm($oAlert)
    {
        $this->_clearCache();
    }

    protected function _processProfileApprove($oAlert)
    {
        $this->_clearCache();
    }

    protected function _processProfileDisapprove($oAlert)
    {
        $this->_clearCache();
    }

    protected function _processProfileActivate($oAlert)
    {
        $this->_clearCache();
    }

    protected function _processProfileSuspend($oAlert)
    {
        $this->_clearCache();
    }

    protected function _clearCache()
    {
        //--- Clear item cache.
        $this->_oModule->getCacheItemObject()->removeAllByPrefix($this->_oModule->_oConfig->getPrefix('cache_item'));

        //--- Clear feed cache.
        $this->_oModule->_oDb->clearCache();
    }

    protected function _processBxTimelineVideosMp4Transcoded($oAlert)
    {
        $this->_onVideoTranscoded($oAlert->iObject, $oAlert->aExtras['ret']);
    }

    protected function _onVideoTranscoded($iMediaId, $bResult, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($CNF['FIELD_STATUS']))
            return;

        $aMedia = $this->_oModule->_oDb->getMediaById($CNF['FIELD_VIDEO'], $iMediaId);
        if(empty($aMedia) || !is_array($aMedia))
            return;

        $iContentId = (int)$aMedia['event_id'];
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return;

        if(!isset($aContentInfo[$CNF['FIELD_STATUS']]) || $aContentInfo[$CNF['FIELD_STATUS']] != 'awaiting')
            return;

        $iNow = time();
        $bNotify = $iNow - $aContentInfo[$CNF['FIELD_ADDED']] > $this->_oModule->_oConfig->getDpnTime();
        $iSystemBotProfileId = (int)getParam('sys_profile_bot');
        $iAuthorProfileId = $aContentInfo[$CNF['FIELD_' . ((int)$aContentInfo[$CNF['FIELD_SYSTEM']] == 0 ? 'OBJECT_ID' : 'OWNER_ID')]];

        if(!$bResult) {
            if((int)$this->_oModule->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => 'failed'), array($CNF['FIELD_ID'] => $iContentId)) > 0) {
                $this->_oModule->onFailed($iContentId);

                if($bNotify)
                    bx_alert($this->_oModule->getName(), 'publish_failed', $aContentInfo[$CNF['FIELD_ID']], $iSystemBotProfileId, array(
                        'object_author_id' => $iAuthorProfileId,
                        'privacy_view' => BX_DOL_PG_ALL
                    ));
            }

            return;
        }

        if(isset($CNF['FIELD_PUBLISHED']) && isset($aContentInfo[$CNF['FIELD_PUBLISHED']]) && $aContentInfo[$CNF['FIELD_PUBLISHED']] > $iNow)
            return;

        $oTranscoder = BxDolTranscoder::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']);
        $aMediasToCheck = $this->_oModule->_oDb->getMedia($CNF['FIELD_VIDEO'], $iContentId);
        foreach($aMediasToCheck as $iMediaToCheck)
            if(!$oTranscoder->isFileReady($iMediaToCheck))
                return;

        if(!$this->_oModule->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => 'active'), array($CNF['FIELD_ID'] => $iContentId)))
            return;

        $this->_oModule->onPublished($iContentId);

        if($bNotify)
            bx_alert($this->_oModule->getName(), 'publish_succeeded', $aContentInfo[$CNF['FIELD_ID']], $iSystemBotProfileId, array(
                'object_author_id' => $iAuthorProfileId,
                'privacy_view' => BX_DOL_PG_ALL
            ));
    }
}

/** @} */
