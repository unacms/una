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

/**
 * Profile forms helper functions
 */
class BxBaseModProfileFormsEntryHelper extends BxBaseModGeneralFormsEntryHelper
{
    protected $_bAutoApproval = false;

    public function __construct($oModule)
    {
        parent::__construct($oModule);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $bAutoApproval = isset($CNF['PARAM_AUTOAPPROVAL']) ? (bool)getParam($CNF['PARAM_AUTOAPPROVAL']) : true;
        $bAdministrator = BxDolAcl::getInstance()->isMemberLevelInSet(array(MEMBERSHIP_ID_ADMINISTRATOR));
        $this->setAutoApproval(isAdmin() || $bAdministrator || $bAutoApproval);
    }

    public function isAutoApproval()
    {
        return $this->_bAutoApproval;
    }

    public function setAutoApproval($b)
    {
        return ($this->_bAutoApproval = $b);
    }

    protected function _getProfileAndContentData ($iContentId)
    {
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
            return array (false, false);

        $oProfile = BxDolProfile::getInstanceMagic($aContentInfo['profile_id']);
        return array($oProfile, $aContentInfo);
    }

    public function deleteData ($iContentId, $aContentInfo = false, $oProfile = null, $oForm = null)
    {
        if (!$aContentInfo)
            list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);

        // delete profile
        $oProfile = BxDolProfile::getInstance($aContentInfo['profile_id']);
        if (!$oProfile->delete())
            return _t('_sys_txt_error_entry_delete');

