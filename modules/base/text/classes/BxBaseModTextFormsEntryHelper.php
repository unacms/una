<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModGeneralFormsEntryHelper');
bx_import('BxDolProfile');

/**
 * Entry forms helper functions
 */
class BxBaseModTextFormsEntryHelper extends BxBaseModGeneralFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
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
     * @return array of profile object and content info
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

    protected function onDataEditAfter ($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId)))
            return MsgBox(_t('_sys_txt_error_occured'));

        // change profile to 'pending' only if profile is 'active'
        if ($oProfile->isActive() && !empty($aTrackTextFieldsChanges['changed_fields']))
            $oProfile->disapprove(BX_PROFILE_ACTION_AUTO);

        // create an alert
        bx_import('BxDolPrivacy');
        bx_alert($this->_oModule->getName(), 'edited', $aContentInfo[$CNF['FIELD_ID']], false, array('privacy_view' => $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]));

        return '';
    }

    protected function onDataAddAfter ($iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId)))
            return MsgBox(_t('_sys_txt_error_occured'));

        // alert
        bx_import('BxDolPrivacy');
        $aParams = isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]) ? array('privacy_view' => $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]) : array();
        bx_alert($this->_oModule->getName(), 'added', $iContentId, false, $aParams);

        return '';
    }

}

/** @} */
