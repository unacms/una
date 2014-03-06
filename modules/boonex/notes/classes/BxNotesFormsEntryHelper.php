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
class BxNotesFormsEntryHelper extends BxDolProfileForms 
{
    protected $_oModule;

    public function __construct($oModule) 
    {
        parent::__construct();
        $this->_oModule = $oModule;
    }

    /**
     * @return add data html
     */
    public function addDataForm () 
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedAdd()))
            return MsgBox($sMsg);

        // check and display form
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD']);
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

        if (!($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId)))
            return MsgBox(_t('_sys_txt_error_occured'));

        // perform action 
        $this->_oModule->checkAllowedAdd(true);

        // alert
        bx_import('BxDolPrivacy');
        bx_alert($this->_oModule->getName(), 'added', $iContentId, false, array('privacy_view' => $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]));

        // redirect 
        $this->_redirectAndExit('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId);
    }

    /**
     * @return edit data html
     */
    public function editDataForm ($iContentId, $sDisplay = false) 
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (false === $sDisplay) 
            $sDisplay = $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'];

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedEdit($aContentInfo)))
            return MsgBox($sMsg);
        
        // check and display form 
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sDisplay); 
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker($aContentInfo); 

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        // update data in the DB
        $aTrackTextFieldsChanges = null;
        if (!$oForm->update ($aContentInfo[$CNF['FIELD_ID']], array(), $aTrackTextFieldsChanges)) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_sys_txt_error_entry_update')); 
        }

        if (!($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId)))
            return MsgBox(_t('_sys_txt_error_occured'));

        // change profile to 'pending' only if profile is 'active'
        if ($oProfile->isActive() && !empty($aTrackTextFieldsChanges['changed_fields']))
            $oProfile->disapprove(BX_PROFILE_ACTION_AUTO);

        // perform action
        $this->_oModule->checkAllowedEdit($aContentInfo, true);

        // create an alert
        bx_import('BxDolPrivacy');
        bx_alert($this->_oModule->getName(), 'edited', $aContentInfo[$CNF['FIELD_ID']], false, array('privacy_view' => $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']])); 

        // redirect 
        $this->_redirectAndExit('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
    }


    /**
     * @return delete data html
     */
    public function deleteDataForm ($iContentId, $sDisplay = false) 
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (false === $sDisplay) 
            $sDisplay = $CNF['OBJECT_FORM_ENTRY_DISPLAY_DELETE'];

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedDelete($aContentInfo)))
            return MsgBox($sMsg);
        
        // check and display form 
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sDisplay);
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker($aContentInfo); 

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        if (!$oForm->delete ($aContentInfo[$CNF['FIELD_ID']])) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_sys_txt_error_entry_delete'));
        }

        // perform action
        $this->_oModule->checkAllowedDelete($aContentInfo, true);

        // create an alert
        bx_alert($this->_oModule->getName(), 'deleted', $aContentInfo[$CNF['FIELD_ID']]);

        // redirect 
        $this->_redirectAndExit('page.php?i=' . $CNF['URI_HOME']);
    }


    /**
     * @return view data html
     */
    public function viewDataForm ($iContentId) 
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($aContentInfo)))
            return MsgBox($sMsg);
        
        // get form 
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_VIEW']); 
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        // perform action
        $this->_oModule->checkAllowedView($aContentInfo);

        // display profile
        $oForm->initChecker($aContentInfo);
        return $oForm->getCode();
    }

    /**
     * @return main content text 
     */
    public function viewDataText ($iContentId) 
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($aContentInfo)))
            return MsgBox($sMsg);

        return $aContentInfo[$CNF['FIELD_TEXT']];
    }

    /**
     * @return main content text 
     */
    public function viewDataEntry ($iContentId) 
    {
        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined')); 

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($aContentInfo)))
            return MsgBox($sMsg);

        return $this->_oModule->_oTemplate->entryText($aContentInfo);
    }


    /**
     * @return array of profile object, profile info and content info
     */
    protected function _getProfileAndContentData ($iContentId) 
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aContentInfo = array();
        $oProfile = false;
        
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return array (false, false);

        $oProfile = BxDolProfile::getInstance($aContentInfo[$CNF['FIELD_AUTHOR']]);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }

        return array ($oProfile, $aContentInfo);
    }

}

/** @} */
