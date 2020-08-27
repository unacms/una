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
        if ($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        if (!($oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_oModule->_oConfig->getName())))
            return '';

        $this->makeAuthorAdmin ($oGroupProfile, bx_get('initial_members'));

        $this->inviteMembers ($oGroupProfile, bx_get('initial_members'));

        return '';
    }

    public function onDataEditAfter ($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        if ($s = parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm))
            return $s;

        if (!($oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_oModule->_oConfig->getName())))
            return ''; 

        $this->inviteMembers ($oGroupProfile, bx_get('initial_members'));

        return '';
    }
    
    protected function redirectAfterEdit($aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if (bx_get('initial_members') && isset($CNF['URL_ENTRY_FANS'])){
            $this->_redirectAndExit($CNF['URL_ENTRY_FANS'] . '&profile_id=' . $aContentInfo['profile_id']);
        }
        else{
            $this->_redirectAndExit('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
        }
    }
    
    public function onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_oModule->_oConfig->getName());

        if ($oGroupProfile && isset($CNF['TABLE_ADMINS']))
            $this->_oModule->_oDb->deleteAdminsByGroupId($oGroupProfile->id());

        if (isset($CNF['OBJECT_CONNECTIONS']) && $oGroupProfile && ($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
            $oConnection->onDeleteInitiatorAndContent($oGroupProfile->id());

        if(($oPrivacyView = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) !== false)
            $oPrivacyView->deleteGroupCustomByContentId($iContentId);

        if(($oPrivacyPost = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_POST'])) !== false)
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
        $CNF = &$this->_oModule->_oConfig->CNF;
        $iAdminProfileId = bx_get_logged_profile_id();

        if (!isset($CNF['OBJECT_CONNECTIONS']) || !$CNF['OBJECT_CONNECTIONS'] || !is_array($aInitialProfiles) || !in_array($iAdminProfileId, $aInitialProfiles))
            return;

        if (!($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
            return;

        if (!$oConnection->isConnected($oGroupProfile->id(), (int)$iAdminProfileId))
            $oConnection->addConnection($oGroupProfile->id(), (int)$iAdminProfileId);
        if (!$oConnection->isConnected((int)$iAdminProfileId, $oGroupProfile->id()))
            $oConnection->addConnection((int)$iAdminProfileId, $oGroupProfile->id());

        if (!$this->_oModule->_oDb->isAdmin ($oGroupProfile->id(), $iAdminProfileId))
            $this->_oModule->_oDb->toAdmins ($oGroupProfile->id(), $iAdminProfileId);
    }
}

/** @} */
