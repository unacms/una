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
    protected $_sSecret;

    protected $_sVerifyReceipt;
    protected $_sVerifyReceiptSandbox;

    public function __construct($aConfig)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);

        $this->_sVerifyReceipt = 'https://buy.itunes.apple.com/verifyReceipt';
        $this->_sVerifyReceiptSandbox = 'https://sandbox.itunes.apple.com/verifyReceipt';
    }

    public function initOptions($aOptions)
    {
    	parent::initOptions($aOptions);

        $this->_sSecret = $this->getOption('secret');
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

    public function getMenuItemsActionsRecurring($iClientId, $iVendorId, $aParams = array())
    {
        if(empty($aParams['id']))
            return array();

        $sPrefix = 'bx-payment-aina-';

        return array(
            array('id' => $sPrefix . 'cancel', 'name' => $sPrefix . 'cancel', 'class' => '', 'link' => 'https://apps.apple.com/account/subscriptions', 'target' => '_blank', 'title' => _t('_bx_payment_aina_menu_item_title_cancel'))
        );
    }

    protected function _processNotification()
    {
        $sPath = "php://input"; //BX_DIRECTORY_PATH_TMP . "aina_renew.json"; // "aina_init.json";
    	$sInput = @file_get_contents($sPath);
        $aNotification = json_decode($sInput, true);
        if(empty($aNotification) || !is_array($aNotification)) 
            return 404;

        $sType = $aNotification['notification_type'];
        if(!in_array($sType, array('INITIAL_BUY_CUSTOM', 'DID_RENEW', 'REFUND', 'CANCEL')))
            return 200;

        $this->log($aNotification, 'Webhooks: ' . (!empty($sType) ? $sType : ''));

        $sMethod = '_processNotification' . bx_gen_method_name(strtolower($sType), array('.', '_', '-'));
    	if(!method_exists($this, $sMethod))
            return 200;

    	return $this->$sMethod($aNotification) ? 200 : 403;
    }

    protected function _processNotificationInitialBuyCustom($aNotification)
    {
        if(!$this->_verifyNotification(array('rt' => $aNotification['original_data']['transactionReceipt'], 'pt' => $aNotification['product'], 'tx' => $aNotification['transaction'], 'txo' => $aNotification['original_transaction'])))
            return false;

        $sOrder = $aNotification['transaction'];
        if(!empty($aNotification['original_transaction']))
            $sOrder = $aNotification['original_transaction'];
        $sOrder = bx_process_input($sOrder);

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'order', 'order' => $sOrder));
        if(!empty($aPending) && is_array($aPending) && (int)$aPending['processed'] == 1)
            return false;

        $sModule = bx_process_input($aNotification['module']);
        $aModule = $this->_oModule->_oDb->getModuleByName($sModule);
        if(empty($aModule) || !is_array($aModule))
            return false;

        $iClient = (int)$aNotification['profile_id'];

        $aInfo = $this->_oModule->getObjectCart()->getInfo(BX_PAYMENT_TYPE_RECURRING, $iClient, $this->_iVendor, array(
            array($this->_iVendor, $aModule['id'], bx_process_input($aNotification['product']), (int)$aNotification['count'])
        ));

        if(empty($aInfo) || $aInfo['vendor_id'] == BX_PAYMENT_EMPTY_ID || empty($aInfo['items']))
            return false;

        $iPendingId = $this->_oModule->_oDb->insertOrderPending($iClient, BX_PAYMENT_TYPE_RECURRING, $this->_sName, $aInfo);
        if(empty($iPendingId))
            return false;

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));

        $aSubscription = array(
            'customer_id' => '', 
            'subscription_id' => $sOrder,
            'status' => BX_PAYMENT_SBS_STATUS_ACTIVE
        );

        if(!$this->_oModule->getObjectSubscriptions()->register($aPending, $aSubscription))
            return false;

        $this->_oModule->_oDb->updateOrderPending($iPendingId, array(
            'order' => $sOrder,
            'error_code' => 0,
            'error_msg' => _t('_bx_payment_aina_msg_subscribed')
        ));

        return $this->_oModule->registerPayment($iPendingId);
    }

    protected function _processNotificationDidRenew(&$aNotification)
    {
        $aRenew = array_shift($aNotification['unified_receipt']['pending_renewal_info']);

        if(!$this->_verifyNotification(array('rt' => $aNotification['unified_receipt']['latest_receipt'], 'pt' => $aRenew['product_id'], 'txo' => $aRenew['original_transaction_id'])))
            return false;

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'order', 'order' => $aRenew['original_transaction_id']));
        if(empty($aPending) || !is_array($aPending))
            return false;
  
        if($aPending['type'] == BX_PAYMENT_TYPE_RECURRING)
            $this->_oModule->getObjectSubscriptions()->prolong($aPending);

        return $this->_oModule->registerPayment($aPending);
    }

    protected function _processNotificationRefund(&$aNotification)
    {
        //TODO: We need to get 'PendingId' to process refund.
    }

    protected function _processNotificationCancel(&$aNotification)
    {
        //TODO: We need to get 'PendingId' to cancel the subscription.
    }

    protected function _verifyNotification($aParams)
    {
        $aRequest = array(
            'receipt-data' => $aParams['rt'],
            'password' => $this->_sSecret
        );

        $sInput = bx_file_get_contents($this->_sVerifyReceipt, $aRequest, 'post-json');
        $aInput = json_decode($sInput, true);

        if(isset($aInput['status']) && (int)$aInput['status'] == 21007) {
            $sInput = bx_file_get_contents($this->_sVerifyReceiptSandbox, $aRequest, 'post-json');
            $aInput = json_decode($sInput, true);
        }

        if(!empty($aInput['latest_receipt_info']) && is_array($aInput['latest_receipt_info']))
            foreach($aInput['latest_receipt_info'] as $aRecipt)
                if($aRecipt['product_id'] == $aParams['pt'] && ((isset($aParams['txo']) && $aRecipt['original_transaction_id'] == $aParams['txo']) || (isset($aParams['tx']) && $aRecipt['transaction_id'] == $aParams['tx'])))
                    return true;

        return false;
    }
}

/** @} */
