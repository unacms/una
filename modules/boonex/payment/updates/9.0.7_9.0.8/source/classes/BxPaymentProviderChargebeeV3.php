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

require_once('BxPaymentProviderChargebee.php');

class BxPaymentProviderChargebeeV3 extends BxPaymentProviderChargebee
{
    function __construct($aConfig)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);

        $this->_aIncludeJs = array(
        	'https://js.chargebee.com/v2/chargebee.js',
        	'main.js',
        	'chargebee_v3.js'
        );

        $this->_aIncludeCss = array(
        	'chargebee_v3.css'
        );
    }

    public function actionGetHostedPage($iClientId, $iVendorId, $sItemName)
    {
        $this->setOptionsByVendor($iVendorId);

        $aClient = $this->_oModule->getProfileInfo($iClientId);
	    $oPage = $this->createHostedPage(array('name' => $sItemName), $aClient);
		if($oPage === false)
		    return echoJson(array());

        header('Content-type: text/html; charset=utf-8');
        echo $oPage->toJson();
    }

    public function addJsCss()
    {
    	if(!$this->isActive())
    		return;

        $this->_oModule->_oTemplate->addJs($this->_aIncludeJs);
        $this->_oModule->_oTemplate->addCss($this->_aIncludeCss);
    }

    public function initializeCheckout($iPendingId, $aCartInfo, $sRedirect = '')
    {
        $sPageId = bx_process_input(bx_get('page_id'));
        if(empty($sPageId) || empty($iPendingId))
        	return $this->_sLangsPrefix . 'err_wrong_data';

    	$aItem = array_shift($aCartInfo['items']);
    	if(empty($aItem) || !is_array($aItem))
    		return $this->_sLangsPrefix . 'err_empty_items';

		$aClient = $this->_oModule->getProfileInfo();
		$aVendor = $this->_oModule->getProfileInfo($aCartInfo['vendor_id']);

		$oPage = $this->retreiveHostedPage($sPageId);
		if($oPage === false)
			return $this->_sLangsPrefix . 'err_cannot_perform';

		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
		if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return $this->_sLangsPrefix . 'err_already_processed';

        if($aPending['type'] != BX_PAYMENT_TYPE_RECURRING) 
            return $this->_sLangsPrefix . 'err_wrong_data';

		$oCustomer = $oPage->content()->customer();
		$oSubscription = $oPage->content()->subscription();

		return array(
			'code' => 0,
			'eval' => $this->_oModule->_oConfig->getJsObject('cart') . '.onSubscribeSubmit(oData);',
			'redirect' => $this->getReturnDataUrl($aVendor['id'], array(
				'order_id' => $oSubscription->id,
				'customer_id' => $oCustomer->id,
				'pending_id' => $aPending['id'],
				'redirect' => $sRedirect
			))
		);
    }

    public function finalizeCheckout(&$aData)
    {
        $sOrderId = bx_process_input($aData['order_id']);
    	$sCustomerId = bx_process_input($aData['customer_id']);
		$iPendingId = bx_process_input($aData['pending_id'], BX_DATA_INT);
        if(empty($iPendingId))
        	return array('code' => 1, 'message' => $this->_sLangsPrefix . 'err_wrong_data');

        $sRedirect = bx_process_input($aData['redirect']);

		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return array('code' => 3, 'message' => $this->_sLangsPrefix . 'err_already_processed');

        if($aPending['type'] != BX_PAYMENT_TYPE_RECURRING) 
            return array('code' => 1, 'message' => $this->_sLangsPrefix . 'err_wrong_data');

		$oCustomer = $this->retrieveCustomer($sCustomerId);
		$oSubscription = $this->retrieveSubscription($sOrderId);
		if($oCustomer === false || $oSubscription === false)
            return array('code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform');

		$aResult = array(
			'code' => BX_PAYMENT_RESULT_SUCCESS,
        	'message' => $this->_sLangsPrefix . 'cbee_msg_subscribed',
			'pending_id' => $iPendingId,
			'customer_id' => $oCustomer->id,
		    'subscription_id' => $oSubscription->id,
			'client_name' => _t($this->_sLangsPrefix . 'txt_buyer_name_mask', $oCustomer->firstName, $oCustomer->lastName),
			'client_email' => $oCustomer->email,
			'paid' => false,
			'trial' => false,
			'redirect' => $sRedirect
		);

        //--- Update pending transaction ---//
        $this->_oModule->_oDb->updateOrderPending($iPendingId, array(
            'order' => $oSubscription->id,
            'error_code' => $aResult['code'],
            'error_msg' => _t($aResult['message'])
        ));

        return $aResult;
    }

    /**
     * Single tome payments aren't available with Chargebee
     */
    public function getButtonSingle($iClientId, $iVendorId, $aParams = array())
    {
        return '';
    }

    public function getButtonRecurring($iClientId, $iVendorId, $aParams = array())
    {
		return $this->_getButton(BX_PAYMENT_TYPE_RECURRING, $iClientId, $iVendorId, $aParams);
    }

    protected function _getButton($sType, $iClientId, $iVendorId, $aParams = array())
    {
    	$sSite = '';
    	bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_get_button', 0, $iClientId, array(
			'type' => &$sType, 
			'site' => &$sSite,
    	    'params' => &$aParams
		));

    	return $this->_oModule->_oTemplate->parseHtmlByName('cbee_v3_button_' . $sType . '.html', array(
    		'type' => $sType,
    	    'link' => 'javascript:void(0)',
    		'caption' => _t($this->_sLangsPrefix . 'cbee_txt_checkout_with_' . $sType, $this->_sCaption),
    		'js_object' => $this->_oModule->_oConfig->getJsObject($this->_sName),
    		'js_code' => $this->_oModule->_oTemplate->getJsCode($this->_sName, array_merge(array(
	    		'sProvider' => $this->_sName,
	    		'sSite' => !empty($sSite) ? $sSite : $this->_getSite(),
    	        'iClientId' => $iClientId
	    	), $aParams))
    	));
    }
}

/** @} */
