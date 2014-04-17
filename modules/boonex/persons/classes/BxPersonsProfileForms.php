<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolProfileForms');
bx_import('BxDolProfile');
bx_import('BxDolForm');

/**
 * Person profile forms functions
 */
class BxPersonsProfileForms extends BxDolProfileForms {

    protected $_oModule;

    public function __construct($oModule) {
        parent::__construct();
        $this->_oModule = $oModule;
    }

    public function addDataForm () {

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedAdd()))
            return MsgBox($sMsg);

        // check and display form
        $oForm = BxDolForm::getObjectInstance('bx_person', 'bx_person_add'); 
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker(); 

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        // insert data into database
        $aValsToAdd = array ();        
        $iContentId = $oForm->insert ($aValsToAdd);
        if (!$iContentId) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_sys_txt_error_entry_creation'));
        }

        // add account and content association
        $iProfileId = BxDolProfile::add(BX_PROFILE_ACTION_MANUAL, getLoggedId(), $iContentId, BX_PROFILE_STATUS_PENDING, $this->_oModule->getName());
        $oProfile = BxDolProfile::getInstance($iProfileId);

        // approve profile if auto-approval is enabled and profile status is 'pending'
        $sStatus = $oProfile->getStatus();
        $isAutoApprove = getParam('bx_persons_autoapproval') ? true : false;
        if ($sStatus == BX_PROFILE_STATUS_PENDING && $isAutoApprove)
            $oProfile->approve(BX_PROFILE_ACTION_AUTO);

        // set created profile some default membership
        bx_import('BxDolAcl');
        $iAclLevel = getParam('bx_persons_default_acl_level');
        BxDolAcl::getInstance()->setMembership($iProfileId, $iAclLevel, 0, true); 

        // perform action 
        $this->_oModule->isAllowedAdd(true);

        // alert
        bx_alert($this->_oModule->getName(), 'added', $iContentId);

        // switch context to the created profile
        bx_import('BxDolAccount');
        $oAccount = BxDolAccount::getInstance();
        $oAccount->updateProfileContext($iProfileId);

        // redirect 
        $this->_redirectAndExit('page.php?i=view-persons-profile&id=' . $iContentId);
    }

    public function editDataForm ($iContentId, $sDisplay = 'bx_person_edit') {

        // get content data and profile info
        list ($oProfile, $aProfileInfo, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedEdit($aContentInfo)))
            return MsgBox($sMsg);
        
        // check and display form 
        $oForm = BxDolForm::getObjectInstance('bx_person', $sDisplay); 
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker($aContentInfo); 

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        // update data in the DB
        $aTrackTextFieldsChanges = null;
        $isAutoApprove = getParam('bx_persons_autoapproval') ? true : false;
        if (!$isAutoApprove && BX_PROFILE_STATUS_ACTIVE == $aProfileInfo['status'])
            $aTrackTextFieldsChanges = array ();

        if (!$oForm->update ($aContentInfo['id'], array(), $aTrackTextFieldsChanges)) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_sys_txt_error_entry_update')); 
        }

        // change profile to 'pending' only if profile is 'active'
        if (!$isAutoApprove && BX_PROFILE_STATUS_ACTIVE == $aProfileInfo['status'] && !empty($aTrackTextFieldsChanges['changed_fields']))
            $oProfile->disapprove(BX_PROFILE_ACTION_AUTO);

        // perform action
        $this->_oModule->isAllowedEdit($aContentInfo, true);

        // create an alert
        bx_alert($this->_oModule->getName(), 'edited', $aContentInfo['id']); 

        // redirect 
        $this->_redirectAndExit('page.php?i=view-persons-profile&id=' . $aContentInfo['id']);
    }

    public function deleteDataForm ($iContentId) {

        // get content data and profile info
        list ($oProfile, $aProfileInfo, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedDelete($aContentInfo)))
            return MsgBox($sMsg);
        
        // check and display form 
        $oForm = BxDolForm::getObjectInstance('bx_person', 'bx_person_delete'); 
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker($aContentInfo); 

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        // delete avatar & avatar
        if (!$oForm->delete ($aContentInfo['id'], $aContentInfo))
            return MsgBox(_t('_sys_txt_error_entry_delete')); 

        // delete profile
        if (!$oProfile->delete($aContentInfo['profile_id']))
            return MsgBox(_t('_sys_txt_error_entry_delete')); 

        // perform action
        $this->_oModule->isAllowedDelete($aContentInfo, true);

        // create an alert
        bx_alert($this->_oModule->getName(), 'deleted', $aContentInfo['id']); 

        // redirect
        $this->_redirectAndExit('member.php', false); 
    }

    public function viewDataForm ($iContentId) {

        // get content data and profile info
        list ($oProfile, $aProfileInfo, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedView($aContentInfo)))
            return MsgBox($sMsg);
        
        // get form 
        $oForm = BxDolForm::getObjectInstance('bx_person', 'bx_person_view'); 
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        // perform action
        $this->_oModule->isAllowedView($aContentInfo);

        // display profile
        $oForm->initChecker($aContentInfo);
        return $oForm->getCode();
    }

    protected function _getProfileAndContentData ($iContentId) {
    
        $aContentInfo = array();
        $aProfileInfo = array();
        $oProfile = false;
        
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return array (false, false, false);

        $oProfile = BxDolProfile::getInstance($aContentInfo['profile_id']);
        $aProfileInfo = $oProfile->getInfo();

        return array ($oProfile, $aProfileInfo, $aContentInfo);
    }

}

/** @} */
