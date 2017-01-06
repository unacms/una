<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxFilesFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    public function getObjectFormAdd ()
    {
        return BxDolForm::getObjectInstance('bx_files_upload', 'bx_files_entry_upload', $this->_oModule->_oTemplate);
    }
    
    public function addDataForm ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedAdd()))
            return MsgBox($sMsg);

        // check and display form
        $oForm = $this->getObjectFormAdd();
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker();

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        // insert data into database
        $aValsToAdd = array ();
        $aContentIds = $oForm->insert ($aValsToAdd);
        if (false === $aContentIds || !is_array($aContentIds)) {
            if (!$oForm->isValid() || !is_array($aContentIds))
                return $oForm->getCode();
            else
                return MsgBox(_t('_sys_txt_error_entry_creation'));
        }

        foreach ($aContentIds as $iContentId) {
            $sResult = $this->onDataAddAfter (getLoggedId(), $iContentId);
            if ($sResult)
                return $sResult;

            // perform action
            $this->_oModule->checkAllowedAdd(true);
        }

        // redirect
        $this->_redirectAndExit('page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id=' . bx_get_logged_profile_id());
    }
}

/** @} */
