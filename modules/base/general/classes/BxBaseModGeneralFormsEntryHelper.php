<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolProfileForms');
bx_import('BxDolForm');

/**
 * Entry forms helper functions
 */
class BxBaseModGeneralFormsEntryHelper extends BxDolProfileForms
{
    protected $_oModule;

    public function __construct($oModule)
    {
        parent::__construct();
        $this->_oModule = $oModule;
    }

    public function addDataForm ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedAdd()))
            return MsgBox($sMsg);

        // check and display form
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'], $this->_oModule->_oTemplate);
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

        $sResult = $this->onDataAddAfter ($iContentId);
        if ($sResult)
            return $sResult;

        // perform action
        $this->_oModule->checkAllowedAdd(true);

        // redirect
        $this->_redirectAndExit('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId);
    }

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
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sDisplay, $this->_oModule->_oTemplate);
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $aSpecificValues = array();
        bx_import('BxDolMetatags');
        if (!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            $aSpecificValues = $oMetatags->locationGet($iContentId, $CNF['FIELD_LOCATION_PREFIX']);
        }
        $oForm->initChecker($aContentInfo, $aSpecificValues);

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        // update data in the DB
        $aTrackTextFieldsChanges = null;

        $this->onDataEditBefore ($aContentInfo[$CNF['FIELD_ID']], $aContentInfo, $aTrackTextFieldsChanges);

        if (!$oForm->update ($aContentInfo[$CNF['FIELD_ID']], array(), $aTrackTextFieldsChanges)) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_sys_txt_error_entry_update'));
        }

        $sResult = $this->onDataEditAfter ($aContentInfo[$CNF['FIELD_ID']], $aContentInfo, $aTrackTextFieldsChanges, $oProfile);
        if ($sResult)
            return $sResult;

        // perform action
        $this->_oModule->checkAllowedEdit($aContentInfo, true);

        // redirect
        $this->_redirectAndExit('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
    }

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
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sDisplay, $this->_oModule->_oTemplate);
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker($aContentInfo);

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        if ($sError = $this->deleteData($aContentInfo[$CNF['FIELD_ID']], $aContentInfo, $oProfile, $oForm))
            return MsgBox($sError);

        // perform action
        $this->_oModule->checkAllowedDelete($aContentInfo, true);

        // redirect
        bx_import('BxDolPermalinks');
        $this->_redirectAndExit($CNF['URL_HOME'], true, array(
            'account_id' => $oProfile->getAccountId(),
            'profile_id' => $oProfile->id(),
        ));
    }

    /**
     * Delete data entry
     * @param $iContentId entry id
     * @param $oForm optional content info array
     * @param $aContentInfo optional content info array
     * @param $oProfile optional content author profile
     * @return error string on error or empty string on success
     */
    public function deleteData ($iContentId, $aContentInfo = false, $oProfile = null, $oForm = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!$aContentInfo || !$oProfile)
            list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);

        if (!$aContentInfo)
            return _t('_sys_txt_error_entry_is_not_defined');

        if (!$oForm)
            $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_DELETE'], $this->_oModule->_oTemplate);

        if (!$oForm->delete ($aContentInfo[$CNF['FIELD_ID']], $aContentInfo))
            return _t('_sys_txt_error_entry_delete');

        if ($sResult = $this->onDataDeleteAfter ($aContentInfo[$CNF['FIELD_ID']], $aContentInfo, $oProfile))
            return $sResult;

        // create an alert
        bx_alert($this->_oModule->getName(), 'deleted', $aContentInfo[$CNF['FIELD_ID']]);

        return '';
    }

    public function viewDataForm ($iContentId, $sDisplay = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (false === $sDisplay)
            $sDisplay = $CNF['OBJECT_FORM_ENTRY_DISPLAY_VIEW'];

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined'));

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($aContentInfo)))
            return MsgBox($sMsg);

        // get form
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sDisplay, $this->_oModule->_oTemplate);
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        // display profile
        $oForm->initChecker($aContentInfo);
        return $oForm->getCode();
    }

}

/** @} */
