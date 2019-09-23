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

        $oForm = BxDolForm::getObjectInstance($this->_sObjectNameForFormAdd, $sDisplay, $this->_oModule->_oTemplate);
        if($this->_bAjaxMode)
            $oForm->setAjaxMode($this->_bAjaxMode);

        if($this->_bAbsoluteActionUrl)
            $this->_setAbsoluteActionUrl('add', $oForm);

        return $oForm;
    }
    
    protected function addDataFormAction ($sDisplay = false, $sCheckFunction = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $bAsJson = false;

        // get form object
        $oForm = $this->getObjectFormAdd();
        if (!$oForm)
            return $this->prepareResponse(MsgBox(_t('_sys_txt_error_occured')), $bAsJson, 'msg');

        $bAsJson = $this->_bAjaxMode && $oForm->isSubmitted();

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedAdd()))
            return $this->prepareResponse(MsgBox($sMsg), $bAsJson, 'msg');       

        // check and display form
        $oForm->initChecker();
        if (!$oForm->isSubmittedAndValid())
            return $this->prepareResponse($oForm->getCode($this->_bDynamicMode), $bAsJson, 'form', array(
            	'form_id' => $oForm->getId()
            ));

        // insert data into database
        $aValsToAdd = array ();
        $aContentIds = $oForm->insert ($aValsToAdd);
        if (false === $aContentIds || !is_array($aContentIds)) {
            if (!$oForm->isValid() || !is_array($aContentIds))
                return $this->prepareResponse($oForm->getCode($this->_bDynamicMode), $bAsJson, 'form', array(
                	'form_id' => $oForm->getId()
                ));
            else
                return $this->prepareResponse(MsgBox(_t('_sys_txt_error_entry_creation')), $bAsJson, 'msg');
        }

        foreach ($aContentIds as $iContentId) {
            $sResult = $this->onDataAddAfter (getLoggedId(), $iContentId);
            if ($sResult)
                return $sResult;

            if (!($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId)))
                return MsgBox(_t('_sys_txt_error_occured'));

            // Create alert about the completed action.
            $this->_oModule->alertAfterAdd($aContentInfo);
        }
        
        return array('need_redirect_after_action' => true, 'content_ids_array' => $aContentIds);
    }
}

/** @} */
