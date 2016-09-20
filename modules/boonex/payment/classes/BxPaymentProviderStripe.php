<?php use Symfony\Component\Finder\Tests\FakeAdapter\DummyAdapter;
defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Payment Payment
 * @ingroup     TridentModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_PLUGINS . 'stripe/init.php');

define('STRP_MODE_LIVE', 1);
define('STRP_MODE_TEST', 2);

class BxPaymentProviderStripe extends BxBaseModPaymentProvider implements iBxBaseModPaymentProvider
{
	protected $_aIncludeJs;
	protected $_aIncludeCss;

	protected $_iMode;
	protected $_bCheckAmount;

	protected $_oCustomer;

    function __construct($aConfig)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);

        $this->_bRedirectOnResult = false;
        $this->_iMode = (int)$this->getOption('mode');
        $this->_bCheckAmount = $this->getOption('check_amount') == 'on'; 
        $this->_bUseSsl = $this->getOption('ssl') == 'on';
        $this->_sLogFile = BX_DIRECTORY_PATH_LOGS . 'bx_pp_' . $this->_sName . '.log';

        $this->_aIncludeJs = array(
        	'https://checkout.stripe.com/checkout.js',
        	'main.js',
        	'stripe.js'
        );
        $this->_aIncludeCss = array(
        	'stripe.css'
        );

        $this->_oCustomer = null;

        \Stripe\Stripe::setApiKey($this->_getSecretKey());
    }

	public function addJsCss()
    {
    	if(!$this->isActive())
    		return;

		$this->_oModule->_oTemplate->addJs($this->_aIncludeJs);
		$this->_oModule->_oTemplate->addCss($this->_aIncludeCss);
    }

    public function initializeCheckout($iPendingId, $aCartInfo)
    {
    	$sToken = bx_process_input(bx_get('token'));

    	if(empty($aCartInfo['items']) || !is_array($aCartInfo['items']))
    		return $this->_sLangsPrefix . 'err_empty_items';

		$aClient = $this->_oModule->getProfileInfo();
		$aVendor = $this->_oModule->getProfileInfo($aCartInfo['vendor_id']);

		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
		if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return $this->_sLangsPrefix . 'err_already_processed';

		switch($aPending['type']) {
			case BX_PAYMENT_TYPE_SINGLE:
				$aCartInfo['items_title'] = '';
				foreach($aCartInfo['items'] as $aItem)
		            $aCartInfo['items_title'] .= ' ' . $aItem['title'] . ',';
				$aCartInfo['items_title'] = trim($aCartInfo['items_title'], ', ');

				$mixedResult = $this->_createCharge($sToken, $iPendingId, $aClient, $aCartInfo);
				if($mixedResult === false)
					return $this->_sLangsPrefix . 'err_cannot_perform';

				header("Location: " . $this->getReturnDataUrl($aVendor['id'], array(
					'order_id' => $mixedResult['order'],
					'customer_id' => $mixedResult['customer'], 
					'pending_id' => $aPending['id']
				)));
				exit;

			case BX_PAYMENT_TYPE_RECURRING:
				$mixedResult = $this->_createSubscription($sToken, $iPendingId, $aClient, $aCartInfo);
				if($mixedResult === false)
					return $this->_sLangsPrefix . 'err_cannot_perform';

				return array(
					'code' => 0,
					'eval' => $this->_oModule->_oConfig->getJsObject('cart') . '.onSubscribeSubmit(oData);',
					'redirect' => $this->getReturnDataUrl($aVendor['id'], array(
						'order_id' => $mixedResult['order'],
						'customer_id' => $mixedResult['customer'],
						'pending_id' => $aPending['id']
					))
				);
		}
    }

    public function finalizeCheckout(&$aData)
    {
    	$sOrderId = bx_process_input($aData['order_id']);
    	$sCustomerId = bx_process_input($aData['customer_id']);
		$iPendingId = bx_process_input($aData['pending_id'], BX_DATA_INT);
        if(empty($iPendingId))
        	return array('code' => 1, 'message' => $this->_sLangsPrefix . 'err_wrong_data');

		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return array('code' => 3, 'message' => $this->_sLangsPrefix . 'err_already_processed');

		$aResult = array(
			'code' => BX_PAYMENT_RESULT_SUCCESS,
        	'message' => '',
			'pending_id' => $iPendingId,
			'client_name' => '',
			'client_email' => '',
			'paid' => false
		);

		switch($aPending['type']) {
			case BX_PAYMENT_TYPE_SINGLE:
				$aCustomer = $this->_retrieveCustomer($sCustomerId)->jsonSerialize();
				$aCharge = $this->_retrieveCharge($sOrderId)->jsonSerialize();
				if(empty($aCustomer) || !is_array($aCustomer) || empty($aCharge) || !is_array($aCharge))
					return array('code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform');

				$aResult = array_merge($aResult, array(
					'message' => $this->_sLangsPrefix . 'strp_msg_charged',
					'client_email' => $aCustomer['email'],
					'paid' => (bool)$aCharge['paid']
				));
				break;

			case BX_PAYMENT_TYPE_RECURRING:
				$aCustomer = $this->_retrieveCustomer($sCustomerId)->jsonSerialize();
				$aSubscription = $this->_retrieveSubscription($sCustomerId, $sOrderId)->jsonSerialize();
				if(empty($aCustomer) || !is_array($aCustomer) || empty($aSubscription) || !is_array($aSubscription))
					return array('code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform');

				$aResult = array_merge($aResult, array(
					'message' => $this->_sLangsPrefix . 'strp_msg_subscribed',
					'client_email' => $aCustomer['email']
				));
				break;
		}

        //--- Update pending transaction ---//
        $this->_oModule->_oDb->updateOrderPending($iPendingId, array(
            'order' => $sOrderId,
            'error_code' => $aResult['code'],
            'error_msg' => _t($aResult['message'])
        ));

        return $aResult;
    }

    public function notify()
    {
		$iResult = $this->_processEvent();
		http_response_code($iResult);
    }

    public function getButtonSingle($iClientId, $iVendorId, $aParams = array())
    {
    	$aItems = array();
    	$aCartInfo = $this->_oModule->getObjectCart()->getInfo(BX_PAYMENT_TYPE_SINGLE, $iClientId, (int)$iVendorId, $aItems);

    	return $this->_getButton(BX_PAYMENT_TYPE_SINGLE, $iClientId, $iVendorId, array_merge($aParams, array(
			'sVendorName' => _t($this->_sLangsPrefix . 'txt_payment_to', $aCartInfo['vendor_name']),
			'sVendorCurrency' => $aCartInfo['vendor_currency_code'],
			'sVendorIcon' => $aCartInfo['vendor_icon'],
    	)));
    }

    public function getButtonRecurring($iClientId, $iVendorId, $aParams = array())
    {
    	$aVendor = $this->_oModule->getVendorInfo((int)$iVendorId);

		return $this->_getButton(BX_PAYMENT_TYPE_RECURRING, $iClientId, $iVendorId, array_merge($aParams, array(
			'sVendorName' => _t($this->_sLangsPrefix . 'txt_payment_to', $aVendor['name']),
			'sVendorCurrency' => $aVendor['currency_code'],
			'sVendorIcon' => $aVendor['icon'],
		)));
    }

    public function getCheckoutParamsSingle($aParams, &$oGrid)
    {
    	if(bx_get('token') !== false)
    		$aParams['token'] = bx_process_input(bx_get('token'));

    	return $aParams;
    }

	protected function _getPublicKey()
	{
		return $this->_iMode == STRP_MODE_LIVE ? $this->getOption('live_pub_key') : $this->getOption('test_pub_key');
	}

	protected function _getSecretKey()
	{
		return $this->_iMode == STRP_MODE_LIVE ? $this->getOption('live_sec_key') : $this->getOption('test_sec_key');
	}

	protected function _createCustomer($sToken, $aClient) {
		try {
			$aClientParams = array(
				'card'  => $sToken,
				'email' => !empty($aClient['email']) ? $aClient['email'] : ''
			);

			$this->_oCustomer = \Stripe\Customer::create($aClientParams);
		}
		catch (Stripe\Error\Base $oException) {
			return $this->_processException('Create Customer Error: ', $oException);
		}

		return $this->_oCustomer->jsonSerialize();
	}

	protected function _retrieveCustomer($sId) {
		try {
			$oCustomer = \Stripe\Customer::retrieve($sId);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Customer Error: ', $oException);
		}

		return $oCustomer;
	}

	protected function _createCharge($sToken, $iPendingId, &$aClient, &$aCartInfo) {
		if(empty($this->_oCustomer))
			$this->_createCustomer($sToken, $aClient);

		if(empty($this->_oCustomer))
			return false;

		$fAmount = 100 * (float)$aCartInfo['items_price'];

		try {
			$oCharge = \Stripe\Charge::create(array(
				'customer' => $this->_oCustomer->id,
				'amount' => $fAmount,
				'currency' => $aCartInfo['vendor_currency_code'],
				'description' => $aCartInfo['items_title'],
				'metadata' => array(
					'vendor' => $aCartInfo['vendor_id'],
					'customer' => $aClient['id'],
					'product' => $iPendingId,
					'verification' => $this->_getVerificationCodeCharge($aCartInfo['vendor_id'], $aClient['id'], $fAmount, $aCartInfo['vendor_currency_code'])
				)
			));
		}
		catch (Exception $oException) {
			return $this->_processException('Create Charge Error: ', $oException);
		}

		$aResult = $oCharge->jsonSerialize();
		if(empty($aResult) || !is_array($aResult) || empty($aResult['paid']))
			return false;

		$aMetadata = $aResult['metadata'];
		if(empty($aMetadata['verification']) || $aMetadata['verification'] != $this->_getVerificationCodeCharge($aMetadata['vendor'], $aMetadata['customer'], $aResult['amount'], $aResult['currency']))
			return false;

		return array(
			'pending' => $iPendingId,
			'amount' =>(float)$aResult['amount'] / 100,
			'customer' => $this->_oCustomer->id,
			'order' => $aResult['id']
		);
	}

	protected function _retrieveCharge($sId) {
		try {
			$oCharge = \Stripe\Charge::retrieve($sId);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Charge Error: ', $oException);
		}

		return $oCharge;
	}

	protected function _createSubscription($sToken, $iPendingId, &$aClient, &$aCartInfo)
	{
		$bTrial = false;

		if(empty($this->_oCustomer))
			$this->_createCustomer($sToken, $aClient);

		if(empty($this->_oCustomer))
			return false;

		try {
			$aItem = array_shift($aCartInfo['items']);
			if(empty($aItem) || !is_array($aItem))
				return false;

			//TODO: "Trial" wasn't finaly relized. It's just a draft.
			if(isset($aItem['trial']) && $aItem['trial'] === true)
				$bTrial = true;

			$oSubscription = $this->_oCustomer->subscriptions->create(array(
				'plan' => $aItem['name'],
				'metadata' => array(
					'vendor' => $aCartInfo['vendor_id'],
					'customer' => $aClient['id'],
					'product' => $iPendingId,
					'verification' => $this->_getVerificationCodeSubscription($aCartInfo['vendor_id'], $aClient['id'], $aItem['name'], $aCartInfo['vendor_currency_code'])
				)
			));
		}
		catch (Exception $oException) {
			return $this->_processException('Create Subscription Error: ', $oException);
		}

		$aResult = $oSubscription->jsonSerialize();
		if(empty($aResult) || !is_array($aResult) || (!$bTrial && $aResult['status'] != 'active') || ($bTrial && !in_array($aResult['status'], array('active', 'trialing'))))
			return false;

		$aMetadata = $aResult['metadata'];
		if(empty($aMetadata['verification']) || $aMetadata['verification'] != $this->_getVerificationCodeSubscription($aCartInfo['vendor_id'], $aClient['id'], $aResult['plan']['id'], $aResult['plan']['currency']))
			return false;

		return array(
			'pending' => $iPendingId,
			'amount' =>(float)$aCartInfo['items_price'],
			'customer' => $this->_oCustomer->id, 
			'order' => $aResult['id'],
			'trial' => $bTrial
		);
	}

	protected function _retrieveSubscription($sCustomerId, $sSubscriptionId) {
		try {
			$oCustomer = \Stripe\Customer::retrieve($sCustomerId);
			$oSubscription = $oCustomer->subscriptions->retrieve($sSubscriptionId);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Subscription Error: ', $oException);
		}

		return $oSubscription;
	}

	public function _retrieveCoupon($sId) {
		try {
			$oCoupon = \Stripe\Coupon::retrieve($sId);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Coupon Error: ', $oException);
		}

		return $oCoupon;
	}

	public function _retrieveEvent($sId) {
		try {
			$oEvent = \Stripe\Event::retrieve($sId);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Event Error: ', $oException);
		}

		return $oEvent;
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

		$fChargeAmount = (float)$oCharge->amount / 100;
		$sChargeCurrency = strtoupper($oCharge->currency);
		if($this->_bCheckAmount && ((float)$aPending['amount'] != $fChargeAmount || strcasecmp($this->_oModule->_oConfig->getDefaultCurrencyCode(), $sChargeCurrency) != 0))
			return false;

		return $this->_oModule->registerPayment($aPending);
	}

	protected function _processEventChargeRefunded(&$aEvent)
	{
		$mixedResult = $this->_getData($aEvent);
		if($mixedResult === false)
			return false;

		list($aPending) = $mixedResult;
		return $this->_oModule->refundPayment($aPending);
	}

	protected function _processEventCustomerSubscriptionDeleted(&$aEvent)
	{
		$mixedResult = $this->_getData($aEvent);
		if($mixedResult === false)
			return false;

		list($aPending) = $mixedResult;
		return $this->_oModule->cancelSubscription($aPending);
	}

	protected function _processException($sMessage, &$oException)
	{
		$aError = $oException->getJsonBody();

		$sMessage = $aError['error']['message'];
		if(empty($sMessage))
			$sMessage = $oException->getMessage();

		$this->log($sMessage . $aError['error']['message']);
		$this->log($aError);

		return false;
	}

	protected function _getData(&$aEvent, $bRetrieve = true)
	{
		if($bRetrieve)
			$oEvent = $this->_retrieveEvent($aEvent['id']);
		else 
			$oEvent = \Stripe\Util\Util::convertToStripeObject($aEvent, array());

		if(empty($oEvent))
			return false;

		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'order', 'order' => $oEvent->data->object->subscription));
		if(empty($aPending) || !is_array($aPending))
			return false;

		$oCharge = $this->_retrieveCharge($oEvent->data->object->charge);
		if(empty($oCharge))
			return false;

		return array($aPending, $oCharge);
	}

	protected function _getVerificationCodeCharge($iVendorId, $iCustomerId, $fAmount, $sCurrency) {
		return md5(implode('#-#', array(
			(int)$iVendorId,
			(int)$iCustomerId,
			(float)$fAmount,
			strtoupper($sCurrency)
		)));
	}

	protected function _getVerificationCodeSubscription($iVendorId, $iCustomerId, $sSubscription, $sCurrency) {
		return md5(implode('#-#', array(
			(int)$iVendorId,
			(int)$iCustomerId,
			strtoupper($sSubscription),
			strtoupper($sCurrency)
		)));
	}

    protected function _getButton($sType, $iClientId, $iVendorId, $aParams = array())
    {
    	$sClientEmail = '';
    	if(!empty($iClientId)) {
    		$oClient = BxDolProfile::getInstance($iClientId);
    		if($oClient)
    			$sClientEmail = $oClient->getAccountObject()->getEmail();
    	}

    	return $this->_oModule->_oTemplate->parseHtmlByName('strp_button_' . $sType . '.html', array(
    		'type' => $sType,
    		'caption' => _t($this->_sLangsPrefix . 'strp_txt_checkout_with_' . $sType, $this->_sCaption),
    		'js_object' => $this->_oModule->_oConfig->getJsObject($this->_sName),
    		'js_code' => $this->_oModule->_oTemplate->getJsCode($this->_sName, array_merge(array(
	    		'sProvider' => $this->_sName,
	    		'sPublicKey' => $this->_getPublicKey(),
	    		'sVendorName' => '',
	    		'sVendorCurrency' => '',
	    		'sVendorIcon' => '',
	    		'sClientEmail' => $sClientEmail,
	    	), $aParams))
    	));
    }
}

/** @} */
