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

define('BX_PAYMENT_TYPE_SINGLE', 'single');
define('BX_PAYMENT_TYPE_RECURRING', 'recurring');

define('BX_PAYMENT_ORDERS_TYPE_PENDING', 'pending');
define('BX_PAYMENT_ORDERS_TYPE_PROCESSED', 'processed');
define('BX_PAYMENT_ORDERS_TYPE_SUBSCRIPTION', 'subscription');
define('BX_PAYMENT_ORDERS_TYPE_HISTORY', 'history');

define('BX_PAYMENT_EMPTY_ID', 0);

define('BX_PAYMENT_RESULT_SUCCESS', 0);

/**
 * Payment module by BoonEx
 *
 * This module is needed to work with payment providers and organize the process
 * of some item purchasing. Shopping Cart and Orders Manager are included.
 *
 * Integration notes:
 * To integrate your module with this one, you need:
 * 1. Get 'Add To Cart' button using serviceGetAddToCartLink service.
 * 2. Add info about your module in the 'bx_pmt_modules' table.
 * 3. Realize the following service methods in your Module class.
 *   a. serviceGetItems($iSellerId) - Is used in Orders Administration to get all products of the requested seller(vendor).
 *   b. serviceGetCartItem($iClientId, $iItemId) - Is used in Shopping Cart to get one product by specified id.
 *   c. serviceRegisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrderId) - Register purchased product.
 *   d. serviceUnregisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrderId) - Unregister the product purchased earlier.
 * @see You may see an example of integration in Membership module.
 *
 *
 * Profile's Wall:
 * no spy events
 *
 *
 *
 * Spy:
 * no spy events
 *
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 *
 * Service methods:
 *
 * Is used to get "Add to cart" link for some item(s) in your module.
 * @see BxPmtModule::serviceGetAddToCartLink
 * BxDolService::call('payment', 'get_add_to_cart_link', array($iSellerId, $mixedModuleId, $iItemId, $iItemCount));
 *
 * Check transaction(s) in database which satisty all conditions.
 * @see BxPmtModule::serviceGetTransactionsInfo
 * BxDolService::call('payment', 'get_transactions_info', array($aConditions));
 *
 * Get total count of items in Shopping Cart.
 * @see BxPmtModule::serviceGetCartItemsCount
 * BxDolService::call('payment', 'get_cart_items_count', array($iUserId, $iOldCount));
 * @note is needed for internal usage(integration with member tool bar).
 *
 * Get Shopping cart content.
 * @see BxPmtModule::serviceGetCartItems
 * BxDolService::call('payment', 'get_cart_items');
 * @note is needed for internal usage(integration with member tool bar).
 *
 *
 * Alerts:
 * no alerts available
 *
 */
class BxPaymentModule extends BxBaseModPaymentModule
{
    protected $_iUserId;

    protected $_aOrderTypes;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_iUserId = $this->getProfileId();

