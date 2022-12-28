<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModProfileAlertsResponse extends BxBaseModGeneralAlertsResponse
{
    public function __construct()
    {
        parent::__construct();
    }

    public function response($oAlert)
    {
        parent::response($oAlert);

        $CNF = $this->_oModule->_oConfig->CNF;
        $sModule = $this->_oModule->getName();

        // update picture field id when file is deleted
        if ($CNF['OBJECT_STORAGE'] == $oAlert->sUnit && 'file_deleted' == $oAlert->sAction && isset($CNF['FIELD_PICTURE'])) {
            $bResult = (int)$this->_oModule->_oDb->resetContentPictureByFileId($oAlert->iObject, $CNF['FIELD_PICTURE']) > 0;
            if($bResult)
                bx_alert($sModule, 'profile_picture_deleted', $oAlert->iObject);
        }

        if ($CNF['OBJECT_STORAGE_COVER'] == $oAlert->sUnit && 'file_deleted' == $oAlert->sAction && isset($CNF['FIELD_COVER'])) {
            $bResult = (int)$this->_oModule->_oDb->resetContentPictureByFileId($oAlert->iObject, $CNF['FIELD_COVER']) > 0;
            if($bResult)
                bx_alert($sModule, 'profile_cover_deleted', $oAlert->iObject);
        }

        // connection events
        if ($oAlert->sUnit == 'sys_profiles_friends' && $oAlert->sAction == 'connection_added') {
            if((int)$oAlert->aExtras['mutual'] == 0 && !BxDolModuleQuery::getInstance()->isEnabledByName('bx_notifications'))
                $this->sendMailFriendRequest($oAlert);
        }

        /*
         * Re-translate timeline alert for timeline posts made by other profiles.
         * It's used with Notifications module in the following circumstances:
         * 1. User1 follow User2.
         * 2. User1 doesn't follow User3.
         * 3. User3 posts something on User2's timeline.
         * Result: User1 would be notifiend when the timeline of following profile (User2) was update by 3d party user.
         */
        /*
         * The code was commented because it looks like the Retranslation of 
         * Timeline Common Post is redundant. It's happened after system of Contexts was created.
         * 
         * TODO: If nothing happened, remove the code in UNA v.10.
         * Don't forget to:
         * 1. Stop listening 'bx_timeline' - 'post_common' alert.
         * 2. Remove BxBaseModProfileModule::serviceGetNotificationsTimelinePostCommon method
         * 3. Remove language keys from $CNF['T']['txt_ntfs_timeline_post_common']
         * 
        if($oAlert->sUnit == 'bx_timeline' && $oAlert->sAction == 'post_common' && !empty($oAlert->aExtras['owner_id'])) {
            $iTimelineOwner = (int)$oAlert->aExtras['owner_id'];
            $oTimelineOwner = BxDolProfile::getInstance($iTimelineOwner);
            if($oTimelineOwner && $oTimelineOwner->getModule() == $this->_oModule->getName() && $iTimelineOwner != $oAlert->iSender) {
                $aContentInfo = $this->_oModule->serviceGetContentInfoById($oTimelineOwner->getContentId());

                //--- Note. Timeline owner profile ID is used as alert sender and also a content (group) owner (author).
                $iSenderId = $iObjectAuthorId = $iTimelineOwner; 
                bx_alert($this->_oModule->getName(), 'timeline_post_common', $aContentInfo[$CNF['FIELD_ID']], $iSenderId, array(
                    'object_author_id' => $iObjectAuthorId,
                    'timeline_post_id' => $oAlert->iObject, 
                    'timeline_post_author_id' => $oAlert->iSender,

                    'content' => $aContentInfo,

                    'group_profile' => $iTimelineOwner, 
                    'profile' => $oAlert->iSender,
                ));
            }
        }
        */

        if ($this->MODULE != $oAlert->sUnit)
            return;

        // timeline events to override permissions
        switch ($oAlert->sAction) {
        case 'timeline_view':
            $this->processTimelineView($oAlert, $oAlert->iObject);
            break;

        case 'timeline_report':
            $this->processTimelineEventsBoolResult($oAlert, $oAlert->iObject,'checkAllowedView');
            break;    
            
        case 'timeline_comment':
        case 'timeline_vote':
        case 'timeline_score':
            $this->processTimelineEventsBoolResult($oAlert, $oAlert->iObject);
            break;

        case 'timeline_post':
            $this->processTimelineEventsCheckResult($oAlert, $oAlert->iObject);
            break;

        case 'timeline_pin':
        case 'timeline_delete':
            $this->processTimelineEventsCheckResult($oAlert, $oAlert->iObject, 'checkAllowedEdit');
            break;

        case 'timeline_repost':
            $this->processTimelineRepost($oAlert, $oAlert->iObject);
            break;
        }

    }

    protected function sendMailFriendRequest ($oAlert)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if(empty($CNF['EMAIL_FRIEND_REQUEST']) || empty($CNF['URI_VIEW_FRIEND_REQUESTS']))
            return;

        $iRecipient = $oAlert->aExtras['content'];
        $oRecipient = BxDolProfile::getInstance($iRecipient);
        if($oRecipient->getModule() != $this->MODULE)
            return;

        $iSender = $oAlert->aExtras['initiator'];
        $oSender = BxDolProfile::getInstance($iSender);
        sendMailTemplate($CNF['EMAIL_FRIEND_REQUEST'], 0, $iRecipient, array(
            'SenderUrl' => $oSender->getUrl(),
            'SenderDisplayName' => $oSender->getDisplayName(),
            'FriendsLink' => bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_FRIEND_REQUESTS'] . '&profile_id=' . $iRecipient)),
        ), BX_EMAIL_NOTIFY);
    }

    protected function processTimelineView ($oAlert, $iGroupProfileId, $bDisableOwnerActions = false)
    {
        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $bDisableOwnerActions = !BxDolService::call($oGroupProfile->getModule(), 'act_as_profile');

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (CHECK_ACTION_RESULT_ALLOWED !== $this->_oModule->checkAllowedView($aContentInfo)) {
            $oAlert->aExtras['override_content'] = MsgBox(_t('_sys_access_denied_to_private_content'));
        }

        if ($bDisableOwnerActions && isset($oAlert->aExtras['menu'])) {
            foreach ($oAlert->aExtras['menu'] as $i => $r) {
                if ('timeline-view-owner' == $r['id'] || 'timeline-view-other'  == $r['id'])
                    unset($oAlert->aExtras['menu'][$i]);
            }
        }
    }

    protected function processTimelineEventsCheckResult ($oAlert, $iGroupProfileId, $sFunc = 'checkAllowedPost')
    {
        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (CHECK_ACTION_RESULT_ALLOWED === ($s = $this->_oModule->$sFunc($aContentInfo))) {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
        } 
        else {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_MESSAGE_NOT_ALLOWED;
            $oAlert->aExtras['check_result'][CHECK_ACTION_MESSAGE] = $s;
        }
    }

    protected function processTimelineEventsBoolResult ($oAlert, $iGroupProfileId, $sFunc = 'checkAllowedPost')
    {
        if (isAdmin() || !$oAlert->aExtras['result'])
            return;

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        $oAlert->aExtras['result'] = (CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->$sFunc($aContentInfo)) ? true : false;
    }

    protected function processTimelineRepost ($oAlert, $iGroupProfileId)
    {
        if ($oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return;

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (BX_DOL_PG_ALL == $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']]) {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
        }
        else {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_MESSAGE_NOT_ALLOWED;
            $oAlert->aExtras['check_result'][CHECK_ACTION_MESSAGE] = _t('_sys_access_denied_to_private_content');
        }
    }
}

/** @} */
