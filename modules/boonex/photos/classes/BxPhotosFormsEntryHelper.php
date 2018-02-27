<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxPhotosFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }
    
    public function getObjectFormAdd ($sDisplay = false)
    {
        if (false === $sDisplay)
            $sDisplay = 'bx_photos_entry_upload';

        return BxDolForm::getObjectInstance('bx_photos_upload', $sDisplay, $this->_oModule->_oTemplate);
    }
    
    public function addDataForm ($sDisplay = false, $sCheckFunction = false)
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
        $this->_redirectAndExit(BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']));
    }
}

/** @} */
