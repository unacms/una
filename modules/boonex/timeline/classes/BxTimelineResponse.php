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
        $this->_sModule = 'bx_timeline';

        parent::__construct();
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
        bx_alert($this->_sModule, 'before_register_alert', 0, 0, array(
            'unit' => $oAlert->sUnit,
            'action' => $oAlert->sAction,
            'alert' => &$oAlert,
        ));
        
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

                $sSource = '';
                if(!empty($oAlert->aExtras['timeline_group']))
                    $sSource = $oAlert->aExtras['timeline_group']['by'];
                else
                    $sSource = $oAlert->sUnit . '_' . abs($iObjectAuthorId) . '_' . $oAlert->iObject;

                $iDate = time();
                $aEvent = [
                    'owner_id' => $iOwnerId,
                    'type' => $oAlert->sUnit,
                    'action' => $oAlert->sAction,
                    'object_id' => $oAlert->iObject,
                    'object_owner_id' => $iObjectAuthorId,
                    'object_privacy_view' => $iObjectPrivacyView,
                    'object_cf' => $this->_getObjectCf($oAlert->aExtras),
                    'content' => $sContent,
                    'source' => $sSource,
                    'title' => '',
                    'description' => '',
                    'date' => $iDate,
                    'reacted' => $iDate,
                ];

                $sMethod = '_prepareEvent' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);
                if(method_exists($this, $sMethod))
                    $this->$sMethod($oAlert, $aEvent);

                $sMethod = 'get_timeline_insert_data';
                if(bx_is_srv($oAlert->sUnit, $sMethod)) 
                    $aEvent = bx_srv($oAlert->sUnit, $sMethod, [$oAlert, $aHandler, $aEvent]);

                bx_alert($this->_sModule, 'before_insert_data', 0, 0, [
                    'unit' => $oAlert->sUnit,
                    'action' => $oAlert->sAction,
                    'alert' => $oAlert,
                    'data' => &$aEvent
                ]);

                $iId = $this->_oModule->_oDb->insertEvent($aEvent);
                if(!empty($iId))
                    $this->_oModule->onPost($iId);
                break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_UPDATE:
                $sMethod = '_getEventUpdate' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
                if(!method_exists($this, $sMethod))
                    $sMethod = '_getEventUpdate';

                $aEvent = $this->$sMethod($oAlert, $aHandler);
                if(empty($aEvent) || !is_array($aEvent))
                    break;
