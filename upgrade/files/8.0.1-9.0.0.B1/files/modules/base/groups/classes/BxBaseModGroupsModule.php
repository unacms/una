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

/**
 * Groups profiles module.
 */
class BxBaseModGroupsModule extends BxBaseModProfileModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    /**
     * Get possible recipients for start conversation form
     */
    public function actionAjaxGetInitialMembers ()
    {
        $sTerm = bx_get('term');

        $a = BxDolService::call('system', 'profiles_search', array($sTerm), 'TemplServiceProfiles');

        header('Content-Type:text/javascript; charset=utf-8');
        echo(json_encode($a));
    }

    /**
     * Check if this module entry can be used as profile
     */
    public function serviceActAsProfile ()
    {
        return false;
    }

    /**
     * Delete profile from fans and admins tables
     * @param $iProfileId profile id 
     */
    public function serviceDeleteProfileFromFansAndAdmins ($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        $this->_oDb->deleteAdminsByProfileId($iProfileId);

        if (isset($CNF['OBJECT_CONNECTIONS']) && ($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
            $oConnection->onDeleteInitiatorAndContent($iProfileId);
    }

    /**
     * Reset group's author for particular group
     * @param $iContentId group id 
     * @return false of error, or number of updated records on success
     */
    public function serviceReassignEntityAuthor ($iContentId)
    {
        $aContentInfo = $this->_oDb->getContentInfoById((int)$iContentId);
        if (!$aContentInfo)
            return false;

        if (!($oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName())))
            return false;

        $aAdmins = $this->_oDb->getAdmins($oGroupProfile->id());

        return $this->_oDb->updateAuthorById($iContentId, $aAdmins ? array_pop($aAdmins) : 0);
    }

    /**
     * Reset group's author when author profile is deleted
     * @param $iProfileId profile id 
     * @return number of changed items
     */
    public function serviceReassignEntitiesByAuthor ($iProfileId)
    {
        $a = $this->_oDb->getEntriesByAuthor((int)$iProfileId);
        if (!$a)
            return 0;

        $iCount = 0;
        foreach ($a as $aContentInfo)
            $iCount += ('' == $this->serviceReassignEntityAuthor($aContentInfo[$this->_oConfig->CNF['FIELD_ID']]) ? 1 : 0);

        return $iCount;
    }

    public function servicePrepareFields ($aFieldsProfile)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $aFieldsProfile[$CNF['FIELD_NAME']] = $aFieldsProfile['name'];
        $aFieldsProfile[$CNF['FIELD_TEXT']] = isset($aFieldsProfile['description']) ? $aFieldsProfile['description'] : '';
        unset($aFieldsProfile['name']);
        unset($aFieldsProfile['description']);
        return $aFieldsProfile;
    }

    public function serviceOnRemoveConnection ($iGroupProfileId, $iInitiatorId)
    {
        $CNF = &$this->_oConfig->CNF;

        list ($iProfileId, $iGroupProfileId, $oGroupProfile) = $this->_prepareProfileAndGroupProfile($iGroupProfileId, $iInitiatorId);
        if (!$oGroupProfile)
            return false;

        $this->_oDb->fromAdmins($iGroupProfileId, $iProfileId);

        if ($oConn = BxDolConnection::getObjectInstance('sys_profiles_subscriptions'))
            $oConn->removeConnection($iProfileId, $iGroupProfileId);
    }

    public function serviceAddMutualConnection ($iGroupProfileId, $iInitiatorId, $iIgnoreJoinConfirmation = false)
    {        
        $CNF = &$this->_oConfig->CNF;

        list ($iProfileId, $iGroupProfileId, $oGroupProfile) = $this->_prepareProfileAndGroupProfile($iGroupProfileId, $iInitiatorId);
        if (!$oGroupProfile)
            return false;

        if (!($aContentInfo = $this->_oDb->getContentInfoById((int)BxDolProfile::getInstance($iGroupProfileId)->getContentId())))
            return false;

        if (!($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
            return false;



        $sEntryTitle = $aContentInfo[$CNF['FIELD_NAME']];
        $sEntryUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);

        // send invitation to the group 
        if ($iIgnoreJoinConfirmation && !$oConnection->isConnected((int)$iInitiatorId, $oGroupProfile->id()) && !$oConnection->isConnected($oGroupProfile->id(), (int)$iInitiatorId) && bx_get_logged_profile_id() != $iProfileId) {

            bx_alert($this->getName(), 'join_invitation', $aContentInfo[$CNF['FIELD_ID']], $iGroupProfileId, array('content' => $aContentInfo, 'entry_title' => $sEntryTitle, 'entry_url' => $sEntryUrl, 'group_profile' => $iGroupProfileId, 'profile' => $iProfileId, 'notification_subobject_id' => $iProfileId, 'object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']]));

        }
        // send notification to group's admins that new connection is pending confirmation 
        elseif (!$iIgnoreJoinConfirmation && $oConnection->isConnected((int)$iInitiatorId, $oGroupProfile->id()) && !$oConnection->isConnected($oGroupProfile->id(), (int)$iInitiatorId) && $aContentInfo['join_confirmation']) {

            bx_alert($this->getName(), 'join_request', $aContentInfo[$CNF['FIELD_ID']], $iGroupProfileId, array('content' => $aContentInfo, 'entry_title' => $sEntryTitle, 'entry_url' => $sEntryUrl, 'group_profile' => $iGroupProfileId, 'profile' => $iProfileId, 'notification_subobject_id' => $iProfileId, 'object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']]));

        }
        // send notification that join request was accepted 
        else if (!$iIgnoreJoinConfirmation && $oConnection->isConnected((int)$iInitiatorId, $oGroupProfile->id(), true) && $oGroupProfile->getModule() != $this->getName() && bx_get_logged_profile_id() != $iProfileId) {
            
            bx_alert($this->getName(), 'join_request_accepted', $aContentInfo[$CNF['FIELD_ID']], $iGroupProfileId, array('content' => $aContentInfo, 'entry_title' => $sEntryTitle, 'entry_url' => $sEntryUrl, 'group_profile' => $iGroupProfileId, 'profile' => $iProfileId, 'notification_subobject_id' => $iProfileId, 'object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']]));

        }

        // new fan was added
        if ($oConnection->isConnected($oGroupProfile->id(), (int)$iInitiatorId, true)) {
            bx_alert($this->getName(), 'fan_added', $aContentInfo[$CNF['FIELD_ID']], $iGroupProfileId, array('content' => $aContentInfo, 'entry_title' => $sEntryTitle, 'entry_url' => $sEntryUrl, 'group_profile' => $iGroupProfileId, 'profile' => $iProfileId, 'notification_subobject_id' => $iProfileId, 'object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']]));
            return false;
        }

        // don't automatically add back connection (mutual) if group requires manual join confirmation
        if (!$iIgnoreJoinConfirmation && $aContentInfo['join_confirmation'])
            return false;

        // check if connection already exists
        if ($oConnection->isConnected($oGroupProfile->id(), (int)$iInitiatorId, true) || $oConnection->isConnected($oGroupProfile->id(), (int)$iInitiatorId))
            return false;

        if (!$oConnection->addConnection($oGroupProfile->id(), (int)$iInitiatorId))
            return false;

        return true;
    }

    public function serviceFansTable ()
    {
        $oGrid = BxDolGrid::getObjectInstance($this->_oConfig->CNF['OBJECT_GRID_CONNECTIONS']);
        if (!$oGrid)
            return false;

        return $oGrid->getCode();
    }

    public function serviceFans ($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        if (!($oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName())))
            return false;

        bx_import('BxDolConnection');
        $s = $this->serviceBrowseConnectionsQuick ($oGroupProfile->id(), $this->_oConfig->CNF['OBJECT_CONNECTIONS'], BX_CONNECTIONS_CONTENT_TYPE_CONTENT, true);
        if (!$s)
            return MsgBox(_t('_sys_txt_empty'));
        return $s;
    }

    public function serviceBrowseJoinedEntries ($iProfileId = 0, $bDisplayEmptyMsg = false)
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return '';

        return $this->_serviceBrowse ('joined_entries', array('joined_profile' => $iProfileId), BX_DB_PADDING_DEF, $bDisplayEmptyMsg);
    }

    /**
     * Entry social sharing block
     */
    public function serviceEntitySocialSharing ($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType((int)$iContentId, $this->getName());
        if (!$oGroupProfile)
            return false;

        $CNF = &$this->_oConfig->CNF;

        return $this->_entitySocialSharing ($iContentId, $iContentId, 0, $oGroupProfile->getDisplayName(), false, false, $CNF['OBJECT_VOTES'], $CNF['OBJECT_REPORTS'], $CNF['URI_VIEW_ENTRY']);
    }

	/**
     * Data for Notifications module
     */
    public function serviceGetNotificationsData()
    {
    	$sModule = $this->_aModule['name'];

        return array(
            'handlers' => array(
                array('group' => $sModule . '_vote', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVote', 'module_name' => $sModule, 'module_method' => 'get_notifications_vote', 'module_class' => 'Module'),
                array('group' => $sModule . '_vote', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'undoVote'),

                array('group' => $sModule . '_fan_added', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'fan_added', 'module_name' => $sModule, 'module_method' => 'get_notifications_fan_added', 'module_class' => 'Module'),

                array('group' => $sModule . '_join_request', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'join_request', 'module_name' => $sModule, 'module_method' => 'get_notifications_join_request', 'module_class' => 'Module', 'module_event_privacy' => $this->_oConfig->CNF['OBJECT_PRIVACY_VIEW_NOTIFICATION_EVENT']),
                
                array('group' => $sModule . '_timeline_post_common', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'timeline_post_common', 'module_name' => $sModule, 'module_method' => 'get_notifications_timeline_post_common', 'module_class' => 'Module'),
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'doVote'),
                array('unit' => $sModule, 'action' => 'undoVote'),
                array('unit' => $sModule, 'action' => 'fan_added'),
                array('unit' => $sModule, 'action' => 'join_request'),
                array('unit' => $sModule, 'action' => 'timeline_post_common'),
            )
        );
    }

    /**
     * Notification about new member requst in the group
     */
    public function serviceGetNotificationsJoinRequest($aEvent)
    {
        return $this->_serviceGetNotification($aEvent, $this->_oConfig->CNF['T']['txt_ntfs_join_request']);
    }
    
	/**
     * Notification about new member in the group
     */
    public function serviceGetNotificationsFanAdded($aEvent)
    {
        return $this->_serviceGetNotification($aEvent, $this->_oConfig->CNF['T']['txt_ntfs_fan_added']);
    }

    /**
     * Entry post for Timeline module
     */
    public function serviceGetTimelinePost($aEvent)
    {
        $a = parent::serviceGetTimelinePost($aEvent);

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aEvent['object_id'], $this->getName());

        $a['content']['url'] = $oGroupProfile->getUrl();
        $a['content']['title'] = $oGroupProfile->getDisplayName();
        
        return $a;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedViewCoverImage ($aDataEntry, $isPerformAction = false)
    {
        $oPrivacy = BxDolPrivacy::getObjectInstance($this->_oConfig->CNF['OBJECT_PRIVACY_VIEW']);
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = parent::checkAllowedViewCoverImage($aDataEntry)) && $oPrivacy->isPartiallyVisible($aDataEntry[$this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']]))
            return CHECK_ACTION_RESULT_ALLOWED;
        
        return $sMsg;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedViewProfileImage ($aDataEntry, $isPerformAction = false)
    {
        return $this->checkAllowedViewCoverImage($aDataEntry, $isPerformAction);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedPost ($aDataEntry, $isPerformAction = false)
    {
        if ($this->isFan($aDataEntry[$this->_oConfig->CNF['FIELD_ID']]))
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedView ($aDataEntry, $isPerformAction = false)
    {
        if ($this->isFan($aDataEntry[$this->_oConfig->CNF['FIELD_ID']]))
            return CHECK_ACTION_RESULT_ALLOWED;

        return parent::checkAllowedView ($aDataEntry, $isPerformAction);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedFanAdd (&$aDataEntry, $isPerformAction = false)
    {
        if (!$this->isFan($aDataEntry[$this->_oConfig->CNF['FIELD_ID']]) && isLogged())
            return CHECK_ACTION_RESULT_ALLOWED;

        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, $this->_oConfig->CNF['OBJECT_CONNECTIONS'], true, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedFanRemove (&$aDataEntry, $isPerformAction = false)
    {
        if (CHECK_ACTION_RESULT_ALLOWED === $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, $this->_oConfig->CNF['OBJECT_CONNECTIONS'], false, true, true))
            return CHECK_ACTION_RESULT_ALLOWED;
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, $this->_oConfig->CNF['OBJECT_CONNECTIONS'], false, true, false);
    }

    public function checkAllowedManageAdmins ($mixedDataEntry, $isPerformAction = false)
    {
        if (is_array($mixedDataEntry)) {
            $aDataEntry = $mixedDataEntry;
        }
        else {
            $oGroupProfile = BxDolProfile::getInstance((int)$mixedDataEntry);
            $aDataEntry = $oGroupProfile && $this->getName() == $oGroupProfile->getModule() ? $this->_oDb->getContentInfoById($oGroupProfile->getContentId()) : array();
        }

        return parent::checkAllowedEdit ($aDataEntry, $isPerformAction);
    }

    public function checkAllowedEdit ($aDataEntry, $isPerformAction = false)
    {
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->getName());
        if ($this->_oDb->isAdmin($oGroupProfile->id(), bx_get_logged_profile_id(), $aDataEntry))
            return CHECK_ACTION_RESULT_ALLOWED;
        return parent::checkAllowedEdit ($aDataEntry, $isPerformAction);
    }

    public function checkAllowedChangeCover ($aDataEntry, $isPerformAction = false)
    {
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->getName());
        if ($this->_oDb->isAdmin($oGroupProfile->id(), bx_get_logged_profile_id(), $aDataEntry))
            return CHECK_ACTION_RESULT_ALLOWED;
        return parent::checkAllowedChangeCover ($aDataEntry, $isPerformAction);
    }

    public function checkAllowedDelete (&$aDataEntry, $isPerformAction = false)
    {
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->getName());
        if ($oGroupProfile && $this->_oDb->isAdmin($oGroupProfile->id(), bx_get_logged_profile_id(), $aDataEntry))
            return CHECK_ACTION_RESULT_ALLOWED;
        return parent::checkAllowedDelete ($aDataEntry, $isPerformAction);
    }

    public function checkAllowedSubscribeAdd (&$aDataEntry, $isPerformAction = false)
    {
        if (!$this->isFan($aDataEntry[$this->_oConfig->CNF['FIELD_ID']]))
            return _t('_sys_txt_access_denied');

        return parent::checkAllowedSubscribeAdd ($aDataEntry, $isPerformAction);
    }

    protected function _checkAllowedConnect (&$aDataEntry, $isPerformAction, $sObjConnection, $isMutual, $isInvertResult, $isSwap = false)
    {
        $sResult = $this->checkAllowedView($aDataEntry);

        $oPrivacy = BxDolPrivacy::getObjectInstance($this->_oConfig->CNF['OBJECT_PRIVACY_VIEW']);

        if (CHECK_ACTION_RESULT_ALLOWED !== $sResult && !$oPrivacy->isPartiallyVisible($aDataEntry[$this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']]))
            return $sResult;

        return parent::_checkAllowedConnect ($aDataEntry, $isPerformAction, $sObjConnection, $isMutual, $isInvertResult, $isSwap);
    }

    public function isFan ($iContentId, $iProfileId = false) 
    {
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName());

        return $oGroupProfile && ($oConnection = BxDolConnection::getObjectInstance($this->_oConfig->CNF['OBJECT_CONNECTIONS'])) && $oConnection->isConnected($iProfileId ? $iProfileId : bx_get_logged_profile_id(), $oGroupProfile->id(), true);
    }

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl)
    {
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aEvent['object_id'], $this->getName());
                
        return array(
		    array('url' => $sUrl, 'src' => $oGroupProfile->getPicture()),
		);
    }

    protected function _prepareProfileAndGroupProfile($iGroupProfileId, $iInitiatorId)
    {
        if (!($oGroupProfile = BxDolProfile::getInstance($iGroupProfileId)))
            return array(0, 0, null);

        if ($oGroupProfile->getModule() == $this->getName()) {
            $iProfileId = $iInitiatorId;
            $iGroupProfileId = $oGroupProfile->id();
        } else {
            $iProfileId = $oGroupProfile->id();
            $iGroupProfileId = $iInitiatorId;
        }

        return array($iProfileId, $iGroupProfileId, $oGroupProfile);
    }
}

/** @} */
