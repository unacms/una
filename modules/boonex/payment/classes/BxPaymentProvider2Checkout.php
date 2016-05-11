<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Payment Payment
 * @ingroup     TridentModules
 *
 * @{
 */

define('TUCO_MODE_LIVE', 1);
define('TUCO_MODE_TEST', 2);

define('TUCO_PAYMENT_METHOD_CC', 'CC');
define('TUCO_PAYMENT_METHOD_CK', 'CK');
define('TUCO_PAYMENT_METHOD_AL', 'AL');
define('TUCO_PAYMENT_METHOD_PPI', 'PPI');

class BxPaymentProvider2Checkout extends BxBaseModPaymentProvider implements iBxBaseModPaymentProvider
{
    protected $_sDataReturnUrl;

    function __construct($aConfig)
    {
		$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);

        $this->_bRedirectOnResult = true;
        $this->_sDataReturnUrl = $this->_oModule->_oConfig->getUrl('URL_RETURN_DATA') . $this->_sName . '/';
    }

    public function initializeCheckout($iPendingId, $aCartInfo)
    {
    	$bTest = (int)$this->getOption('mode') == TUCO_MODE_TEST;

        $aFormData = array(
            'sid' => $this->getOption('account_id'),
        	'mode' => '2CO',
        	'demo' => $bTest ? 'Y' : '',
        	'merchant_order_id' => $iPendingId,
            'total' => sprintf("%.2f", (float)$aCartInfo['items_price']),
        	'currency_code' => $aCartInfo['vendor_currency_code'],
            'pay_method' => $this->getOption('payment_method'),
            'x_receipt_link_url' => $this->_sDataReturnUrl . $aCartInfo['vendor_id']
        );

        $iIndex = 0;
        $sPriceKey = $this->_oModule->_oConfig->getKey('KEY_ARRAY_PRICE_SINGLE');
        foreach($aCartInfo['items'] as $aItem) {
        	$aFormData['li_' . $iIndex . '_type'] = 'product';
            $aFormData['li_' . $iIndex . '_name'] = $aItem['title'];
            $aFormData['li_' . $iIndex . '_price'] = $aItem[$sPriceKey];
            $aFormData['li_' . $iIndex . '_quantity'] = $aItem['quantity'];
            $aFormData['li_' . $iIndex . '_tangible'] = 'N';

            $iIndex++;
        }

        $sActionURL = 'https://' . ($bTest ? 'sandbox' : 'www') . '.2checkout.com/checkout/purchase';
        $this->_oModule->_oTemplate->displayPageCodeRedirect($sActionURL, $aFormData);
        exit;
    }

    public function finalizeCheckout(&$aData)
    {
        return $this->_registerCheckout($aData);
    }

    /**
     *
     * @param $aData - data from payment provider.
     * @param $bSubscription - Is not needed. May be used in the future for subscriptions.
     * @param $iPendingId - Is not needed. May be used in the future for subscriptions.
     * @return array with results.
     */
    protected function _registerCheckout(&$aData, $bSubscription = false, $iPendingId = 0)
    {
        if(empty($this->_aOptions) && isset($aData['merchant_order_id']))
            $this->_aOptions = $this->getOptionsByPending($aData['merchant_order_id']);

        if(empty($this->_aOptions))
            return array('code' => 2, 'message' => _t('_payment_2co_err_no_vendor_given'));

        $aResult = $this->_validateCheckout($aData);
        if(empty($aResult['pending_id']))
            return $aResult;

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => (int)$aResult['pending_id']));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return array('code' => 6, 'message' => _t('_payment_2co_err_already_processed'));

        $this->_oModule->_oDb->updateOrderPending((int)$aResult['pending_id'], array(
            'order' => $aData['order_number'],
            'error_code' => $aResult['code'],
            'error_msg' => $aResult['message']
        ));
        return $aResult;
    }

    protected function _validateCheckout(&$aData)
    {
        if(empty($aData['order_number']) || empty($aData['total']) || empty($aData['key']) || empty($aData['merchant_order_id']))
            return array('code' => 3, 'message' => _t('_payment_2co_err_no_data_given'));

        $sOrder = bx_process_input($aData['order_number'], BX_TAGS_STRIP);
        $sAmount = bx_process_input($aData['total'], BX_TAGS_STRIP);
        $iPendingId = (int)$aData['merchant_order_id'];

        if($aData['key'] != $this->_generateKey($sOrder, $sAmount))
            return array('code' => 4, 'message' => _t('_payment_2co_err_wrong_key'), 'pending_id' => $iPendingId);

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if((float)$sAmount != (float)$aPending['amount'])
            return array('code' => 5, 'message' => _t('_payment_2co_err_wrong_payment'), 'pending_id' => $iPendingId);

        return array(
        	'code' => BX_PAYMENT_RESULT_SUCCESS, 
        	'message' => _t('_payment_2co_msg_verified'), 
        	'pending_id' => $iPendingId,
        	'client_name' => _t('_payment_txt_buyer_name_mask', bx_process_input($aData['first_name']), bx_process_input($aData['last_name'])),
        	'client_email' => bx_process_input($aData['email']),
        	'paid' => true
        );
    }

    protected function _generateKey($sOrder, $sAmount)
    {
    	if((int)$this->getOption('mode') == TUCO_MODE_TEST)
    		$sOrder = '1';

        $sKey = $this->getOption('secret_word') . $this->getOption('account_id') . $sOrder . $sAmount;
        return strtoupper(md5($sKey));
    }
}

/** @} */
