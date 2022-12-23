<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPaymentResponse extends BxDolAlertsResponse
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();

        $this->_sModule = 'bx_payment';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
        if(method_exists($this, $sMethod))
            return $this->$sMethod($oAlert);

    	if($oAlert->sUnit != 'profile' || !in_array($oAlert->sAction, ['join', 'delete']))
            return;

        switch($oAlert->sAction) {
            case 'join':
                $this->_oModule->onProfileJoin($oAlert->iObject);
                break;

            case 'delete':
                $this->_oModule->onProfileDelete($oAlert->iObject);
                break;
        }
    }

    protected function _processSystemSaveSetting($oAlert)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if($oAlert->aExtras['option'] != $CNF['PARAM_CURRENCY_CODE'])
            return;
        
        if(strcmp($oAlert->aExtras['value'], $oAlert->aExtras['value_prior']) == 0)
            return;

        $this->_oModule->updateCurrencyExchangeRates();
    }
}

/** @} */
