<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseFile Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxBaseModFilesFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    protected $_sDisplayForFormAdd;
    protected $_sObjectNameForFormAdd;
    
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }
    
    public function getObjectFormAdd ($sDisplay = false)
    {
        if (false === $sDisplay)
            $sDisplay = $this->_sDisplayForFormAdd;

        return BxDolForm::getObjectInstance($this->_sObjectNameForFormAdd, $sDisplay, $this->_oModule->_oTemplate);
    }
    
    protected function addDataFormAction ($sDisplay = false, $sCheckFunction = false)
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
        
        return array('needRedirectAfterAction' => true, 'ContentIds' => $aContentIds);
    }
}

/** @} */
