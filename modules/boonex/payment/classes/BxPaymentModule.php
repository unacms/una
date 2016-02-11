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
 *   a. serviceGetItems($iVendorId) - Is used in Orders Administration to get all products of the requested seller(vendor).
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
 * BxDolService::call('payment', 'get_add_to_cart_link', array($iVendorId, $mixedModuleId, $iItemId, $iItemCount));
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

    public function actionGetItems($iModuleId)
    {
    	$iSellerId = $this->getProfileId();
        $aItems = $this->callGetCartItems((int)$iModuleId, array($iSellerId));

        $aResult = array('code' => 0);
        if(is_array($aItems) && !empty($aItems))
            $aResult['data'] = $this->_oTemplate->displayItems($aItems);

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
     * a. order_id - internal order ID (string)
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
    public function actionCartSubmit()
    {
    	if(!$this->isLogged())
            return $this->_oTemplate->displayPageCodeError($this->_sLangsPrefix . 'err_required_login');

        if(bx_get('bx-payment-delete') !== false && bx_get('items') !== false){
        	$aItems = bx_get('items');
            foreach($aItems as $sItem) {
                list($iVendorId, $iModuleId, $iItemId, $iItemCount) = $this->_oConfig->descriptorS2A($sItem);
                $this->getObjectCart()->serviceDeleteFromCart($iVendorId, $iModuleId, $iItemId);
            }
        }
		else if(bx_get('bx-payment-checkout') !== false && bx_get('items') !== false) {
			$iVendorId = (int)bx_get('vendor_id');
			$sProvider = bx_process_input(bx_get('provider'));
			$aItems = bx_get('items');

			$sError = $this->serviceInitializeCheckout($iVendorId, $sProvider, $aItems);
			if(!empty($sError))
	    		return $this->_oTemplate->displayPageCodeError($sError, false);
		}

        header('Location: ' . $this->_oConfig->getUrl('cart'));
        exit;
    }

    public function actionAddToCart($iVendorId, $iModuleId, $iItemId, $iItemCount)
    {
        $aResult = $this->getObjectCart()->serviceAddToCart($iVendorId, $iModuleId, $iItemId, $iItemCount);
		echoJson($aResult);
    }

    /**
     * Isn't used yet.
     */
    public function actionDeleteFromCart($iVendorId, $iModuleId, $iItemId)
    {
        $aResult = $this->getObjectCart()->serviceDeleteFromCart($iVendorId, $iModuleId, $iItemId);
        echoJson($aResult);
    }

    /**
     * Isn't used yet.
     */
    public function actionEmptyCart($iVendorId)
    {
        $aResult = $this->getObjectCart()->serviceDeleteFromCart($this->_iUserId, $iVendorId);
		echoJson($aResult);
    }


    /**
     * Payment Processing Methods
     */
	public function serviceInitializeCheckout($iVendorId, $sProvider, $aItems = array())
	{
		if(!is_array($aItems))
			$aItems = array($aItems);

		$iVendorId = (int)$iVendorId;
        if($iVendorId == BX_PAYMENT_EMPTY_ID)
            return MsgBox(_t($this->_sLangsPrefix . 'err_unknown_vendor'));

		$oProvider = $this->getObjectProvider($sProvider, $iVendorId);
        if($oProvider === false)
        	return MsgBox(_t($this->_sLangsPrefix . 'err_incorrect_provider'));

        $aInfo = $this->getObjectCart()->getInfo($this->_iUserId, $iVendorId, $aItems);
        if(empty($aInfo) || $aInfo['vendor_id'] == BX_PAYMENT_EMPTY_ID || empty($aInfo['items']))
            return MsgBox(_t($this->_sLangsPrefix . 'err_empty_order'));

		/*
		 * Process FREE (price = 0) items for LOGGED IN members
		 * WITHOUT processing via payment provider.
		 */
		$bProcessedFree = false;
		foreach($aInfo['items'] as $iIndex => $aItem)
			if((int)$aInfo['client_id'] != 0 && (float)$aItem['price'] == 0) {
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
            return MsgBox(_t($this->_sLangsPrefix . ($bProcessedFree ? 'msg_successfully_processed_free' : 'err_empty_order')));

        $iPendingId = $this->_oDb->insertOrderPending($this->_iUserId, $sProvider, $aInfo);
        if(empty($iPendingId))
            return MsgBox(_t($this->_sLangsPrefix . 'err_access_db'));

		/*
		 * Perform Join WITHOUT processing via payment provider
		 * if a client ISN'T logged in and has only ONE FREE item in the card.
		 */
		if((int)$aInfo['client_id'] == 0 && (int)$aInfo['items_count'] == 1) {
			reset($aInfo['items']);
			$aItem = current($aInfo['items']);

			if(!empty($aItem) && $aItem['price'] == 0) {
				$this->_oDb->updateOrderPending($iPendingId, array(
		            'order' => $this->_oConfig->getLicense(),
		            'error_code' => '1',
		            'error_msg' => ''
		        ));

				$this->getObjectJoin()->performJoin($iPendingId);
			}
		}

		$sError = $oProvider->initializeCheckout($iPendingId, $aInfo);
		if(!empty($sError))
			return MsgBox($sError);

        return true;
	}

    public function actionFinalizeCheckout($sProvider, $mixedVendorId = "")
    {
        $aData = &$_REQUEST;

        $oProvider = $this->getObjectProvider($sProvider, $mixedVendorId);
        if($oProvider === false)
        	return $this->_oTemplate->displayPageCodeError($this->_sLangsPrefix . 'err_incorrect_provider');

        $aResult = $oProvider->finalizeCheckout($aData);
        if((int)$aResult['code'] != BX_PAYMENT_RESULT_SUCCESS) 
        	return $this->_oTemplate->displayPageCodeError($aResult['message']);

		$aPending = $this->_oDb->getOrderPending(array('type' => 'id', 'id' => (int)$aResult['pending_id']));

		//--- Check "Pay Before Join" situation
		if((int)$aPending['client_id'] == 0)
			$this->getObjectJoin()->performJoin((int)$aPending['id'], $aResult);

		//--- Register payment for purchased items in associated modules 
		$this->getObjectCart()->updateInfo($aPending);

		if($oProvider->needRedirect()) {
			header('Location: ' . $this->_oConfig->getUrl('return'));
			exit;
		}

		return $this->_oTemplate->displayPageCodeResponse($aResult['message']);
    }

    public function actionCheckoutFinished($sProvider, $mixedVendorId = "")
    {
        $oProvider = $this->getObjectProvider($sProvider, $mixedVendorId);
        if($oProvider === false)
        	return $this->_oTemplate->displayPageCodeError($this->_sLangsPrefix . 'err_incorrect_provider');

        $aResult = $oProvider->checkoutFinished();

        $this->_oTemplate->displayPageCodeResponse($aResult['message']);
        exit;
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
}

/** @} */
