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

class BxPaymentProviderAppleInApp extends BxBaseModPaymentProvider implements iBxBaseModPaymentProvider
{
    function __construct($aConfig)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);
    }

    public function initOptions($aOptions)
    {
    	parent::initOptions($aOptions);
    }

    public function initializeCheckout($iPendingId, $aCartInfo, $sRedirect = '')
    {
    	return $this->_sLangsPrefix . 'err_not_supported';
    }

    public function finalizeCheckout(&$aData)
    {
        return array('code' => 1, 'message' => $this->_sLangsPrefix . 'err_not_supported');
    }

    public function notify()
    {
        $iResult = $this->_processEvent();
        http_response_code($iResult);
    }
    
    protected function _processEvent()
    {
    	$sInput = @file_get_contents("php://input");
        $aEvent = json_decode($sInput, true);
        if(empty($aEvent) || !is_array($aEvent)) 
                return 404;

        $sType = $aEvent['type'];
        if(!in_array($sType, array('invoice.payment_succeeded', 'charge.refunded', 'customer.subscription.deleted')))
                return 200;

        $this->log('Webhooks: ' . (!empty($sType) ? $sType : ''));
        $this->log($aEvent);

        $sMethod = '_processEvent' . bx_gen_method_name($sType, array('.', '_', '-'));
    	if(!method_exists($this, $sMethod))
            return 200;

    	return $this->$sMethod($aEvent) ? 200 : 403;
    }

    protected function _processEventInvoicePaymentSucceeded(&$aEvent)
    {
        $mixedResult = $this->_getData($aEvent);
        if($mixedResult === false)
            return false;

        list($aPending, $oCharge) = $mixedResult;
        if(empty($aPending) || !is_array($aPending) || empty($oCharge))
            return false;

        $fChargeAmount = (float)$oCharge->amount / 100;
        $sChargeCurrency = strtoupper($oCharge->currency);
        if($this->_bCheckAmount && ((float)$aPending['amount'] != $fChargeAmount || strcasecmp($this->_oModule->_oConfig->getDefaultCurrencyCode(), $sChargeCurrency) !== 0))
            return false;

        if($aPending['type'] == BX_PAYMENT_TYPE_RECURRING)
            $this->_oModule->getObjectSubscriptions()->prolong($aPending);

        return $this->_oModule->registerPayment($aPending);
    }
}

/** @} */
