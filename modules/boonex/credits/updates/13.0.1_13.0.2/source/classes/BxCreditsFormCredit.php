<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Credit form
 */
class BxCreditsFormCredit extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    protected $_fRate;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_credits';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_fRate = $this->_oModule->_oConfig->getConversionRateWithdraw();

        if(isset($this->aInputs[$CNF['FIELD_C_MESSAGE']])) {
            $sInfo = '_bx_credits_form_credit_input_message_inf_';
            switch($this->aParams['display']) {
                case $CNF['OBJECT_FORM_CREDIT_DISPLAY_GRANT']:
                    $sInfo .= 'g';
                    break;

                case $CNF['OBJECT_FORM_CREDIT_DISPLAY_SEND']:
                    $sInfo .= 's';
                    break;

                case $CNF['OBJECT_FORM_CREDIT_DISPLAY_WITHDRAW_REQUEST']:
                    $sInfo .= 'wr';
                    break;
                
                case $CNF['OBJECT_FORM_CREDIT_DISPLAY_WITHDRAW_CONFIRM']:
                    $sInfo .= 'wc';
                    break;
            }

            $this->aInputs[$CNF['FIELD_C_MESSAGE']]['info'] = _t($sInfo);
        }
    }

    protected function genCustomRowRate(&$aInput)
    {
        if($this->_fRate == 1)
            return '';

        return $this->genRowStandard($aInput);
    }

    protected function genCustomRowResult(&$aInput)
    {
        if($this->_fRate == 1)
            return '';

        return $this->genRowStandard($aInput);
    }

    protected function genCustomInputBalance(&$aInput)
    {
        $aInput['value'] = $this->_oModule->getProfileBalance();

        return $this->genInputStandard($aInput);
    }

    protected function genCustomInputCleared(&$aInput)
    {
        $aInput['value'] = $this->_oModule->getProfileBalanceCleared();

        return $this->genInputStandard($aInput);
    }

    protected function genCustomInputRate(&$aInput)
    {
        $aInput['value'] = $this->_fRate;
        $aInput['attrs']['id'] = $this->_oModule->_oConfig->getHtmlIds('withdraw_field_rate');
        return $this->genInputStandard($aInput);
    }

    protected function genCustomInputAmount(&$aInput)
    {
        if($this->_fRate != 1)
            $aInput['attrs']['onblur'] = $this->_oModule->_oConfig->getJsObject('withdraw') . '.getResult(this)';

        $aInput['attrs']['id'] = $this->_oModule->_oConfig->getHtmlIds('withdraw_field_amount');
        return $this->genInputStandard($aInput);
    }

    protected function genCustomInputResult(&$aInput)
    {
        $aInput['attrs']['id'] = $this->_oModule->_oConfig->getHtmlIds('withdraw_field_result');
        return $this->genInputStandard($aInput);
    }

    protected function genCustomInputProfile(&$aInput)
    {
        if(empty($aInput['custom']) || !is_array($aInput['custom']))
            $aInput['custom'] = array();
        $aInput['custom']['only_once'] = 1;

        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . "get_profiles";

        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
}

/** @} */
