<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
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

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
            return array(false, false);

        $oProfile = BxDolProfile::getInstanceMagic($aContentInfo[$CNF['FIELD_AUTHOR']]);
        return array($oProfile, $aContentInfo);
    }

    public function onDataEditAfter ($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        if ($s = parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm))
            return $s;

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId)))
            return MsgBox(_t('_sys_txt_error_occured'));

        if (isset($CNF['FIELD_VIDEO']))
            $oForm->processFiles($CNF['FIELD_VIDEO'], $iContentId, false);

        if (isset($CNF['FIELD_SOUND']))
            $oForm->processFiles($CNF['FIELD_SOUND'], $iContentId, false);

        if (isset($CNF['FIELD_FILE']))
            $oForm->processFiles($CNF['FIELD_FILE'], $iContentId, false);

        if (isset($CNF['FIELD_POLL']))
            $oForm->processPolls($CNF['FIELD_POLL'], $iContentId);
        
        if (isset($CNF['FIELD_LINK']))
            $oForm->processLinks($CNF['FIELD_LINK'], $iContentId);

        // change profile to 'pending' only if profile is 'active'
        if ($oProfile->isActive() && !empty($aTrackTextFieldsChanges['changed_fields']))
            $oProfile->disapprove(BX_PROFILE_ACTION_AUTO);

        return '';
    }

    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        if ($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId)))
            return MsgBox(_t('_sys_txt_error_occured'));

        if(($oForm = $this->getObjectFormAdd()) !== false) {
            if (isset($CNF['FIELD_VIDEO']))
                $oForm->processFiles($CNF['FIELD_VIDEO'], $iContentId, true);

            if (isset($CNF['FIELD_SOUND']))
                $oForm->processFiles($CNF['FIELD_SOUND'], $iContentId, true);

            if (isset($CNF['FIELD_FILE']))
                $oForm->processFiles($CNF['FIELD_FILE'], $iContentId, true);

            if (isset($CNF['FIELD_POLL']))
                $oForm->processPolls($CNF['FIELD_POLL'], $iContentId);
            
            if (isset($CNF['FIELD_LINK']))
                $oForm->processLinks($CNF['FIELD_LINK'], $iContentId);
        }

        return '';
    }

    public function onDataDeleteAfter($iContentId, $aContentInfo, $oProfile)
    {
        $sResult = parent::onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile);
        if(!empty($sResult))
            return $sResult;

        $this->_oModule->_oDb->deletePolls(array('content_id' => $iContentId));
        BxDolCategories::getInstance()->delete($this->_oModule->getName(), $iContentId);

        return '';
    }
}

/** @} */
