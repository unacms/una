<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModGroupsAlertsResponse extends BxBaseModProfileAlertsResponse
{
    /*
     * Use Internal Notifications.
     */
    protected $_bUseIn;

    public function __construct()
    {
        parent::__construct();

        $this->_bUseIn = $this->_oModule->_oConfig->isInternalNotifications();
    }

    public function response($oAlert)
    {
        parent::response($oAlert);

        $CNF = &$this->_oModule->_oConfig->CNF;

        // connection events
        if (isset($CNF['OBJECT_CONNECTIONS']) && $CNF['OBJECT_CONNECTIONS'] == $oAlert->sUnit && 'connection_added' == $oAlert->sAction) {
            $this->_oModule->serviceAddMutualConnection($oAlert->aExtras['content'], $oAlert->aExtras['initiator']);
        }
        elseif (isset($CNF['OBJECT_CONNECTIONS']) && $CNF['OBJECT_CONNECTIONS'] == $oAlert->sUnit && 'connection_removed' == $oAlert->sAction) {
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
                if($this->_bUseIn)
                    $this->sendMailJoinRequest($oAlert, $oAlert->aExtras['profile'], bx_get_logged_profile_id());
                break;

            case 'join_request_accepted':
                if($this->_bUseIn)
                    $this->sendMailJoinRequestAccepted($oAlert, $oAlert->aExtras['profile'], bx_get_logged_profile_id());
                break;
        }
    }

    protected function sendMailInvitation ($oAlert, $iInvited, $iInviter = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $bTrackInvites = isset($CNF["TABLE_INVITES"]) && isset($CNF['OBJECT_CONNECTIONS']);
        if(!$this->_bUseIn && !$bTrackInvites)
            return;

        $sEntryUrl = $oAlert->aExtras['entry_url'];
        if($bTrackInvites) {
            if(!($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
                return;

            if($iInviter != $oAlert->iSender && !$oConnection->isConnected($iInviter, $oAlert->iSender))
                return;

            if($this->_oModule->_oDb->isInviteByInvited($iInvited, $oAlert->iSender))
                return;

            $sKey = BxDolKey::getInstance()->getNewKey(false, $CNF["INVITES_KEYS_LIFETIME"]);
            $this->_oModule->_oDb->insertInvite($sKey, $oAlert->iSender, $iInviter, $iInvited);
            $sEntryUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'], [
                'id' => $oAlert->iObject,
                'key' => $sKey
            ]));
        }

        sendMailTemplate($this->_oModule->_oConfig->CNF['EMAIL_INVITATION'], 0, $iInvited, array(
            'InviterUrl' => BxDolProfile::getInstance($iInviter)->getUrl(),
            'InviterDisplayName' => BxDolProfile::getInstance($iInviter)->getDisplayName(),
            'EntryUrl' => $sEntryUrl,
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
