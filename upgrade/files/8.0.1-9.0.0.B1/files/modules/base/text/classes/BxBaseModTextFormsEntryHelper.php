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
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        return array ($oProfile, $aContentInfo);
    }

    public function onDataEditAfter ($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        if ($s = parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm))
            return $s;

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId)))
            return MsgBox(_t('_sys_txt_error_occured'));

        // change profile to 'pending' only if profile is 'active'
        if ($oProfile->isActive() && !empty($aTrackTextFieldsChanges['changed_fields']))
            $oProfile->disapprove(BX_PROFILE_ACTION_AUTO);

        // alert
        $aParams = array('object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']]);
        if(isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]))
        	$aParams['privacy_view'] = $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']];

        bx_alert($this->_oModule->getName(), 'edited', $aContentInfo[$CNF['FIELD_ID']], false, $aParams);

        return '';
    }

    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        if ($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId)))
            return MsgBox(_t('_sys_txt_error_occured'));

        // alert
        $aParams = array('object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']]);
        if(isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]))
        	$aParams['privacy_view'] = $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']];

        bx_alert($this->_oModule->getName(), 'added', $iContentId, false, $aParams);

        return '';
    }

}

/** @} */
