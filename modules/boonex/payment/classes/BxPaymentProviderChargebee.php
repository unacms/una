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

define('CBEE_MODE_LIVE', 1);
define('CBEE_MODE_TEST', 2);

use ChargeBee\ChargeBee\Environment;
use ChargeBee\ChargeBee\Models\Addon;
use ChargeBee\ChargeBee\Models\Customer;
use ChargeBee\ChargeBee\Models\HostedPage;
use ChargeBee\ChargeBee\Models\Subscription;
use ChargeBee\ChargeBee\Models\Invoice;

class BxPaymentProviderChargebee extends BxBaseModPaymentProvider implements iBxBaseModPaymentProvider
{
    protected $_iMode;
    protected $_bCheckAmount;

    function __construct($aConfig)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);   

        $this->_aSbsStatuses = array(
            'future' => BX_PAYMENT_SBS_STATUS_SCHEDULED, 
            'in_trial' => BX_PAYMENT_SBS_STATUS_TRIAL, 
            'active' => BX_PAYMENT_SBS_STATUS_ACTIVE, 
            'non_renewing' => BX_PAYMENT_SBS_STATUS_ACTIVE, 
            'paused' => BX_PAYMENT_SBS_STATUS_PAUSED,
            'canceled' => BX_PAYMENT_SBS_STATUS_CANCELED,
        );
    }

    public function initOptions($aOptions)
    {
    	parent::initOptions($aOptions);

    	$this->_iMode = (int)$this->getOption('mode');
        $this->_bCheckAmount = $this->getOption('check_amount') == 'on';
        $this->_bUseSsl = $this->getOption('ssl') == 'on';
    }

    public function initializeCheckout($iPendingId, $aCartInfo)
    {
    	$aItem = array_shift($aCartInfo['items']);
    	if(empty($aItem) || !is_array($aItem))
            return $this->_sLangsPrefix . 'err_empty_items';

        $aClient = $this->_oModule->getProfileInfo();
        $aVendor = $this->_oModule->getProfileInfo($aCartInfo['vendor_id']);

        $oPage = $this->createHostedPageRecurring($aItem, $aClient, $aVendor, $iPendingId);
        if($oPage === false)
            return $this->_sLangsPrefix . 'err_cannot_perform';

        return array(
            'code' => 0,
            'eval' => $this->_oModule->_oConfig->getJsObject('cart') . '.onSubscribeSubmit(oData);',
            'redirect' => $oPage->url
        );
    }

    public function finalizeCheckout(&$aData)
    {
        $sPageId = bx_process_input($aData['id']);
        $iPendingId = bx_process_input($aData['pending_id'], BX_DATA_INT);

        if(empty($sPageId) || empty($iPendingId))
            return array('code' => 1, 'message' => $this->_sLangsPrefix . 'err_wrong_data');

        $oPage = $this->retreiveHostedPage($sPageId);
        if($oPage === false)
                return $this->_sLangsPrefix . 'err_cannot_perform';

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return array('code' => 3, 'message' => $this->_sLangsPrefix . 'err_already_processed');

        $oCustomer = $oPage->content()->customer();
        $oSubscription = $oPage->content()->subscription();

        $aResult = array(
            'code' => BX_PAYMENT_RESULT_SUCCESS,
            'message' => $this->_sLangsPrefix . 'cbee_msg_subscribed',
            'pending_id' => $iPendingId,
            'customer_id' => $oCustomer->id,
            'subscription_id' => $oSubscription->id,
            'client_name' => _t($this->_sLangsPrefix . 'txt_buyer_name_mask', $oCustomer->firstName, $oCustomer->lastName),
            'client_email' => $oCustomer->email,
            'paid' => false,
            'trial' => $oSubscription->status == 'in_trial',
        );

        //--- Update pending transaction ---//
        $this->_oModule->_oDb->updateOrderPending($iPendingId, array(
            'order' => $oSubscription->id,
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

    public function cancelRecurring($iPendingId, $sCustomerId, $sSubscriptionId)
    {
        return $this->deleteSubscription($sSubscriptionId);
    }

    public function createHostedPageSingle($aItem, $aClient, $aVendor = array(), $iPendingId = 0)
    {
        $oPage = false;

        try {
            $aPage = [
                'embed' => false, //--- Note. 'embed' should be disabled to allow payments via PayPal
                'charges' => [
                    [
                        'amount' => $aItem['amount'], 
                        'description' => $aItem['description'],
                    ]
                ],
                'customer' => [
                    'email' => $aClient['email'],
                    'firstName' => $aClient['name']
                ],
            ];

            if(!empty($aItem['addons']) && is_array($aItem['addons']))
                $aPage['addons'] = $aItem['addons'];

            if(!empty($aVendor) && is_array($aVendor) && !empty($aVendor))
                $aPage['redirectUrl'] = bx_append_url_params($this->getReturnDataUrl($aVendor['id']), array(
                    'pending_id' => $iPendingId
                ));

            Environment::configure($this->_getSite(), $this->_getApiKey());
            $oResult = HostedPage::checkoutOneTime($aPage);

            $oPage = $oResult->hostedPage();
        }
        catch (Exception $oException) {
            $iError = $oException->getCode();
            $sError = $oException->getMessage();

            $this->log('Create Hosted Page Error: ' . $sError . '(' . $iError . ')');

            return false;
        }

        return $oPage;
    }

    public function createHostedPageRecurring($aItem, $aClient, $aVendor = array(), $iPendingId = 0)
    {
        $oPage = false;

        try {
            $aPage = [
                'embed' => false, //--- Note. 'embed' should be disabled to allow payments via PayPal
                'subscription' => [
                    'planId' => $aItem['name']
                ],
                'customer' => [
                    'email' => $aClient['email'],
                    'firstName' => $aClient['name']
                ], 
            ];

            if(!empty($aItem['addons']) && is_array($aItem['addons']))
                $aPage['addons'] = $aItem['addons'];

            if(!empty($aVendor) && is_array($aVendor) && !empty($aVendor))
                $aPage['redirectUrl'] = bx_append_url_params($this->getReturnDataUrl($aVendor['id']), array(
                    'pending_id' => $iPendingId
                ));

            Environment::configure($this->_getSite(), $this->_getApiKey());
            $oResult = HostedPage::checkoutNew($aPage);

            $oPage = $oResult->hostedPage();
        }
        catch (Exception $oException) {
            $iError = $oException->getCode();
            $sError = $oException->getMessage();

            $this->log('Create Hosted Page Error: ' . $sError . '(' . $iError . ')');

            return false;
        }

        return $oPage;
    }

    public function retreiveHostedPage($sPageId)
    {
        $oPage = null;

        try {
            Environment::configure($this->_getSite(), $this->_getApiKey());
            $oResult = HostedPage::retrieve($sPageId);

            $oPage = $oResult->hostedPage();
        }
        catch (Exception $oException) {
            $iError = $oException->getCode();
            $sError = $oException->getMessage();

            $this->log('Retrieve Hosted Page Error: ' . $sError . '(' . $iError . ')');

            return false;
        }

        return $oPage;
    }

    public function retrieveSubscription($sSubscriptionId)
    {
        $oSubscription = null;

        try {
            Environment::configure($this->_getSite(), $this->_getApiKey());
            $oResult = Subscription::retrieve($sSubscriptionId);

            $oSubscription = $oResult->subscription();
            if($oSubscription->id != $sSubscriptionId)
                return false;
        }
        catch (Exception $oException) {
            $iError = $oException->getCode();
            $sError = $oException->getMessage();

            $this->log('Retrieve Subscription Error: ' . $sError . '(' . $iError . ')');

            return false;
        }

        return $oSubscription;
    }

    public function getSubscription($iPendingId, $sCustomerId, $sSubscriptionId)
    {
        $oSubscription = $this->retrieveSubscription($sSubscriptionId);
        if($oSubscription === false)
            return array();

        $sStatus = $oSubscription->status;
        $sStatus = isset($this->_aSbsStatuses[$sStatus]) ? $this->_aSbsStatuses[$sStatus] : BX_PAYMENT_SBS_STATUS_UNKNOWN;

        return array(
            'status' => $sStatus,
            'created' => $oSubscription->createdAt,
            'started' => $oSubscription->startedAt,
            'trial_start' => $oSubscription->trialStart,
            'trial_end' => $oSubscription->trialEnd,
            'cperiod_start' => $oSubscription->currentTermStart,
            'cperiod_end' => $oSubscription->currentTermEnd,
        );
    }
    
    public function deleteSubscription($sSubscriptionId)
    {
        try {
            Environment::configure($this->_getSite(), $this->_getApiKey());
            $oResult = Subscription::cancel($sSubscriptionId);

            $oSubscription = $oResult->subscription();
            if($oSubscription->status != 'cancelled')
                return false;
        }
        catch (Exception $oException) {
            $aError = $oException->getJsonBody();

            $this->log('Delete Subscription Error: ' . $aError['error']['message']);
            $this->log($aError);

            return false;
        }

        $this->onDeleteSubscription($sSubscriptionId, $oSubscription->getValues());

        return true;
    }

    public function retrieveInvoice($sInvoiceId)
    {
        $oInvoice = null;

        try {
            Environment::configure($this->_getSite(), $this->_getApiKey());
            $oResult = Invoice::retrieve($sInvoiceId);

            $oInvoice = $oResult->invoice();
            if($oInvoice->id != $sInvoiceId)
                return false;
        }
        catch (Exception $oException) {
            $iError = $oException->getCode();
            $sError = $oException->getMessage();

            $this->log('Retrieve Invoice Error: ' . $sError . '(' . $iError . ')');

            return false;
        }

        return $oInvoice;
    }

    public function retrieveAddons($sStatus = 'active', $iLimit = 0)
    {
        $aAddons = array();

        try {
            $aParams = array();
            if(!empty($sStatus))
                $aParams['status[is]'] = $sStatus;
            if(!empty($iLimit))
                $aParams['limit'] = $iLimit;
            
            Environment::configure($this->_getSite(), $this->_getApiKey());
            $oResults = Addon::all($aParams);

            foreach($oResults as $oResult)
                $aAddons[] = $oResult->addon();
        }
        catch (Exception $oException) {
            $iError = $oException->getCode();
            $sError = $oException->getMessage();

            $this->log('Retrieve Addons Error: ' . $sError . '(' . $iError . ')');

            return false;
        }

        return $aAddons;
    }

    public function getAddons($sStatus = 'active', $iLimit = 0)
    {
        $aAddons = $this->retrieveAddons($sStatus, $iLimit);
        if(empty($aAddons) || !is_array($aAddons))
            return array();

        $aResult = array();
        foreach($aAddons as $oAddon)
            $aResult[] = $oAddon->getValues();

        return $aResult;
    }

    public function retrieveAddon($sId)
    {
        $oAddon = null;

        try {
            Environment::configure($this->_getSite(), $this->_getApiKey());
            $oResult = Addon::retrieve($sId);

            $oAddon = $oResult->addon();
            if($oAddon->id != $sId)
                return false;
        }
        catch (Exception $oException) {
            $iError = $oException->getCode();
            $sError = $oException->getMessage();

            $this->log('Retrieve Addon Error: ' . $sError . '(' . $iError . ')');

            return false;
        }

        return $oAddon;
    }

    public function getAddon($sId)
    {
        $oAddon = $this->retrieveAddon($sId);
        if($oAddon === false)
            return array();

        return $oAddon->getValues();
    }

    public function retrieveCustomer($sCustomerId)
    {
        $oCustomer = null;

        try {
            Environment::configure($this->_getSite(), $this->_getApiKey());
            $oResult = Customer::retrieve($sCustomerId);

            $oCustomer = $oResult->customer();
            if($oCustomer->id != $sCustomerId)
                    return false;
        }
        catch (Exception $oException) {
            $iError = $oException->getCode();
            $sError = $oException->getMessage();

            $this->log('Retrieve Customer Error: ' . $sError . '(' . $iError . ')');

            return false;
        }

        return $oCustomer;
    }

    public function onDeleteSubscription($sSubscriptionId, $aSubscription)
    {
        /**
         * @hooks
         * @hookdef hook-bx_payment-chargebee_cancel_subscription 'bx_payment', 'chargebee_cancel_subscription' - hook after a subscription was canceled
         * - $unit_name - equals `bx_payment`
         * - $action - equals `chargebee_cancel_subscription`
         * - $object_id - not used
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `subscription_id` - [string] unique subscription id
         *      - `subscription_info` - [array] subscription info
         * @hook @ref hook-bx_payment-chargebee_cancel_subscription
         */
        /**
         * @hooks
         * @hookdef hook-bx_payment-chargebee_v3_cancel_subscription 'bx_payment', 'chargebee_v3_cancel_subscription' - hook after a subscription was canceled
         * It's equivalent to @ref hook-bx_payment-chargebee_cancel_subscription
         * @hook @ref hook-bx_payment-chargebee_v3_cancel_subscription
         */
        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_cancel_subscription', 0, false, [
            'subscription_id' => $sSubscriptionId,
            'subscription_info' => $aSubscription
        ]);
    }


    protected function _getSite()
    {
        return $this->_iMode == CBEE_MODE_LIVE ? $this->getOption('live_site') : $this->getOption('test_site');
    }

    protected function _getApiKey()
    {
        return $this->_iMode == CBEE_MODE_LIVE ? $this->getOption('live_api_key') : $this->getOption('test_api_key');
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
        $mixedResult = $this->_getDataTransaction($aEvent, 'success');
        if($mixedResult === false)
            return false;

        list($aPending, $aTransaction) = $mixedResult;

        $fTransactionAmount = (float)$aTransaction['amount'] / 100;
        $sTransactionCurrency = strtoupper($aTransaction['currency_code']);
        if($this->_bCheckAmount && ((float)$aPending['amount'] != $fTransactionAmount || strcasecmp($this->_oModule->_oConfig->getDefaultCurrencyCode(), $sTransactionCurrency) !== 0))
            return false;

        if($aPending['type'] == BX_PAYMENT_TYPE_RECURRING)
            $this->_oModule->getObjectSubscriptions()->prolong($aPending);

        return $this->_oModule->registerPayment($aPending);
    }

    protected function _processEventPaymentRefunded(&$aEvent)
    {
        $mixedResult = $this->_getDataTransaction($aEvent);
        if($mixedResult === false)
            return false;

        list($aPending) = $mixedResult;
        return $this->_oModule->refundPayment($aPending);
    }

    protected function _processEventSubscriptionCancelled(&$aEvent)
    {
        $mixedResult = $this->_getDataSubscription($aEvent);
        if($mixedResult === false)
            return false;

        list($aPending, $aSubscription) = $mixedResult;
        $this->onDeleteSubscription($aSubscription['id'], $aSubscription);

        return $this->_oModule->getObjectSubscriptions()->cancelLocal($aPending);
    }

    protected function _getDataTransaction(&$aEvent, $sWithStatusCheck = '')
    {
        $aTransaction = $aEvent['content']['transaction'];
        if(empty($aTransaction) || (!empty($sWithStatusCheck) && $aTransaction['status'] != $sWithStatusCheck))
            return false;

        $sOrder = '';
        if(isset($aTransaction['subscription_id']))
            $sOrder = $aTransaction['subscription_id'];
        else if(isset($aTransaction['id']))
            $sOrder = $aTransaction['id'];

        if(empty($sOrder))
            return false;

        $aPending = $this->_oModule->_oDb->getOrderPending(['type' => 'order', 'order' => $sOrder]);
        if(empty($aPending) || !is_array($aPending))
            return false;

        return array($aPending, $aTransaction);
    }

    protected function _getDataSubscription(&$aEvent, $sWithStatusCheck = '')
    {
        $aSubscription = $aEvent['content']['subscription'];
        if(empty($aSubscription) || (!empty($sWithStatusCheck) && $aSubscription['status'] != $sWithStatusCheck))
            return false;

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'order', 'order' => $aSubscription['id']));
        if(empty($aPending) || !is_array($aPending))
            return false;

        return array($aPending, $aSubscription);
    }
}

/** @} */
