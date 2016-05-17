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

require_once(BX_DIRECTORY_PATH_PLUGINS . 'recurly/recurly.php');

define('RCRL_MODE_LIVE', 1);
define('RCRL_MODE_TEST', 2);

class BxPaymentProviderRecurly extends BxBaseModPaymentProvider implements iBxBaseModPaymentProvider
{
	protected $_aIncludeJs;
	protected $_aIncludeCss;

	protected $_sFormCard;
	protected $_sFormDisplayCardAdd;

	protected $_iMode;
	protected $_bCheckAmount;

    function __construct($aConfig)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);

        $this->_aIncludeJs = array(
        	'https://js.recurly.com/v4/recurly.js'
        );
        $this->_aIncludeCss = array(
        	'recurly.css'
        );

        $this->_sFormCard = 'bx_payment_form_rcrl_card';
        $this->_sFormDisplayCardAdd = 'bx_payment_form_rcrl_card_add';

        $this->_bRedirectOnResult = false;
        $this->_bUseSsl = true;
        $this->_sLogFile = BX_DIRECTORY_PATH_LOGS . 'bx_pp_' . $this->_sName . '.log';        

        $this->_iMode = (int)$this->getOption('mode');
        $this->_bCheckAmount = false; // Disabled for easier processing of discounted subscriptions.
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
    	$aItem = array_shift($aCartInfo['items']);
    	if(empty($aItem) || !is_array($aItem))
    		return $this->_sLangsPrefix . 'err_empty_items';

		$aClient = $this->_oModule->getProfileInfo();
		$aVendor = $this->_oModule->getProfileInfo($aCartInfo['vendor_id']);

		$mixedResult = $this->createCardForm($iPendingId, $aItem, $aClient, $aVendor);
		if($mixedResult === false)
			return $this->_sLangsPrefix . 'err_cannot_perform';

		return array_merge(array('code' => 0), $mixedResult);
    }

    public function finalizeCheckout(&$aData)
    {
    	$iPendingId = bx_process_input($aData['pending_id'], BX_DATA_INT);
    	$sItem = bx_process_input($aData['item']);
    	$sToken = bx_process_input($aData['token']);
    	$aClient = array(
    		'first_name' => bx_process_input($aData['first_name']),
    		'last_name' => bx_process_input($aData['last_name']),
    		'email' => bx_process_input($aData['email'])
    	);

        if(empty($sToken) || empty($iPendingId))
        	return array('code' => 1, 'message' => $this->_sLangsPrefix . 'pp_err_no_data_given');
		
		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return array('code' => 3, 'message' => $this->_sLangsPrefix . 'pp_err_already_processed');

		$oSubscription = $this->createSubscription($sToken, $sItem, $aClient);
		if($oSubscription === false)
			return $this->_sLangsPrefix . 'err_cannot_perform';

		/*
		 * TODO: Need to get subscription ID to save it in DB as Order
		 */
		$sSubscription = '';

		$aResult = array(
			'code' => BX_PAYMENT_RESULT_SUCCESS,
        	'message' => $this->_sLangsPrefix . 'rcrl_msg_subscribed',
			'pending_id' => $iPendingId,
			'client_name' => _t($this->_sLangsPrefix . 'txt_buyer_name_mask', $aClient['first_name'], $aClient['last_name']),
			'client_email' => $aClient['email'],
			'paid' => false
		);

        //--- Update pending transaction ---//
        $this->_oModule->_oDb->updateOrderPending($iPendingId, array(
            'order' => $sSubscription,
            'error_code' => $aResult['code'],
            'error_msg' => _t($aResult['message'])
        ));

        return $aResult;
    }

    public function notify()
    {
    	/*
    	 * TODO: Integrate necessary Webhook events. 
    	 */
		//$iResult = $this->_processEvent();
		//http_response_code($iResult);
    }

    public function createCardForm($iPendingId, $aItem, $aClient, $aVendor)
    {
		$oForm = BxDolForm::getObjectInstance($this->_sFormCard, $this->_sFormDisplayCardAdd, $this->_oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = bx_append_url_params($this->getReturnDataUrl($aVendor['id']), array(
			'pending_id' => $iPendingId
		));

        $oForm->aInputs['pending_id']['value'] = $iPendingId;
        $oForm->aInputs['item']['value'] = $aItem['name'];

		$aCardFields = array('card_number', 'card_cvv');
		foreach($aCardFields as $sCardField) {
			$oForm->aInputs[$sCardField]['content'] = $this->_oModule->_oTemplate->parseHtmlByName('rcrl_card_field.html', array(
				'attrs' => bx_convert_array2attrs($oForm->aInputs[$sCardField]['attrs'])
			));
		}
		$oForm->aInputs['card_expire']['content'] = $this->_oModule->_oTemplate->parseHtmlByName('rcrl_card_field_date.html', array(
			'attrs_month' => bx_convert_array2attrs(array('data-recurly' => 'month')),
			'attrs_year' => bx_convert_array2attrs(array('data-recurly' => 'year'))
		));

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
        	$sFormMethod = $oForm->aFormAttrs['method'];

        	/*
        	 * TODO: Need to reload form on internal error or update the code to work via this check and then redirect to Finalize.
        	 * Issue with "form reloading". Currently Recurly doesn't allow to "call payment form" multiple times (https://github.com/recurly/recurly-js/issues/239). 
        	 */ 
        }

        $aTmplConfig = BxTemplConfig::getInstance()->__get('aLessConfig');
        
        $sId = 'bx-payment-rcrl-card-add';
    	$sTitle = _t($this->_sLangsPrefix . 'popup_title_rcrl_card_add');
		$sContent = BxTemplStudioFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->parseHtmlByName('rcrl_card_popup_add.html', array(
			'public_key' => $this->getOption('api_key_public'),
			'font_family' => $aTmplConfig['bx-font-family'],
			'font_size' => $aTmplConfig['bx-font-size-default'],
			'font_color' => $aTmplConfig['bx-font-color-default'],
			'form_id' => $oForm->aFormAttrs['id'],
			'form' => $oForm->getCode(true),
		)));

		return array('popup' => array(
			'html' => $sContent, 
			'options' => array(
				'closeOnOuterClick' => false, 
				'onBeforeShow' => 'bxRecurlyFieldsInit();', 
				'onHide' => 'bxRecurlyFieldsDestroy();'
			)
		));
    }

	public function createSubscription($sToken, $sItem, $aClient)
	{
		$oSubscription = false;

		try {
			$oSubscription = new Recurly_Subscription();
			$oSubscription->plan_code = $sItem;

			$oSubscription->account = new Recurly_Account();
			$oSubscription->account->account_code = 'pat_smith';
			$oSubscription->account->first_name = $aClient['first_name'];
			$oSubscription->account->last_name = $aClient['last_name'];
			$oSubscription->account->email = $aClient['email'];

			$oSubscription->account->billing_info = new Recurly_BillingInfo();
			$oSubscription->account->billing_info->token_id = $sToken;

			$oSubscription->create();
		}
		catch (Exception $oException) {
			$iError = $oException->getCode();
			$sError = $oException->getMessage();

			$this->log('Create Subscription Error: ' . $sError . '(' . $iError . ')');

			return false;
		}

		return $oSubscription;
	}

	public function retrieveSubscription($sSubscriptionId)
	{
		$oSubscription = null;

		try {
			
		}
		catch (Exception $oException) {
			$iError = $oException->getCode();
			$sError = $oException->getMessage();

			$this->log('Retrieve Subscription Error: ' . $sError . '(' . $iError . ')');

			return false;
		}

		return $oSubscription;
	}

	public function deleteSubscription($sSubscriptionId)
	{
		try {

		}
		catch (Exception $oException) {
			$aError = $oException->getJsonBody();

			$this->log('Delete Subscription Error: ' . $aError['error']['message']);
			$this->log($aError);

			return false;
		}

		return true;
	}

	protected function _processEvent()
	{
    	$sInput = @file_get_contents("php://input");
		$aEvent = json_decode($sInput, true);
		if(empty($aEvent) || !is_array($aEvent)) 
			return 404;

		$sType = $aEvent['event_type'];
		if(!in_array($sType, array('payment_succeeded', ' payment_refunded', 'subscription_cancelled')))
			return 200;

		$this->log('Webhooks: ' . (!empty($sType) ? $sType : ''));
		$this->log($aEvent);

		$sMethod = '_processEvent' . bx_gen_method_name($sType, array('.', '_', '-'));
    	if(!method_exists($this, $sMethod))
    		return 200;

    	return $this->$sMethod($aEvent) ? 200 : 403;
    }

	protected function _processEventPaymentSucceeded(&$aEvent)
	{
		$mixedResult = $this->_getData($aEvent, true);
		if($mixedResult === false)
			return false;

		list($aPending, $aTransaction) = $mixedResult;

		$fTransactionAmount = (float)$aTransaction['amount'] / 100;
		$sTransactionCurrency = strtoupper($aTransaction['currency_code']);
		if($this->_bCheckAmount && ((float)$aPending['amount'] != $fTransactionAmount || strcasecmp($this->_oModule->_oConfig->getDefaultCurrencyCode(), $sTransactionCurrency) != 0))
			return false;

		return $this->_oModule->registerPayment($aPending);
	}

	protected function _processEventPaymentRefunded(&$aEvent)
	{
		$mixedResult = $this->_getData($aEvent);
		if($mixedResult === false)
			return false;

		list($aPending) = $mixedResult;
		return $this->_oModule->refundPayment($aPending);
	}

	protected function _processEventSubscriptionCancelled(&$aEvent)
	{
		$mixedResult = $this->_getData($aEvent);
		if($mixedResult === false)
			return false;

		list($aPending) = $mixedResult;
		return $this->_oModule->cancelSubscription($aPending);
	}

	protected function _getData(&$aEvent, $bWithStatusCheck = false)
	{
		$aTransaction = $aEvent['content']['transaction'];
		if(empty($aTransaction) || ($bWithStatusCheck && $aTransaction['status'] != 'success'))
			return false;

		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'order', 'order' => $aTransaction['subscription_id']));
		if(empty($aPending) || !is_array($aPending))
			return false;

		return array($aPending, $aTransaction);
	}
}

/** @} */
