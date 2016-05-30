<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * alerts handler
 */
class BxGroupsAlertsResponse extends BxDolAlertsResponse
{
    protected $_oModule;

    public function response($oAlert)
    {
        $this->_oModule = BxDolModule::getInstance('bx_groups');

        // connection events
        if ('bx_groups_fans' == $oAlert->sUnit && 'connection_added' == $oAlert->sAction) {
            $this->_oModule->serviceAddMutualConnection($oAlert->aExtras['content'], $oAlert->aExtras['initiator']);
        }
        elseif ('bx_groups_fans' == $oAlert->sUnit && 'connection_removed' == $oAlert->sAction) {
            $this->_oModule->serviceOnRemoveConnection($oAlert->aExtras['content'], $oAlert->aExtras['initiator']);
        }

        // profile delete event
        if ('profile' == $oAlert->sUnit && 'delete' == $oAlert->sAction) {
            $this->_oModule->serviceDeleteProfileFromFansAndAdmins($oAlert->iObject);
            $this->_oModule->serviceReassignEntitiesByAuthor($oAlert->iObject);
        }

        if ('bx_groups' != $oAlert->sUnit)
            return;

        // join group events
        switch ($oAlert->sAction) {
        case 'join_invitation':
            $this->sendMailInvitation($oAlert, $oAlert->aExtras['profile'], bx_get_logged_profile_id());
            break;
        case 'join_request':
            $this->sendMailJoinRequest($oAlert, $oAlert->aExtras['profile'], bx_get_logged_profile_id());
            break;
        case 'join_request_accepted':
            $this->sendMailJoinRequestAccepted($oAlert, $oAlert->aExtras['profile'], bx_get_logged_profile_id());
            break;
        }

        // timeline events to override permissions
        switch ($oAlert->sAction) {
        case 'timeline_view':
            $this->processTimelineView($oAlert, $oAlert->iObject);
            break;

        case 'timeline_comment':
        case 'timeline_report':
        case 'timeline_vote':
            $this->processTimelineEventsBoolResult($oAlert, $oAlert->iObject);
            break;

        case 'timeline_post':
            $this->processTimelineEventsCheckResult($oAlert, $oAlert->iObject);
            break;

        case 'timeline_delete':
            $this->processTimelineEventsCheckResult($oAlert, $oAlert->iObject, 'checkAllowedEdit');
            break;

        case 'timeline_share':
            $this->processTimelineShare($oAlert, $oAlert->iObject);
            break;
        }

    }

    protected function sendMailInvitation ($oAlert, $iProfileId, $iSender = 0)
    {
        sendMailTemplate('bx_groups_invitation', 0, $iProfileId, array(
            'InviterUrl' => BxDolProfile::getInstance($iSender)->getUrl(),
            'InviterDisplayName' => BxDolProfile::getInstance($iSender)->getDisplayName(),
            'EntryUrl' => $oAlert->aExtras['entry_url'],
            'EntryTitle' => $oAlert->aExtras['entry_title'],
        ), BX_EMAIL_NOTIFY);
    }

    protected function sendMailJoinRequest ($oAlert, $iProfileId, $iSender = 0)
    {
        $aAdmins = $this->_oModule->_oDb->getAdmins($oAlert->aExtras['group_profile']);
        foreach ($aAdmins as $iAdminProfileId) {
            sendMailTemplate('bx_groups_join_request', 0, $iAdminProfileId, array(
                'NewMemberUrl' => BxDolProfile::getInstance($iProfileId)->getUrl(),
                'NewMemberDisplayName' => BxDolProfile::getInstance($iProfileId)->getDisplayName(),
                'EntryUrl' => $oAlert->aExtras['entry_url'],
                'EntryTitle' => $oAlert->aExtras['entry_title'],
            ), BX_EMAIL_NOTIFY);
        }
    }

    protected function sendMailJoinRequestAccepted ($oAlert, $iProfileId, $iSender = 0)
    {
        sendMailTemplate('bx_groups_join_confirm', 0, $iProfileId, array(
            'EntryUrl' => $oAlert->aExtras['entry_url'],
            'EntryTitle' => $oAlert->aExtras['entry_title'],
        ), BX_EMAIL_NOTIFY);
    }
    
    protected function processTimelineView ($oAlert, $iGroupProfileId)
    {
        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (CHECK_ACTION_RESULT_ALLOWED !== $this->_oModule->checkAllowedView($aContentInfo)) {
            $oAlert->aExtras['override_content'] = MsgBox(_t('_sys_access_denied_to_private_content'));
        }

        foreach ($oAlert->aExtras['menu'] as $i => $r) {
            if ('timeline-view-owner' == $r['id'] || 'timeline-view-other'  == $r['id'])
                unset($oAlert->aExtras['menu'][$i]);
        }
    }

    protected function processTimelineEventsCheckResult ($oAlert, $iGroupProfileId, $sFunc = 'checkAllowedPost')
    {
        if ($oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return;

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
        if (!$oAlert->aExtras['result'])
            return;

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        $oAlert->aExtras['result'] = (CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->$sFunc($aContentInfo)) ? true : false;
    }

    protected function processTimelineShare ($oAlert, $iGroupProfileId)
    {
        if ($oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return;

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile) 
            return;

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (BX_DOL_PG_ALL == $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']] || 'c' == $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']]) {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
        }
        else {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_MESSAGE_NOT_ALLOWED;
            $oAlert->aExtras['check_result'][CHECK_ACTION_MESSAGE] = _t('_sys_access_denied_to_private_content');
        }
    }
}

/** @} */