        return '';
    }

    public function deleteDataService ($iContentId, $aContentInfo = false, $oProfile = null, $oForm = null)
    {
        return parent::deleteData ($iContentId, $aContentInfo, $oProfile, $oForm);
    }

    public function onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile)
    {
        if($s = parent::onDataDeleteAfter($iContentId, $aContentInfo, $oProfile))
            return $s;

        $CNF = &$this->_oModule->_oConfig->CNF;

        $bActAsProfile = BxDolService::call($oProfile->getModule(), 'act_as_profile');
        if(($oPrivacyView = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) !== false && $bActAsProfile)
            $oPrivacyView->deleteGroupCustomByProfileId($oProfile->id());

        if(($oPrivacyPost = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_POST'])) !== false) {
            $oPrivacyPost->deleteGroupCustomByContentId($iContentId);

            if($bActAsProfile)
                $oPrivacyPost->deleteGroupCustomByProfileId($oProfile->id());
        }

        return '';
    }

    public function onDataEditBefore ($iContentId, $aContentInfo, &$aTrackTextFieldsChanges, &$oProfile, &$oForm)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);

        $sStatus = $oProfile->getStatus();
        if (!$this->isAutoApproval() && BX_PROFILE_STATUS_ACTIVE == $sStatus)
            $aTrackTextFieldsChanges = array ();
    }

    public function onDataEditAfter ($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        if ($s = parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm))
            return $s;

        $CNF = &$this->_oModule->_oConfig->CNF;

        /*
         * Load updated data.
         */
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

        // change profile to 'pending' only if profile is 'active'
        $sStatus = $oProfile->getStatus();
        if (!$this->isAutoApproval() && BX_PROFILE_STATUS_ACTIVE == $sStatus && !empty($aTrackTextFieldsChanges['changed_fields']))
            $oProfile->disapprove(BX_PROFILE_ACTION_AUTO, 0, $this->_oModule->serviceActAsProfile());

        // process uploaded files
        if (isset($CNF['FIELD_PICTURE']))
            $oForm->processFiles($CNF['FIELD_PICTURE'], $iContentId, false);
        if (isset($CNF['FIELD_COVER']))
            $oForm->processFiles($CNF['FIELD_COVER'], $iContentId, false);

        if(isset($CNF['FIELD_ALLOW_POST_TO']) && !empty($aContentInfo[$CNF['FIELD_ALLOW_POST_TO']]) && ($oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_POST'])) !== false)
            $oPrivacy->reassociateGroupCustomWithContent($oProfile->id(), $iContentId, (int)$aContentInfo[$CNF['FIELD_ALLOW_POST_TO']]);

        return '';
    }

    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        /*
         * Add account and content association.
         * Note. It should be done first to correctly get and use author's profile later.
         */
        $iProfileId = BxDolProfile::add(BX_PROFILE_ACTION_MANUAL, $iAccountId, $iContentId, BX_PROFILE_STATUS_PENDING, $this->_oModule->getName());
        $oProfile = BxDolProfile::getInstance($iProfileId);

        if($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        $CNF = &$this->_oModule->_oConfig->CNF;

        /*
         * Load updated data.
         */
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

        // approve profile if auto-approval is enabled and profile status is 'pending'
        $sStatus = $oProfile->getStatus();
        if ($sStatus == BX_PROFILE_STATUS_PENDING && $this->isAutoApproval())
            $oProfile->approve(BX_PROFILE_ACTION_AUTO, 0, $this->_oModule->serviceActAsProfile() && $this->_oModule->serviceIsEnableProfileActivationLetter());

        // set created profile some default membership
        if ((int)bx_get('level_id') && bx_srv('bx_acl', 'get_prices', [(int)bx_get('level_id'), true]))
            $iAclLevel = (int)bx_get('level_id');
        else
            $iAclLevel = !isset($CNF['PARAM_DEFAULT_ACL_LEVEL']) ? MEMBERSHIP_ID_STANDARD : 
               (isAdmin() ? MEMBERSHIP_ID_ADMINISTRATOR : getParam($CNF['PARAM_DEFAULT_ACL_LEVEL']));
        BxDolAcl::getInstance()->setMembership($iProfileId, $iAclLevel, 0, true);
        
        // process uploaded files
        $oForm = $this->getObjectFormAdd();
        if (isset($CNF['FIELD_PICTURE']))
            $oForm->processFiles($CNF['FIELD_PICTURE'], $iContentId, true);
        if (isset($CNF['FIELD_COVER']))
            $oForm->processFiles($CNF['FIELD_COVER'], $iContentId, true);

        if(isset($CNF['FIELD_ALLOW_POST_TO']) && !empty($aContentInfo[$CNF['FIELD_ALLOW_POST_TO']]) && ($oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_POST'])) !== false)
            $oPrivacy->associateGroupCustomWithContent($oProfile->id(), $iContentId, (int)$aContentInfo[$CNF['FIELD_ALLOW_POST_TO']]);

        // switch context to the created profile
        if ($this->_oModule->serviceActAsProfile()) {
            $oAccount = BxDolAccount::getInstance($iAccountId);
            $oAccount->updateProfileContext($iProfileId);
        }

        return '';
    }

    public function redirectAfterAdd($aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oSession = BxDolSession::getInstance();

        $sRedirectType = empty($CNF['PARAM_REDIRECT_AADD']) ? BX_DOL_PROFILE_REDIRECT_PROFILE : getParam($CNF['PARAM_REDIRECT_AADD']);
        $sRedirectDefault = 'page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']];

        $sRedirectUrl = $sRedirectDefault;
        switch ($sRedirectType) {
            case BX_DOL_PROFILE_REDIRECT_PROFILE:
                break;

            // if user just joined the redirect to the page where user comes from. 
            case BX_DOL_PROFILE_REDIRECT_LAST:
                $sJoinReferrer = $oSession->getValue('join-referrer');
                if($sJoinReferrer) {
                    $sRedirectUrl = $sJoinReferrer;
                    $oSession->unsetValue('join-referrer');
                }
                break;

            case BX_DOL_PROFILE_REDIRECT_CUSTOM:
                $sRedirectCustom = getParam($CNF['PARAM_REDIRECT_AADD_CUSTOM_URL']);
                if($sRedirectCustom) {
                    $sRedirectUrl = $this->prepareCustomRedirectUrl($sRedirectCustom, $aContentInfo);
                }
        }

        $sCustomReferrer = $oSession->getValue('custom-referrer');
        if($sCustomReferrer) {
            $sRedirectUrl = $sCustomReferrer;
            $oSession->unsetValue('custom-referrer');
        }

        if($sRedirectUrl) {
            if(!bx_has_proto($sRedirectUrl))
                $sRedirectUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($sRedirectUrl);

            header('Location: ' . $sRedirectUrl);
            exit;
        }

        parent::redirectAfterAdd($aContentInfo);
    }
}

/** @} */
