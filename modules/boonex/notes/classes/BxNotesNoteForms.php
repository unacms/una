<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolProfileForms');
bx_import('BxDolProfile');
bx_import('BxDolForm');

/**
 * Note forms functions
 */
class BxNotesNoteForms extends BxDolProfileForms {

    protected $_oModule;

    public function __construct($oModule) {
        parent::__construct();
        $this->_oModule = $oModule;
    }

    /**
     * @return add data html
     */
    public function addDataForm () {

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedAdd()))
            return MsgBox($sMsg);

        // check and display form
        $oForm = BxDolForm::getObjectInstance('bx_notes', 'bx_notes_note_add'); 
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
                return MsgBox(_t('_bx_notes_txt_error_note_creation'));
        }

/* TODO: status
        // approve note if auto-approval is enabled and profile status is 'pending'
        $sStatus = $oProfile->getStatus();
        $isAutoApprove = getParam('bx_notes_autoapproval') ? true : false;
        if ($sStatus == BX_PROFILE_STATUS_PENDING && $isAutoApprove)
            $oProfile->approve(BX_PROFILE_ACTION_AUTO);
*/
        // perform action 
        $this->_oModule->isAllowedAdd(true);

        // alert
        //TODO: Pass a valid Note's privacy view group.
        bx_import('BxDolPrivacy');
        bx_alert($this->_oModule->getName(), 'added', $iContentId, false, array('privacy_view' => BX_DOL_PG_ALL));

        // redirect 
        $this->_redirectAndExit('page.php?i=view-note&id=' . $iContentId);
    }

    /**
     * @return edit data html
     */
    public function editDataForm ($iContentId, $sDisplay = 'bx_notes_note_edit') {

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_bx_notes_txt_error_note_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedEdit($aContentInfo)))
            return MsgBox($sMsg);
        
        // check and display form 
        $oForm = BxDolForm::getObjectInstance('bx_notes', $sDisplay); 
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker($aContentInfo); 

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        // update data in the DB
        $aTrackTextFieldsChanges = null;
/* TODO: status
        $isAutoApprove = getParam('bx_notes_autoapproval') ? true : false;
        if (!$isAutoApprove && $oProfile->isActive()))
            $aTrackTextFieldsChanges = array ();
*/
        if (!$oForm->update ($aContentInfo['id'], array(), $aTrackTextFieldsChanges)) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_bx_notes_txt_error_note_update')); 
        }

        // change profile to 'pending' only if profile is 'active'
        if (/*!$isAutoApprove &&*/ $oProfile->isActive() && !empty($aTrackTextFieldsChanges['changed_fields']))
            $oProfile->disapprove(BX_PROFILE_ACTION_AUTO);

        // perform action
        $this->_oModule->isAllowedEdit($aContentInfo, true);

        // create an alert
        //TODO: Pass a valid Note's privacy view group.
        bx_import('BxDolPrivacy');
        bx_alert($this->_oModule->getName(), 'edited', $aContentInfo['id'], false, array('privacy_view' => BX_DOL_PG_MEMBERS)); 

        // redirect 
        $this->_redirectAndExit('page.php?i=view-note&id=' . $aContentInfo['id']);
    }


    /**
     * @return delete data html
     */
    public function deleteDataForm ($iContentId, $sDisplay = 'bx_notes_note_delete') {

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_bx_notes_txt_error_note_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedDelete($aContentInfo)))
            return MsgBox($sMsg);
        
        // check and display form 
        $oForm = BxDolForm::getObjectInstance('bx_notes', $sDisplay); 
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker($aContentInfo); 

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        if (!$oForm->delete ($aContentInfo['id'])) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_bx_notes_txt_error_note_delete'));
        }

        // perform action
        $this->_oModule->isAllowedDelete($aContentInfo, true);

        // create an alert
        bx_alert($this->_oModule->getName(), 'deleted', $aContentInfo['id']);

        // redirect 
        $this->_redirectAndExit('page.php?i=notes-home');
    }


    /**
     * @return view data html
     */
    public function viewDataForm ($iContentId) {

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_bx_notes_txt_error_note_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedView($aContentInfo)))
            return MsgBox($sMsg);
        
        // get form 
        $oForm = BxDolForm::getObjectInstance('bx_notes', 'bx_notes_note_view'); 
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        // perform action
        $this->_oModule->isAllowedView($aContentInfo);

        // display profile
        $oForm->initChecker($aContentInfo);
        return $oForm->getCode();
    }

    /**
     * @return main content text 
     */
    public function viewDataText ($iContentId) {

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_bx_notes_txt_error_note_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedView($aContentInfo)))
            return MsgBox($sMsg);

        return $aContentInfo[BxNotesConfig::$FIELD_TEXT];
    }

    /**
     * @return main content text 
     */
    public function viewDataEntry ($iContentId) {

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_bx_notes_txt_error_note_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedView($aContentInfo)))
            return MsgBox($sMsg);

        return $this->_oModule->_oTemplate->entryText($aContentInfo);
    }


    /**
     * @return array of profile object, profile info and content info
     */
    protected function _getProfileAndContentData ($iContentId) {
    
        $aContentInfo = array();
        $oProfile = false;
        
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return array (false, false, false);

        $oProfile = BxDolProfile::getInstance($aContentInfo['author']);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }

        return array ($oProfile, $aContentInfo);
    }

}

/** @} */