//var_dump($oAlert->aExtras); exit;
                $aContent = [];
                if(!empty($aEvent['content']) && is_string($aEvent['content']))
                    $aContent = unserialize($aEvent['content']);
                if(!empty($oAlert->aExtras) && is_array($oAlert->aExtras))
                    $aContent = array_merge($aContent, bx_process_input($oAlert->aExtras));

                $aParamsSet = ['content' => serialize($aContent)];
                $aParamsSetBySource = [];

                if($iObjectAuthorId != $aEvent['object_owner_id']) {
                    $aParamsSet['object_owner_id'] = $iObjectAuthorId;
                    $aParamsSetBySource['object_owner_id'] = $iObjectAuthorId;
                }

                if($iObjectPrivacyView > 0)
                    $aParamsSet = array_merge($aParamsSet, [
                        'owner_id' => $iObjectAuthorId,
                        'object_privacy_view' => $iObjectPrivacyView
                    ]);
                else if($iObjectPrivacyView < 0)
                    $aParamsSet = array_merge($aParamsSet, [
                        'owner_id' => abs($iObjectPrivacyView),
                        'object_privacy_view' => $iObjectPrivacyView 
                    ]);

                $bSource = false;
                $sSource = $aEvent[$CNF['FIELD_SOURCE']];
                if(($bSource = !empty($sSource)) !== false) {
                    $sSourceNew = '';
                    if(!empty($oAlert->aExtras['timeline_group']))
                        $sSourceNew = $oAlert->aExtras['timeline_group']['by'];

                    if($sSourceNew && strcmp($sSourceNew, $sSource) != 0) {
                        $aParamsSet['source'] = $sSourceNew;
                        $aParamsSetBySource['source'] = $sSourceNew;
                    }
                }

                foreach(['status', 'status_admin'] as $sStatusKey)
                    if(!empty($aContent[$sStatusKey])) {
                        $aParamsSet[$sStatusKey] = $aContent[$sStatusKey];
                        $aParamsSetBySource[$sStatusKey] = $aContent[$sStatusKey];
                    }

                $sMethod = 'get_timeline_update_data';
                if(bx_is_srv($oAlert->sUnit, $sMethod)) 
                    list($aParamsSet, $aParamsSetBySource) = bx_srv($oAlert->sUnit, $sMethod, [$oAlert, $aHandler, $aEvent, [$aParamsSet, $aParamsSetBySource]]);

                bx_alert($this->_sModule, 'before_update_data', 0, 0, [
                    'unit' => $oAlert->sUnit,
                    'action' => $oAlert->sAction,
                    'alert' => $oAlert,
                    'data' => &$aParamsSet,
                    'data_by_source' => &$aParamsSetBySource
                ]);

                $this->_oModule->_oDb->updateEvent($aParamsSet, ['id' => $aEvent[$CNF['FIELD_ID']]]);

                //--- Delete item cache.
                $this->_oModule->deleteCacheItem($aEvent[$CNF['FIELD_ID']]);              

                //--- Update related events.
                if($bSource && !empty($aParamsSetBySource)) {
                    $aEventsBySource = $this->_oModule->_oDb->getEvents(['browse' => 'source', 'value' => $sSource]);
                    foreach($aEventsBySource as $aEventBySource) {
                        if($aEventBySource[$CNF['FIELD_ID']] == $aEvent[$CNF['FIELD_ID']])
                            continue;

                        $this->_oModule->_oDb->updateEvent($aParamsSetBySource, ['id' => $aEventBySource[$CNF['FIELD_ID']]]);

                        //--- Delete item cache.
                        $this->_oModule->deleteCacheItem($aEventBySource[$CNF['FIELD_ID']]);
                    }
                }

                $this->_oModule->rebuildSlice();
                break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_DELETE:
                if($oAlert->sUnit == 'profile' && $oAlert->sAction == 'delete') {
                    $aEvents = $this->_oModule->_oDb->getEvents(array('browse' => 'owner_id', 'value' => $oAlert->iObject));
                    foreach($aEvents as $aEvent)
                        $this->_oModule->deleteEvent($aEvent);

                    if(!isset($oAlert->aExtras['delete_with_content']) || !$oAlert->aExtras['delete_with_content']) 
                        break;

                    $aEvents = $this->_oModule->_oDb->getEvents(array('browse' => 'common_by_object', 'value' => $oAlert->iObject));
                    foreach($aEvents as $aEvent)
                        $this->_oModule->deleteEvent($aEvent);
                    break;
                }

                $sMethod = '_getEventDelete' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
                if(!method_exists($this, $sMethod))
                    $sMethod = '_getEventDelete';

                $aEvent = $this->$sMethod($oAlert, $aHandler);
                if(empty($aEvent) || !is_array($aEvent))
                    break;

                $sMethod = 'get_timeline_delete_data';
                if(bx_is_srv($oAlert->sUnit, $sMethod)) 
                    $aEvent = bx_srv($oAlert->sUnit, $sMethod, [$oAlert, $aHandler, $aEvent]);

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

    protected function _getEventUpdateCommentEdited(&$oAlert, &$aHandler)
    {
        $aHandlers = $this->_oModule->_oDb->getHandlers([
            'type' => 'by_group_key_type', 
            'group' => $aHandler['group']
        ]);

        return $this->_oModule->_oDb->getEvents([
            'browse' => 'descriptor', 
            'type' => $oAlert->sUnit, 
            'action' => $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'],
            'object_id' => $oAlert->aExtras['comment_uniq_id']
        ]);
    }

    protected function _getEventDelete(&$oAlert, &$aHandler)
    {
        $aHandlers = $this->_oModule->_oDb->getHandlers([
            'type' => 'by_group_key_type', 
            'group' => $aHandler['group']
        ]);

        return $this->_oModule->_oDb->getEvents([
            'browse' => 'descriptor', 
            'type' => $oAlert->sUnit,
            'action' => $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'], 
            'object_id' => $oAlert->iObject
        ]);
    }
    
    protected function _getEventDeleteCommentDeleted(&$oAlert, &$aHandler)
    {
        $aHandlers = $this->_oModule->_oDb->getHandlers([
            'type' => 'by_group_key_type', 
            'group' => $aHandler['group']
        ]);

        return $this->_oModule->_oDb->getEvents([
            'browse' => 'descriptor', 
            'type' => $oAlert->sUnit,
            'action' => $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'], 
            'object_id' => $oAlert->aExtras['comment_uniq_id']
        ]);
    }

    protected function _processSystemClearCache($oAlert)
    {
        if(!in_array($oAlert->aExtras['type'], array('all', 'custom')))
            return;

        $this->_clearCache();
    }

    protected function _processSystemEnable($oAlert)
    {
        $aModuleConfig = $oAlert->aExtras['config'];
        if(empty($aModuleConfig) || !is_array($aModuleConfig))
            return false;

        $sName = $aModuleConfig['name'];
        if(!bx_srv_ii('system', 'is_module_context', [$sName]))
            return false;

        return $this->_oModule->serviceFeedsMenuAdd($sName);
    }

    protected function _processSystemDisable($oAlert)
    {
        $aModuleConfig = $oAlert->aExtras['config'];
        if(empty($aModuleConfig) || !is_array($aModuleConfig))
            return false;

        $sName = $aModuleConfig['name'];
        if(!bx_srv_ii('system', 'is_module_context', [$sName]))
            return false;

        return $this->_oModule->serviceFeedsMenuDelete($sName);
    }

    protected function _processSystemSaveSetting($oAlert)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if($CNF['PARAM_SEARCHABLE_FIELDS'] != $oAlert->aExtras['option'])
            return;

        $this->_oModule->_oDb->alterFulltextIndex();
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
                    bx_alert($this->_sModule, 'publish_failed', $aContentInfo[$CNF['FIELD_ID']], $iSystemBotProfileId, array(
                        'object_author_id' => $iAuthorProfileId,
                        'privacy_view' => BX_DOL_PG_ALL
                    ));
            }

            return;
        }

        if(isset($CNF['FIELD_PUBLISHED']) && isset($aContentInfo[$CNF['FIELD_PUBLISHED']]) && $aContentInfo[$CNF['FIELD_PUBLISHED']] > $iNow)
            return;

        $oTranscoder = BxDolTranscoder::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']);
        $aMediasToCheck = $this->_oModule->_oDb->getMedia($CNF['FIELD_VIDEO'], $iContentId, 0, true);
        foreach($aMediasToCheck as $aMediaToCheck)
            if($oTranscoder->isMimeTypeSupported($aMediaToCheck['mime_type']) && !$oTranscoder->isFileReady($aMediaToCheck['id']))
                return;

        if(!$this->_oModule->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => 'active'), array($CNF['FIELD_ID'] => $iContentId)))
            return;

        $this->_oModule->onPublished($iContentId);

        if($bNotify)
            bx_alert($this->_sModule, 'publish_succeeded', $aContentInfo[$CNF['FIELD_ID']], $iSystemBotProfileId, array(
                'object_author_id' => $iAuthorProfileId,
                'privacy_view' => BX_DOL_PG_ALL
            ));
    }
}

/** @} */
