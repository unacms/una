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

class BxPaymentSubscriptions extends BxBaseModPaymentSubscriptions
{
    function __construct()
    {
    	$this->MODULE = 'bx_payment';

    	parent::__construct();
    }

    /*
     * Service methods
     */
    
    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_list_my get_block_list_my
     * 
     * @code bx_srv('bx_payment', 'get_block_list_my', [...], 'Subscriptions'); @endcode
     * 
     * Get page block with a list of currently logged in member's subscriptions.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentSubscriptions::serviceGetBlockListMy
     */
    /** 
     * @ref bx_payment-get_block_list_my "get_block_list_my"
     */
    public function serviceGetBlockListMy()
    {
        return $this->_getBlock('list_my');
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_list_all get_block_list_all
     * 
     * @code bx_srv('bx_payment', 'get_block_list_all', [...], 'Subscriptions'); @endcode
     * 
     * Get page block with a list of all subscriptions. It's available for authorized members only.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentSubscriptions::serviceGetBlockListAll
     */
    /** 
     * @ref bx_payment-get_block_list_all "get_block_list_all"
     */
    public function serviceGetBlockListAll()
    {
        return $this->_getBlock('list_all');
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_history get_block_history
     * 
     * @code bx_srv('bx_payment', 'get_block_history', [...], 'Subscriptions'); @endcode
     * 
     * Get page block with a list of payments related to subscriptions of currently logged in member.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentSubscriptions::serviceGetBlockHistory
     */
    /** 
     * @ref bx_payment-get_block_history "get_block_history"
     */
    public function serviceGetBlockHistory()
    {
        return $this->_getBlock('history');
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-other Other
     * @subsubsection bx_payment-get_subscription_orders_info get_subscription_orders_info
     * 
     * @code bx_srv('bx_payment', 'get_subscription_orders_info', [...], 'Subscriptions'); @endcode
     * 
     * Get subscription transaction(s) which meets all requirements.
     *
     * @param $aConditions an array of pears('key' => 'value'). The most useful keys are the following:
     * a. client_id - client's ID (integer)
     * b. seller_id - seller's ID (integer)
     * c. type - transaction type: single or recurring (string)
     * d. amount - transaction amount (float)
     * e. order - order ID received from payment provider (string)
     * f. provider - payment provider name (string)
     * g. date - the date when the payment was established(UNIXTIME STAMP)
     * h. processed - whether the payment was processed or not (integer, 0 or 1)
     * @return an array of transactions. Each transaction has full info(client ID, seller ID, external transaction ID, date and so on)
     * 
     * @see BxPaymentSubscriptions::serviceGetSubscriptionOrdersInfo
     */
    /** 
     * @ref bx_payment-get_subscription_orders_info "get_subscription_orders_info"
     */
    public function serviceGetSubscriptionOrdersInfo($aConditions)
    {
        if(empty($aConditions) || !is_array($aConditions))
            return array();

        return $this->_oModule->_oDb->getOrderSubscription(array('type' => 'mixed', 'conditions' => $aConditions));
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-other Other
     * @subsubsection bx_payment-get_subscriptions_info get_subscriptions_info
     * 
     * @code bx_srv('bx_payment', 'get_subscriptions_info', [...], 'Subscriptions'); @endcode
     * 
     * Get subscription(s) which meets all requirements.
     *
     * @param $aConditions an array of pears('key' => 'value'). The most useful keys are the following:
     * a. pending_id - pending transaction ID (integer)
     * b. customer_id - customer ID from payment provider (string)
     * c. subscription_id - subscription ID from payment provider (string)
     * d. paid - flag determining whether the subscription paid or not (integer)
     * e. date - the date when the subscription was established(UNIXTIME STAMP)
     * @param $bCheckInProvider boolean value determining whether the subscription should be checked in associated payment provider.
     * @return an array of subscriptions. Each subscription has full info(pending ID, customer ID, subscription ID and so on)
     * 
     * @see BxPaymentSubscriptions::serviceGetSubscriptionsInfo
     */
    /** 
     * @ref bx_payment-get_subscriptions_info "get_subscriptions_info"
     */
    public function serviceGetSubscriptionsInfo($aConditions, $bCheckInProvider = false)
    {
        if(empty($aConditions) || !is_array($aConditions))
            return array();

        $aSubscriptions = $this->_oModule->_oDb->getSubscription(array('type' => 'mixed_ext', 'conditions' => $aConditions));
        if(empty($aSubscriptions) || !is_array($aSubscriptions) || !$bCheckInProvider)
            return $aSubscriptions;

        foreach($aSubscriptions as $iKey => $aSubscription) {
            if(empty($aSubscription['provider']) || $aSubscription['provider'] == 'manual')
                continue;

            $oProvider = $this->_oModule->getObjectProvider($aSubscription['provider'], $aSubscription['seller_id']);
            if(!$oProvider)
                continue;

            $aSubscriptions[$iKey]['data'] = $oProvider->getSubscription($aSubscription['pending_id'], $aSubscription['customer_id'], $aSubscription['subscription_id']);
        }

        return $aSubscriptions;
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-purchase_processing Purchase Processing
     * @subsubsection bx_payment-subscribe subscribe
     * 
     * @code bx_srv('bx_payment', 'subscribe', [...], 'Subscriptions'); @endcode
     * 
     * Initialize subscription for specified item.
     *
     * @param $iSellerId integer value with seller ID.
     * @param $sSellerProvider string value with a name of payment provider to be used for processing. Empty value means that payment provider selector should be shown.
     * @param $iModuleId integer value with module ID.
     * @param $iItemId integer value with item ID.
     * @param $iItemCount integer value with a number of items for purchasing. It's equal to 1 in case of subscription.
     * @param $sRedirect (optional) string value with redirect URL, if it's needed. 
     * @return an array with special format which describes the result of operation.
     * 
     * @see BxPaymentSubscriptions::serviceSubscribe
     */
    /** 
     * @ref bx_payment-subscribe "subscribe"
     */
    public function serviceSubscribe($iSellerId, $sSellerProvider, $iModuleId, $iItemId, $iItemCount, $sRedirect = '', $aCustom = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$iClientId = $this->_oModule->getProfileId();

    	$mixedResult = $this->_oModule->checkData($iClientId, $iSellerId, $iModuleId, $iItemId, $iItemCount);
    	if($mixedResult !== true)
    		return $mixedResult;

        $aSellerProviders = $this->_oModule->_oDb->getVendorInfoProvidersRecurring($iSellerId);
        if(empty($aSellerProviders))
            return array('code' => 5, 'message' => _t($CNF['T']['ERR_NOT_ACCEPT_PAYMENTS']));

        $aCartItem = array($iSellerId, $iModuleId, $iItemId, $iItemCount);
        $sCartItem = $this->_oModule->_oConfig->descriptorA2S($aCartItem);

		if(empty($sSellerProvider)) {
			$sId = $this->_oModule->_oConfig->getHtmlIds('cart', 'providers_select') . BX_PAYMENT_TYPE_RECURRING;
			$sTitle = _t($CNF['T']['POPUP_PROVIDERS_SELECT']);
			return array('popup' => array(
				'html' => BxTemplStudioFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->displayProvidersSelector($aCartItem, $aSellerProviders, $sRedirect, $aCustom)), 
				'options' => array('closeOnOuterClick' => true)
			));
		}

		$aCustoms = array();
		$this->_oModule->_oConfig->putCustom($aCartItem, $aCustom, $aCustoms);

        $mixedResult = $this->_oModule->serviceInitializeCheckout(BX_PAYMENT_TYPE_RECURRING, $iSellerId, $aSellerProviders[$sSellerProvider]['name'], array($sCartItem), $sRedirect, $aCustoms);
        if(is_string($mixedResult))
        	return array('code' => 6, 'message' => _t($mixedResult));

		return $mixedResult;
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-purchase_processing Purchase Processing
     * @subsubsection bx_payment-subscribe subscribe
     * 
     * @code bx_srv('bx_payment', 'subscribe', [...], 'Subscriptions'); @endcode
     * 
     * Initialize subscription for specified item.
     *
     * @param $iSellerId integer value with seller ID.
     * @param $sSellerProvider string value with a name of payment provider to be used for processing. Empty value means that payment provider selector should be shown.
     * @param $iModuleId integer value with module ID.
     * @param $iItemId integer value with item ID.
     * @param $iItemCount integer value with a number of items for purchasing. It's equal to 1 in case of subscription.
     * @param $sRedirect (optional) string value with redirect URL, if it's needed. 
     * @return an array with special format which describes the result of operation.
     * 
     * @see BxPaymentSubscriptions::serviceSubscribe
     */
    /** 
     * @ref bx_payment-subscribe "subscribe"
     */
    public function serviceSendSubscriptionExpirationLetters($iPendingId, $sOrderId)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;
        $sPrefix = $this->_oModule->_oConfig->getPrefix('general');

        $aSubscription = $this->serviceGetSubscriptionsInfo(array('subscription_id' => $sOrderId));
        if(empty($aSubscription) || !is_array($aSubscription))
            return;

        $aSubscription = array_shift($aSubscription);

        $oSeller = BxDolProfile::getInstanceMagic((int)$aSubscription['seller_id']);
        $oClient = BxDolProfile::getInstanceMagic((int)$aSubscription['client_id']);

        $aEtParams = array(
            'sibscription_id' => $aSubscription['subscription_id'],
            'sibscription_customer' => $aSubscription['customer_id'],
            'sibscription_date' => bx_time_js($aSubscription['date'], BX_FORMAT_DATE, true)
        );

        /**
         * Notify seller.
         */
        if($oSeller !== false) {
            $sEmail = '';
            $oProvider = $this->_oModule->getObjectProvider($aSubscription['provider'], $aSubscription['seller_id']);
            if($oProvider !== false && $oProvider->isActive())
                $sEmail = $oProvider->getOption('expiration_email');

            if(empty($sEmail))
                $sEmail = $oSeller->getAccountObject()->getEmail();

            $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate($sPrefix . 'expiration_notification_seller', $aEtParams, 0, (int)$aSubscription['client_id']);

            sendMail($sEmail, $aTemplate['Subject'], $aTemplate['Body'], 0, array(), BX_EMAIL_SYSTEM);
        }

        /**
         * Notify client.
         */
        if($oClient !== false) {
            $sEmail = $oClient->getAccountObject()->getEmail();
            $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate($sPrefix . 'expiration_notification_client', $aEtParams, 0, (int)$aSubscription['seller_id']);

            sendMail($sEmail, $aTemplate['Subject'], $aTemplate['Body'], 0, array(), BX_EMAIL_SYSTEM);
        }
    }

    public function cancel($iPendingId)
    {
    	$aSubscription = $this->_oModule->_oDb->getSubscription(array('type' => 'pending_id', 'pending_id' => $iPendingId));
		if(empty($aSubscription) || !is_array($aSubscription))
	    	return true;

        $aOrder = $this->_oModule->_oDb->getOrderSubscription(array('type' => 'id', 'id' => $iPendingId));
        if(empty($aOrder) || !is_array($aOrder))
	    	return false;

        $iSellerId = (int)$aOrder['seller_id'];
        $oProvider = $this->_oModule->getObjectProvider($aOrder['provider'], $iSellerId);
        if($oProvider === false || !$oProvider->isActive())
        	return false;

        if(!$oProvider->cancelRecurring($iPendingId, $aSubscription['customer_id'], $aSubscription['subscription_id']))
            return false;

        list($iSellerId, $iModuleId, $iItemId, $iItemCount) = $this->_oModule->_oConfig->descriptorS2A($aOrder['items']);
		if(!$this->_oModule->callCancelSubscriptionItem((int)$iModuleId, array($aOrder['client_id'], $iSellerId, $iItemId, $iItemCount, $aOrder['order'])))
			return false;

		if(!$this->_oModule->_oDb->deleteSubscription($aSubscription['id'], 'cancel'))
			return false;

        return true;
    }

    protected function _getBlock($sType)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sMethod = 'displayBlockSbs' . bx_gen_method_name($sType);
        if(!$this->_oModule->_oTemplate->isMethodExists($sMethod))
            return array(
        		'content' => MsgBox(_t('_Empty'))
            );

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return array(
        		'content' => MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']))
            );

        $this->_oModule->setSiteSubmenu('menu_dashboard', 'system', 'dashboard-subscriptions');

        $sBlockSubmenu = $this->_oModule->_oConfig->getObject('menu_sbs_submenu');
        $oBlockSubmenu = BxDolMenu::getObjectInstance($sBlockSubmenu);
        if($oBlockSubmenu) 
            $oBlockSubmenu->setSelected($this->MODULE, 'sbs-' . str_replace('_', '-', $sType));     

        return array(
        	'content' => $this->_oModule->_oTemplate->$sMethod($iUserId),
        	'menu' => $this->_oModule->_oConfig->getObject('menu_sbs_submenu')
        );
    }
}

/** @} */
