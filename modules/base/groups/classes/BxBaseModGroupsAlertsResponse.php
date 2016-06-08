<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModGroupsAlertsResponse extends BxBaseModProfileAlertsResponse
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function response($oAlert)
    {
        parent::response($oAlert);

        $CNF = $this->_oModule->_oConfig->CNF;

        // connection events
        if ($CNF['OBJECT_CONNECTIONS'] == $oAlert->sUnit && 'connection_added' == $oAlert->sAction) {
            $this->_oModule->serviceAddMutualConnection($oAlert->aExtras['content'], $oAlert->aExtras['initiator']);
        }
        elseif ($CNF['OBJECT_CONNECTIONS'] == $oAlert->sUnit && 'connection_removed' == $oAlert->sAction) {
            $this->_oModule->serviceOnRemoveConnection($oAlert->aExtras['content'], $oAlert->aExtras['initiator']);
        }

        // profile delete event
        if ('profile' == $oAlert->sUnit && 'delete' == $oAlert->sAction) {
            $this->_oModule->serviceDeleteProfileFromFansAndAdmins($oAlert->iObject);
            $this->_oModule->serviceReassignEntitiesByAuthor($oAlert->iObject);
        }

        if ($this->MODULE != $oAlert->sUnit)
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
    }

    protected function sendMailInvitation ($oAlert, $iProfileId, $iSender = 0)
    {
        sendMailTemplate($this->_oModule->_oConfig->CNF['EMAIL_INVITATION'], 0, $iProfileId, array(
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
            sendMailTemplate($this->_oModule->_oConfig->CNF['EMAIL_JOIN_REQUEST'], 0, $iAdminProfileId, array(
                'NewMemberUrl' => BxDolProfile::getInstance($iProfileId)->getUrl(),
                'NewMemberDisplayName' => BxDolProfile::getInstance($iProfileId)->getDisplayName(),
                'EntryUrl' => $oAlert->aExtras['entry_url'],
                'EntryTitle' => $oAlert->aExtras['entry_title'],
            ), BX_EMAIL_NOTIFY);
        }
    }

    protected function sendMailJoinRequestAccepted ($oAlert, $iProfileId, $iSender = 0)
    {
        sendMailTemplate($this->_oModule->_oConfig->CNF['EMAIL_JOIN_CONFIRM'], 0, $iProfileId, array(
            'EntryUrl' => $oAlert->aExtras['entry_url'],
            'EntryTitle' => $oAlert->aExtras['entry_title'],
        ), BX_EMAIL_NOTIFY);
    }

    protected function processTimelineShare ($oAlert, $iGroupProfileId)
    {
        if ($oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return;

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if (!$oGroupProfile)
            return;

        $oPrivacy = BxDolPrivacy::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_PRIVACY_VIEW']);

        $aContentInfo = $this->_oModule->serviceGetContentInfoById($oGroupProfile->getContentId());
        if (BX_DOL_PG_ALL == $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']] || (BX_DOL_PG_MEMBERS == $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']] && isLogged()) || $oPrivacy->isPartiallyVisible($aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']])) {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_RESULT_ALLOWED;
        }
        else {
            $oAlert->aExtras['check_result'][CHECK_ACTION_RESULT] = CHECK_ACTION_MESSAGE_NOT_ALLOWED;
            $oAlert->aExtras['check_result'][CHECK_ACTION_MESSAGE] = _t('_sys_access_denied_to_private_content');
        }
    }
}

/** @} */