        $this->_aOrderTypes = array(
        	BX_PAYMENT_ORDERS_TYPE_PENDING, 
        	BX_PAYMENT_ORDERS_TYPE_PROCESSED, 
        	BX_PAYMENT_ORDERS_TYPE_SUBSCRIPTION, 
        	BX_PAYMENT_ORDERS_TYPE_HISTORY
        );
    }

    /**
     * Manage Orders Methods
     */
	public function actionGetClients()
    {
        $sTerm = bx_get('term');

        $aResult = BxDolService::call('system', 'profiles_search', array($sTerm), 'TemplServiceProfiles');

        echoJson($aResult);
    }

    public function actionGetItems($sType, $iModuleId)
    {
    	$iSellerId = $this->getProfileId();
        $aItems = $this->callGetCartItems((int)$iModuleId, array($iSellerId));

        $aResult = array('code' => 0);
        if(is_array($aItems) && !empty($aItems))
            $aResult['data'] = $this->_oTemplate->displayItems($sType, $aItems);

		echoJson($aResult);
    }


    /**
     * Payment Details Methods
     */
    public function serviceGetOptionsSiteAdmin()
    {
        $aResult = array(
            array('key' => '', 'value' => _t('_Select_one'))
        );

        $aIds = $this->_oDb->getAdminsIds();
        foreach($aIds as $iId) {
        	$aUser = $this->getProfileInfo($iId);

            $aResult[] = array(
                'key' => $iId,
                'value' => $aUser['name']
            );
        }

        return $aResult;
    }

    /**
     * Check transaction(s) in database which satisty all conditions.
     *
     * @param array $aConditions an array of pears('key' => 'value'). Available keys are the following:
     * a. license - internal license (string)
     * b. client_id - client's ID (integer)
     * c. seller_id - seller's ID (integer)
     * d. module_id - modules's where the purchased product is located. (integer)
     * e. item_id - item id in the database. (integer)
     * f. date - the date when the payment was processed(UNIXTIME STAMP)
     *
     * @return array of transactions. Each transaction has full info(client ID, seller ID, external transaction ID, date and so on)
     */
    public function serviceGetTransactionsInfo($aConditions)
    {
        return $this->_oDb->getOrderProcessed(array('type' => 'mixed', 'conditions' => $aConditions));
    }


    /**
     * Cart Processing Methods
     */
    public function actionAddToCart($iSellerId, $iModuleId, $iItemId, $iItemCount)
    {
        $aResult = $this->getObjectCart()->serviceAddToCart($iSellerId, $iModuleId, $iItemId, $iItemCount);
		echoJson($aResult);
    }

    /**
     * Isn't used yet.
     */
    public function actionDeleteFromCart($iSellerId, $iModuleId, $iItemId)
    {
        $aResult = $this->getObjectCart()->serviceDeleteFromCart($iSellerId, $iModuleId, $iItemId);
        echoJson($aResult);
    }

    /**
     * Isn't used yet.
     */
    public function actionEmptyCart($iSellerId)
    {
        $aResult = $this->getObjectCart()->serviceDeleteFromCart($iSellerId);
		echoJson($aResult);
    }

    public function actionSubscribe()
    {
    	$iSellerId = bx_process_input(bx_get('seller_id'), BX_DATA_INT);
    	$sSellerProvider = bx_process_input(bx_get('seller_provider'));
    	$iModuleId = bx_process_input(bx_get('module_id'), BX_DATA_INT);
    	$iItemId = bx_process_input(bx_get('item_id'), BX_DATA_INT);
    	$iItemCount = bx_process_input(bx_get('item_count'), BX_DATA_INT);
    	if(empty($iItemCount))
    		$iItemCount = 1;

        $aResult = $this->getObjectCart()->serviceSubscribe($iSellerId, $sSellerProvider, $iModuleId, $iItemId, $iItemCount);
		echoJson($aResult);
    }


    /**
     * Payment Processing Methods
     */
	public function actionInitializeCheckout($sType)
    {
    	if(!$this->isLogged())
            return $this->_oTemplate->displayPageCodeError($this->_sLangsPrefix . 'err_required_login');

		if(bx_get('seller_id') !== false && bx_get('provider') !== false && bx_get('items') !== false) {
			$iSellerId = bx_process_input(bx_get('seller_id'), BX_DATA_INT);
			$sProvider = bx_process_input(bx_get('provider'));
			$aItems = bx_process_input(bx_get('items'));

			$mixedResult = $this->serviceInitializeCheckout(BX_PAYMENT_TYPE_SINGLE, $iSellerId, $sProvider, $aItems);
			if($mixedResult !== true)
	    		return $this->_oTemplate->displayPageCodeError($mixedResult);
		}

        header('Location: ' . $this->_oConfig->getUrl('URL_CART'));
        exit;
    }

	public function serviceInitializeCheckout($sType, $iSellerId, $sProvider, $aItems = array())
	{
		if(!is_array($aItems))
			$aItems = array($aItems);

		$iSellerId = (int)$iSellerId;
        if($iSellerId == BX_PAYMENT_EMPTY_ID)
            return $this->_sLangsPrefix . 'err_unknown_vendor';

		$oProvider = $this->getObjectProvider($sProvider, $iSellerId);
        if($oProvider === false || !$oProvider->isActive())
        	return $this->_sLangsPrefix . 'err_incorrect_provider';

        $aInfo = $this->getObjectCart()->getInfo($sType, $this->_iUserId, $iSellerId, $aItems);
        if(empty($aInfo) || $aInfo['vendor_id'] == BX_PAYMENT_EMPTY_ID || empty($aInfo['items']))
            return $this->_sLangsPrefix . 'err_empty_order';

		/*
		 * Process FREE (price = 0) items for LOGGED IN members
		 * WITHOUT processing via payment provider.
		 */
		$bProcessedFree = false;
		$sKeyPriceSingle = $this->_oConfig->getKey('KEY_ARRAY_PRICE_SINGLE');
		$sKeyPriceRecurring = $this->_oConfig->getKey('KEY_ARRAY_PRICE_RECURRING');
		foreach($aInfo['items'] as $iIndex => $aItem)
			if((int)$aInfo['client_id'] != 0 && (float)$aItem[$sKeyPriceSingle] == 0 && (float)$aItem[$sKeyPriceRecurring] == 0) {
				$aItemInfo = $this->callRegisterCartItem((int)$aItem['module_id'], array($aInfo['client_id'], $aInfo['vendor_id'], $aItem['id'], $aItem['quantity'], $this->_oConfig->getLicense()));
	            if(is_array($aItemInfo) && !empty($aItemInfo))
	            	$bProcessedFree = true;

	            $aInfo['items_count'] -= 1;
	            unset($aInfo['items'][$iIndex]);

	            $sCartItems = $this->_oDb->getCartItems($aInfo['client_id']);
	            $sCartItems = trim(preg_replace("'" . $this->_oConfig->descriptorA2S(array($aInfo['vendor_id'], $aItem['module_id'], $aItem['id'], $aItem['quantity'])) . ":?'", "", $sCartItems), ":");
	            $this->_oDb->setCartItems($aInfo['client_id'], $sCartItems);
			}

		if(empty($aInfo['items']))
            return $this->_sLangsPrefix . ($bProcessedFree ? 'msg_successfully_processed_free' : 'err_empty_order');

        $iPendingId = $this->_oDb->insertOrderPending($this->_iUserId, $sType, $sProvider, $aInfo);
        if(empty($iPendingId))
            return $this->_sLangsPrefix . 'err_access_db';

		/*
		 * Perform Join WITHOUT processing via payment provider
		 * if a client ISN'T logged in and has only ONE FREE item in the card.
		 */
		if((int)$aInfo['client_id'] == 0 && (int)$aInfo['items_count'] == 1) {
			reset($aInfo['items']);
			$aItem = current($aInfo['items']);

			if(!empty($aItem) && $this->_oConfig->getPrice($sType, $aItem)) {
				$this->_oDb->updateOrderPending($iPendingId, array(
		            'order' => $this->_oConfig->getLicense(),
		            'error_code' => '1',
		            'error_msg' => ''
		        ));

				$this->getObjectJoin()->performJoin($iPendingId);
			}
		}

		return $oProvider->initializeCheckout($iPendingId, $aInfo);
	}

    public function actionFinalizeCheckout($sProvider, $mixedVendorId = "")
    {
        $aData = &$_REQUEST;

        $oProvider = $this->getObjectProvider($sProvider, $mixedVendorId);
        if($oProvider === false || !$oProvider->isActive())
        	return $this->_oTemplate->displayPageCodeError($this->_sLangsPrefix . 'err_incorrect_provider');

        $aResult = $oProvider->finalizeCheckout($aData);
        if((int)$aResult['code'] != BX_PAYMENT_RESULT_SUCCESS) 
        	return $this->_oTemplate->displayPageCodeError($aResult['message']);

		$aPending = $this->_oDb->getOrderPending(array('type' => 'id', 'id' => (int)$aResult['pending_id']));

		//--- Check "Pay Before Join" situation
		if((int)$aPending['client_id'] == 0)
			$this->getObjectJoin()->performJoin((int)$aPending['id'], isset($aResult['client_name']) ? $aResult['client_name'] : '', isset($aResult['client_email']) ? $aResult['client_email'] : '');

		//--- Register payment for purchased items in associated modules 
		if(!empty($aResult['paid']))
			$this->registerPayment($aPending);

		if($oProvider->needRedirect()) {
			header('Location: ' . $oProvider->getReturnUrl());
			exit;
		}

		$this->_oTemplate->displayPageCodeResponse($aResult['message']);
    }

    public function actionFinalizedCheckout($sProvider, $mixedVendorId = "")
    {
        $oProvider = $this->getObjectProvider($sProvider, $mixedVendorId);
        if($oProvider === false || !$oProvider->isActive())
        	return $this->_oTemplate->displayPageCodeError($this->_sLangsPrefix . 'err_incorrect_provider');

        $aResult = $oProvider->finalizedCheckout();
        $this->_oTemplate->displayPageCodeResponse($aResult['message']);
    }

    public function actionNotify($sProvider, $mixedVendorId = "")
    {
    	$oProvider = $this->getObjectProvider($sProvider, $mixedVendorId);
        if($oProvider === false || !$oProvider->isActive())
        	return $this->_oTemplate->displayPageCodeError($this->_sLangsPrefix . 'err_incorrect_provider');

		$oProvider->notify();
    }

    public function onProfileJoin($iProfileId)
    {
    	$this->getObjectJoin()->onProfileJoin($iProfileId);
    }

    public function onProfileDelete($iProfileId)
    {
		$this->_oDb->onProfileDelete($iProfileId);
    }

    public function isAllowedSell($aItem, $bPerform = false)
    {
		$iUserId = (int)$this->getProfileId();
        if(!$iUserId)
        	return false;

		$aItemInfo = $this->callGetCartItem($aItem['module_id'], array($aItem['item_id']));
		if(empty($aItemInfo))
			return false;

        if(isAdmin())
            return true;

        $aCheckResult = checkActionModule($iUserId, 'sell', $this->getName(), $bPerform);
        if((int)$aItemInfo['author_id'] == $iUserId && $aCheckResult[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
			return true;

        return $aCheckResult[CHECK_ACTION_MESSAGE];
    }

	public function registerPayment($mixedPending)
    {
    	$aPending = is_array($mixedPending) ? $mixedPending : $this->_oDb->getOrderPending(array('type' => 'id', 'id' => (int)$mixedPending));
    	if(empty($aPending) || !is_array($aPending))
    		return false;

		if((int)$aPending['processed'] == 1)
			return true;

		$sType = $aPending['type'];
		$bTypeSingle = $sType == BX_PAYMENT_TYPE_SINGLE;

		$iClientId = (int)$aPending['client_id'];
		$sLicense = $this->_oConfig->getLicense();

		$sCartItems = '';
		if($bTypeSingle)
			$sCartItems = $this->_oDb->getCartItems($iClientId);

        $aItems = $this->_oConfig->descriptorsM2A($aPending['items']);
        foreach($aItems as $aItem) {
        	$sMethod = $bTypeSingle ? 'callRegisterCartItem' : 'callRegisterSubscriptionItem';
        	$aItemInfo = $this->$sMethod((int)$aItem['module_id'], array($aPending['client_id'], $aPending['seller_id'], $aItem['item_id'], $aItem['item_count'], $aPending['order'], $sLicense));
            if(empty($aItemInfo) || !is_array($aItemInfo))
                continue;

            $this->_oDb->insertOrderProcessed(array(
                'pending_id' => $aPending['id'],
                'client_id' => $aPending['client_id'],
                'seller_id' => $aPending['seller_id'],
                'module_id' => $aItem['module_id'],
                'item_id' => $aItem['item_id'],
                'item_count' => $aItem['item_count'],
                'amount' => $aItem['item_count'] * $this->_oConfig->getPrice($sType, $aItemInfo),
            	'license' => $sLicense,
            ));

            if($bTypeSingle)
            	$sCartItems = trim(preg_replace("'" . $this->_oConfig->descriptorA2S($aItem) . ":?'", "", $sCartItems), ":");
        }

        if($bTypeSingle)
			$this->_oDb->setCartItems($iClientId, $sCartItems);

        $bResult = $this->_oDb->updateOrderPending($aPending['id'], array('processed' => 1));
        if($bResult) {
			//--- 'System' -> 'Register Payment' for Alerts Engine ---//
			bx_import('BxDolAlerts');
	        $oZ = new BxDolAlerts('system', 'register_payment', 0, $iClientId, array('pending' => $aPending));
	        $oZ->alert();
			//--- 'System' -> 'Register Payment' for Alerts Engine ---//
        }

        return $bResult;
    }

	public function refundPayment($mixedPending)
	{
		$aPending = is_array($mixedPending) ? $mixedPending : $this->_oDb->getOrderPending(array('type' => 'id', 'id' => (int)$mixedPending));
    	if(empty($aPending) || !is_array($aPending))
    		return false;

		$bTypeSingle = $aPending['type'] == BX_PAYMENT_TYPE_SINGLE;

		$iCanceled = 0;
		$aOrders = $this->_oDb->getOrderProcessed(array('type' => 'pending_id', 'pending_id' => (int)$aPending['id']));
		foreach($aOrders as $aOrder) {
			$sMethod = $bTypeSingle ? 'callUnregisterCartItem' : 'callUnregisterSubscriptionItem';
			$bResult = $this->$sMethod((int)$aOrder['module_id'], array($aOrder['client_id'], $aOrder['seller_id'], $aOrder['item_id'], $aOrder['item_count'], $aPending['order'], $aOrder['license']));
			if(!$bResult)
                continue;

            if($this->_oDb->deleteOrderProcessed($aOrder['id']))
            	$iCanceled++;
		}

		if($iCanceled != count($aOrders))
			return false;

		return $this->_oDb->deleteOrderPending($aPending['id']);
	}

	public function cancelSubscription($mixedPending)
	{
		$aPending = is_array($mixedPending) ? $mixedPending : $this->_oDb->getOrderPending(array('type' => 'id', 'id' => (int)$mixedPending));
    	if(empty($aPending) || !is_array($aPending) || $aPending['type'] != BX_PAYMENT_TYPE_RECURRING)
    		return false;

		$aItems = $this->_oConfig->descriptorsM2A($aPending['items']);
        foreach($aItems as $aItem)
			$this->callCancelSubscriptionItem((int)$aItem['module_id'], array($aPending['client_id'], $aPending['seller_id'], $aItem['item_id'], $aItem['item_count'], $aPending['order']));
	}
}

/** @} */
