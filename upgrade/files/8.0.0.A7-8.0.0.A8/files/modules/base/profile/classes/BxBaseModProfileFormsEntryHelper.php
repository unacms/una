<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralFormsEntryHelper');
bx_import('BxDolProfile');

/**
 * Profile forms helper functions
 */
class BxBaseModProfileFormsEntryHelper extends BxBaseModGeneralFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    protected function _getProfileAndContentData ($iContentId)
    {
        $aContentInfo = array();
        $oProfile = false;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return array (false, false);

        $oProfile = BxDolProfile::getInstance($aContentInfo['profile_id']);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }

        return array ($oProfile, $aContentInfo);
    }

    protected function onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile)
    {
        // delete profile
        if (!$oProfile->delete($aContentInfo['profile_id']))
            return _t('_sys_txt_error_entry_delete');

        return '';
    }

    protected function onDataEditBefore ($iContentId, $aContentInfo, &$aTrackTextFieldsChanges)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        $aProfileInfo = $oProfile->getInfo();

        $isAutoApprove = getParam($CNF['PARAM_AUTOAPPROVAL']) ? true : false;
        if (!$isAutoApprove && BX_PROFILE_STATUS_ACTIVE == $aProfileInfo['status'])
            $aTrackTextFieldsChanges = array ();
    }

    protected function onDataEditAfter ($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aProfileInfo = $oProfile->getInfo();

        // change profile to 'pending' only if profile is 'active'
        $isAutoApprove = getParam($CNF['PARAM_AUTOAPPROVAL']) ? true : false;
        if (!$isAutoApprove && BX_PROFILE_STATUS_ACTIVE == $aProfileInfo['status'] && !empty($aTrackTextFieldsChanges['changed_fields']))
            $oProfile->disapprove(BX_PROFILE_ACTION_AUTO);

        // create an alert
        bx_alert($this->_oModule->getName(), 'edited', $aContentInfo[$CNF['FIELD_ID']]);
    }

    protected function onDataAddAfter ($iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // add account and content association
        $iProfileId = BxDolProfile::add(BX_PROFILE_ACTION_MANUAL, getLoggedId(), $iContentId, BX_PROFILE_STATUS_PENDING, $this->_oModule->getName());
        $oProfile = BxDolProfile::getInstance($iProfileId);

        // approve profile if auto-approval is enabled and profile status is 'pending'
        $sStatus = $oProfile->getStatus();
        $isAutoApprove = getParam($CNF['PARAM_AUTOAPPROVAL']) ? true : false;
        if ($sStatus == BX_PROFILE_STATUS_PENDING && $isAutoApprove)
            $oProfile->approve(BX_PROFILE_ACTION_AUTO);

        // set created profile some default membership
        bx_import('BxDolAcl');
        $iAclLevel = isAdmin() ? MEMBERSHIP_ID_ADMINISTRATOR : getParam($CNF['PARAM_DEFAULT_ACL_LEVEL']);
        BxDolAcl::getInstance()->setMembership($iProfileId, $iAclLevel, 0, true);

        // alert
        bx_alert($this->_oModule->getName(), 'added', $iContentId);

        // switch context to the created profile
        bx_import('BxDolAccount');
        $oAccount = BxDolAccount::getInstance();
        $oAccount->updateProfileContext($iProfileId);

        return '';
    }

}

/** @} */
