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
        $iResult = $this->_processNotification();
        http_response_code($iResult);
    }
    
    protected function _processNotification()
    {
    	$sInput = @file_get_contents("php://input");
        $aNotification = json_decode($sInput, true);
        if(empty($aNotification) || !is_array($aNotification)) 
            return 404;

        $sType = $aNotification['notification_type'];
        if(!in_array($sType, array('INITIAL_BUY', 'DID_RENEW', 'REFUND', 'CANCEL')))
            return 200;

        $this->log($aNotification, 'Webhooks: ' . (!empty($sType) ? $sType : ''));

        $sMethod = '_processNotification' . bx_gen_method_name(strtolower($sType), array('.', '_', '-'));
    	if(!method_exists($this, $sMethod))
            return 200;

    	return $this->$sMethod($aNotification) ? 200 : 403;
    }

    protected function _processNotificationInitialBuy($aNotification)
    {
        $sProduct = $aNotification['auto_renew_product_id'];

        //TODO: We need to create 'Pending Transaction' to register this and all future payments.

        /*
        $iPendingId = $this->_oDb->insertOrderPending($this->_iUserId, BX_PAYMENT_TYPE_RECURRING, $this->_sName, array(
            'author_id' => 0,
            'module_id' => 0,
            'id' => 0,
            'quantity' => 1
        ));
         */
    }

    protected function _processNotificationDidRenew(&$aNotification)
    {
        $sProduct = $aNotification['auto_renew_product_id'];

        //TODO: We need to get 'PendingId' to register payment.
  
        /*
        if($aPending['type'] == BX_PAYMENT_TYPE_RECURRING)
            $this->_oModule->getObjectSubscriptions()->prolong($aPending);

        return $this->_oModule->registerPayment($aPending);
         */
    }

    protected function _processNotificationRefund(&$aNotification)
    {
        $sProduct = $aNotification['auto_renew_product_id'];

        //TODO: We need to get 'PendingId' to process refund.
    }

    protected function _processNotificationCancel(&$aNotification)
    {
        $sProduct = $aNotification['auto_renew_product_id'];

        //TODO: We need to get 'PendingId' to cancel the subscription.
    }
}

/** @} */
