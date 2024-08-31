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

/**
 * Group profile forms functions
 */
class BxBaseModGroupsFormsEntryHelper extends BxBaseModProfileFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }
    
    public function getObjectFormInvite ($sDisplay = false)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        if($sDisplay === false)
            $sDisplay = $CNF['OBJECT_FORM_ENTRY_DISPLAY_INVITE'];

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sDisplay, $this->_oModule->_oTemplate);
        if($this->_bAjaxMode)
            $oForm->setAjaxMode($this->_bAjaxMode);

        if($this->_bAbsoluteActionUrl)
            $this->_setAbsoluteActionUrl('edit', $oForm);

        return $oForm;
    }

    public function inviteForm ($iContentId, $sDisplay = false, $sCheckFunction = false, $bErrorMsg = true)
    {
        if (!$sCheckFunction)
            $sCheckFunction = 'checkAllowedInvite';

        $CNF = &$this->_oModule->_oConfig->CNF;

        // get content data and profile info
        list($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);

        if(!$aContentInfo)
            return $bErrorMsg ? ($this->_bIsApi ? [bx_api_get_msg('_sys_txt_error_entry_is_not_defined')] : MsgBox('_sys_txt_error_entry_is_not_defined')) : '';

        // check access
        if(($sMsg = $this->_oModule->$sCheckFunction($aContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return $bErrorMsg ? ($this->_bIsApi ? [bx_api_get_msg($sMsg)] : MsgBox($sMsg)) : '';

        // check and display form
        $oForm = $this->getObjectFormInvite($sDisplay);

        if(!$oForm)
            return $bErrorMsg ? ($this->_bIsApi ? [bx_api_get_msg('_sys_txt_error_occured')] : MsgBox(_t('_sys_txt_error_occured'))) : '';

        $oForm->initChecker($aContentInfo);
        if (!$oForm->isSubmittedAndValid())
            return $this->_bIsApi ? [bx_api_get_block('form', $oForm->getCodeAPI(), ['ext' => ['name' => $this->_oModule->getName(), 'request' => ['url' => '/api.php?r=' . $this->_oModule->getName() . '/entity_invite&params[]=' . $iContentId . '&params[]=' . $sDisplay, 'immutable' => true]]])] : $oForm->getCode();

        $this->onDataInviteBefore($aContentInfo[$CNF['FIELD_ID']], $aContentInfo);

        if (!$oForm->update($aContentInfo[$CNF['FIELD_ID']])) {
            if (!$oForm->isValid())
                return $this->_bIsApi ? [bx_api_get_block('form', $oForm->getCodeAPI(), ['ext' => ['name' => $this->_oModule->getName(), 'request' => ['url' => '/api.php?r=' . $this->_oModule->getName() . '/entity_invit&params[]=' . $iContentId . '&params[]=' . $sDisplay, 'immutable' => true]]])] : $oForm->getCode();
            else
                return $this->_bIsApi ?  [bx_api_get_msg('_sys_txt_error_entry_update')] : MsgBox(_t('_sys_txt_error_entry_update'));
        }

        list($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);

        $sResult = $this->onDataInviteAfter($aContentInfo[$CNF['FIELD_ID']], $aContentInfo);
        if($sResult)
            return $sResult;

        // Perform ACL action
        $this->_oModule->$sCheckFunction($aContentInfo, true);

        // Redirect
         if (bx_is_api())
            return [$this->redirectAfterEdit($aContentInfo)];
        else
        $this->redirectAfterEdit($aContentInfo);
    }

    protected function _getProfileAndContentData ($iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
            return array(false, false);

        $oProfile = BxDolProfile::getInstanceMagic($aContentInfo[$CNF['FIELD_AUTHOR']]);
        return array($oProfile, $aContentInfo);
    }

    protected function _processPermissionsCheckForViewDataForm ($aContentInfo, $oProfile)
    {
        $sMsg = parent::_processPermissionsCheckForViewDataForm ($aContentInfo, $oProfile);

        $oPrivacy = BxDolPrivacy::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_PRIVACY_VIEW']);
        if ($sMsg && $oPrivacy->isPartiallyVisible($aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']]))
            return '';

        return $sMsg;
    }

    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        if($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_oModule->_oConfig->getName());
        if(!$oGroupProfile)
            return '';

        $mInitialMembers = bx_get('initial_members');
        if($this->_bIsApi && $mInitialMembers && is_string($mInitialMembers))
            $mInitialMembers = explode(',', $mInitialMembers);

        $this->makeAuthorAdmin($oGroupProfile, $mInitialMembers);

        $this->inviteMembers($oGroupProfile, $mInitialMembers);

        return '';
    }

    public function onDataEditAfter ($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        if($s = parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm))
            return $s;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_oModule->_oConfig->getName());
        if(!$oGroupProfile)
            return ''; 

        $this->inviteMembers ($oGroupProfile, bx_get('initial_members'));

        return '';
    }

    public function onDataInviteBefore ($iContentId, $aContentInfo)
    {
        return '';
    }

    public function onDataInviteAfter ($iContentId, $aContentInfo)
    {
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_oModule->_oConfig->getName());
        if(!$oGroupProfile)
            return '';

        $this->inviteMembers($oGroupProfile, bx_get('initial_members'));

        return '';
    }   
    
    protected function redirectAfterEdit($aContentInfo, $sUrl = '')
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = '';
        if(bx_get('initial_members')) {
            if(isset($CNF['URL_ENTRY_MANAGE']))
                $sUrl = $CNF['URL_ENTRY_MANAGE'];
            else if(isset($CNF['URL_ENTRY_FANS']))
                $sUrl = $CNF['URL_ENTRY_FANS'];

            $sUrl = bx_append_url_params($sUrl, ['profile_id' => $aContentInfo['profile_id']]);
        }
        else
            $sUrl = 'page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']];

        if(bx_is_api())
            return bx_api_get_block('redirect', ['uri' => '/' . BxDolPermalinks::getInstance()->permalink($sUrl), 'timeout' => 1000]);

        /**
         * @hooks
         * @hookdef hook-bx_base_groups-redirect_after_edit '{module_name}', 'redirect_after_edit' - hook to override redirect URL which is used after content changing
         * It's equivalent to @ref hook-bx_base_general-redirect_after_edit
         * @hook @ref hook-bx_base_groups-redirect_after_edit
         */
        bx_alert($this->_oModule->getName(), 'redirect_after_edit', 0, false, [
            'content' => $aContentInfo,
            'override_result' => &$sUrl,
        ]);

        $this->_redirectAndExit($sUrl);
    }
    
    public function onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_oModule->_oConfig->getName());

        if(isset($CNF['TABLE_QUESTIONS'], $CNF['TABLE_ANSWERS']))
            $this->_oModule->_oDb->deleteQuestionnaires($iContentId);

        if ($oGroupProfile && isset($CNF['TABLE_ADMINS']))
            $this->_oModule->_oDb->deleteAdminsByGroupId($oGroupProfile->id());

        if (isset($CNF['OBJECT_CONNECTIONS']) && $oGroupProfile && ($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
            $oConnection->onDeleteInitiatorAndContent($oGroupProfile->id());

        if((isset($CNF['OBJECT_PRIVACY_VIEW']) && $oPrivacyView = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) !== false)
            $oPrivacyView->deleteGroupCustomByContentId($iContentId);

        if((isset($CNF['OBJECT_PRIVACY_POST']) && $oPrivacyPost = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_POST'])) !== false)
            $oPrivacyPost->deleteGroupCustomByContentId($iContentId);

        return '';
    }

    protected function inviteMembers ($oGroupProfile, $aInitialProfiles)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_CONNECTIONS']) || !$aInitialProfiles)
            return;

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);
        if(!$oConnection)
            return;

        $iGroupId = $oGroupProfile->id();

        if (!is_array($aInitialProfiles))
			$aInitialProfiles = [$aInitialProfiles];
        
        // insert invited members, so they will join without confirmation
        foreach($aInitialProfiles as $iProfileId) {
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if(!$oProfile)
                continue;

            if($oConnection->isConnected($iGroupId, $iProfileId, true) || $oConnection->isConnected($iGroupId, $iProfileId))
                continue;

            $this->_oModule->serviceAddMutualConnection ($iGroupId, $iProfileId, true);            
        }
    }

    /**
     * Make author admin if their is in initial invitations list
     * @param $oGroupProfile group id
     * @param $aInitialProfiles array of initial profile ids
     * @return nothing
     */ 
    protected function makeAuthorAdmin ($oGroupProfile, $aInitialProfiles)
    {
        $this->makeAdmin (bx_get_logged_profile_id(), $oGroupProfile, $aInitialProfiles);
    }

    /**
     * Make profile admin if he is in initial invitations list
     * @param $iProfileId profile id
     * @param $oGroupProfile group id
     * @param $aInitialProfiles array of initial profile ids
     * @return nothing
     */ 
    protected function makeAdmin ($iProfileId, $oGroupProfile, $aInitialProfiles)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!isset($CNF['OBJECT_CONNECTIONS']) || !$CNF['OBJECT_CONNECTIONS'] || !is_array($aInitialProfiles) || !in_array($iProfileId, $aInitialProfiles))
            return;

        if (!($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
            return;

        if (!$oConnection->isConnected($oGroupProfile->id(), (int)$iProfileId))
            $oConnection->addConnection($oGroupProfile->id(), (int)$iProfileId);
        if (!$oConnection->isConnected((int)$iProfileId, $oGroupProfile->id()))
            $oConnection->addConnection((int)$iProfileId, $oGroupProfile->id());

        if (!$this->_oModule->_oDb->isAdmin ($oGroupProfile->id(), $iProfileId))
            $this->_oModule->_oDb->toAdmins ($oGroupProfile->id(), $iProfileId);
    }
}

/** @} */
