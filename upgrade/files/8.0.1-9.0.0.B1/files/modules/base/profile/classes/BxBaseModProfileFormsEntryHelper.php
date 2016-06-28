<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     TridentModules
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
        $this->setAutoApproval(isset($oModule->_oConfig->CNF['PARAM_AUTOAPPROVAL']) ? (bool)getParam($oModule->_oConfig->CNF['PARAM_AUTOAPPROVAL']) : true);
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
        $aContentInfo = array();
        $oProfile = false;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return array (false, false);

        $oProfile = BxDolProfile::getInstance($aContentInfo['profile_id']);
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        return array ($oProfile, $aContentInfo);
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

    public function onDataEditBefore ($iContentId, $aContentInfo, &$aTrackTextFieldsChanges)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        $aProfileInfo = $oProfile->getInfo();

        if (!$this->isAutoApproval() && BX_PROFILE_STATUS_ACTIVE == $aProfileInfo['status'])
            $aTrackTextFieldsChanges = array ();
    }

    public function onDataEditAfter ($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        if ($s = parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm))
            return $s;

        $CNF = &$this->_oModule->_oConfig->CNF;

        $aProfileInfo = $oProfile->getInfo();

        // change profile to 'pending' only if profile is 'active'
        if (!$this->isAutoApproval() && BX_PROFILE_STATUS_ACTIVE == $aProfileInfo['status'] && !empty($aTrackTextFieldsChanges['changed_fields']))
            $oProfile->disapprove(BX_PROFILE_ACTION_AUTO, 0, $this->_oModule->serviceActAsProfile());

        // process uploaded files
        $oForm->processFiles($CNF['FIELD_PICTURE'], $iContentId, false);
        $oForm->processFiles($CNF['FIELD_COVER'], $iContentId, false);

        // create an alert
        bx_alert($this->_oModule->getName(), 'edited', $aContentInfo[$CNF['FIELD_ID']]);

        return '';
    }

    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        if ($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        $CNF = &$this->_oModule->_oConfig->CNF;

        // add account and content association
        $iProfileId = BxDolProfile::add(BX_PROFILE_ACTION_MANUAL, $iAccountId, $iContentId, BX_PROFILE_STATUS_PENDING, $this->_oModule->getName());
        $oProfile = BxDolProfile::getInstance($iProfileId);

        // approve profile if auto-approval is enabled and profile status is 'pending'
        $sStatus = $oProfile->getStatus();
        if ($sStatus == BX_PROFILE_STATUS_PENDING && $this->isAutoApproval())
            $oProfile->approve(BX_PROFILE_ACTION_AUTO, 0, $this->_oModule->serviceActAsProfile());

        // set created profile some default membership
        $iAclLevel = !isset($CNF['PARAM_DEFAULT_ACL_LEVEL']) ? MEMBERSHIP_ID_STANDARD : 
            (isAdmin() ? MEMBERSHIP_ID_ADMINISTRATOR : getParam($CNF['PARAM_DEFAULT_ACL_LEVEL']));
        BxDolAcl::getInstance()->setMembership($iProfileId, $iAclLevel, 0, true);

        // process uploaded files
        $oForm = $this->getObjectFormAdd();
        $oForm->processFiles($CNF['FIELD_PICTURE'], $iContentId, true);
        $oForm->processFiles($CNF['FIELD_COVER'], $iContentId, true);

        // alert
        bx_alert($this->_oModule->getName(), 'added', $iContentId);

        // switch context to the created profile
        if ($this->_oModule->serviceActAsProfile()) {
            $oAccount = BxDolAccount::getInstance($iAccountId);
            $oAccount->updateProfileContext($iProfileId);
        }

        return '';
    }

}

/** @} */
