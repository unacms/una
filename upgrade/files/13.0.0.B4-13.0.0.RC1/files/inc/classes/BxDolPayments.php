<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolPayments extends BxDolFactory implements iBxDolSingleton
{
    protected $_oDb;

    protected $_aObjects;
    protected $_sActive;

    protected function __construct()
    {
        parent::__construct();

        $this->_oDb = new BxDolPaymentsQuery();

        $this->_aObjects = $this->_oDb->getObjects();
        $this->_sActive = getParam('sys_default_payment');
    }

    static public function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses']['BxDolPayments']))
            $GLOBALS['bxDolClasses']['BxDolPayments'] = new BxDolPayments();

        return $GLOBALS['bxDolClasses']['BxDolPayments'];
    }

    public function setActive($sActive)
    {
        $this->_sActive = $sActive;
    }

    public function getActive()
    {
    	return $this->_sActive;
    }

    public function getActiveObject()
    {
        if(!$this->isActive())
            return null;

    	return BxDolModule::getInstance($this->_sActive);
    }

    public function isActive()
    {
    	if(empty($this->_sActive))
            return false;

    	if(!BxDolModuleQuery::getInstance()->isModuleByName($this->_sActive))
            return false;

    	return true;
    }

    public function isCreditsOnly()
    {
    	if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'is_credits_only'))
            return false;

        return bx_srv($this->_sActive, 'is_credits_only');
    }

    public function getDetailsUrl()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_details_url', 'Details'))
            return '';

    	return bx_srv($this->_sActive, 'get_details_url', array(), 'Details');
    }

    public function getCurrencyInfo($iVendorId = 0)
    {
    	if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'get_currency_info'))
            return [];

    	$aSrvParams = [$iVendorId];
        return bx_srv($this->_sActive, 'get_currency_info', $aSrvParams);
    }
    
    public function getCurrencyCode($iVendorId = 0)
    {
    	if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'get_currency_code'))
            return '';

    	$aSrvParams = [$iVendorId];
        return bx_srv($this->_sActive, 'get_currency_code', $aSrvParams);
    }

    public function getCurrencySign($iVendorId = 0)
    {
    	if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'get_currency_sign'))
            return '';

    	$aSrvParams = [$iVendorId];
        return bx_srv($this->_sActive, 'get_currency_sign', $aSrvParams);
    }

    public function convert($fAmount, $sCurrencyFrom, $sCurrencyTo)
    {
        if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'convert'))
            return false;

    	$aSrvParams = [$fAmount, $sCurrencyFrom, $sCurrencyTo];
        return bx_srv($this->_sActive, 'convert', $aSrvParams);
    }

    public function isAcceptingPayments($iVendorId, $sPaymentType = '')
    {
    	if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'is_accepting_payments'))
            return false;

    	$aSrvParams = array($iVendorId, $sPaymentType);
        return bx_srv($this->_sActive, 'is_accepting_payments', $aSrvParams);
    }

    /**
     * @deprecated since version 11.0.0
     * 
     * @see BxDolPayments::isProviderOptions
     */
    public function isPaymentProvider($iVendorId, $sVendorProvider, $sPaymentType = '')
    {
    	return $this->isProviderOptions($iVendorId, $sVendorProvider);
    }

    /**
     * @deprecated since version 11.0.0
     * 
     * @see BxDolPayments::getProviderOptions
     */
    public function getPaymentProvider($iVendorId, $sVendorProvider, $sPaymentType = '')
    {
    	return $this->getProviderOptions($iVendorId, $sVendorProvider, $sPaymentType);
    }

    public function isProviderOptions($iVendorId, $sVendorProvider, $sPaymentType = '')
    {
        if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'is_provider_options'))
            return false;

        $aSrvParams = array($iVendorId, $sVendorProvider, $sPaymentType);
        return bx_srv($this->_sActive, 'is_provider_options', $aSrvParams);
    }

    public function getProvider($sProvider, $mixedVendorId = BX_PAYMENT_EMPTY_ID)
    {
        if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'get_provider'))
            return false;

        $aSrvParams = array($sProvider, $mixedVendorId);
        return bx_srv($this->_sActive, 'get_provider', $aSrvParams);
    }

    public function getProviderOptions($iVendorId, $sVendorProvider, $sPaymentType = '')
    {
        if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'get_provider_options'))
            return false;

        $aSrvParams = array($iVendorId, $sVendorProvider, $sPaymentType);
        return bx_srv($this->_sActive, 'get_provider_options', $aSrvParams);
    }

    public function getModulesWithPayments()
    {
        if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'get_modules_with_payments'))
            return false;

        return bx_srv($this->_sActive, 'get_modules_with_payments', array());
    }

    public function getProductsNames($iVendor = 0, $iLimit = 1000)
    {
        $aRet = array();
        $aModules = $this->getModulesWithPayments();
        foreach ($aModules as $sModule) {
            if(!BxDolRequest::serviceExists($sModule, 'get_products_names'))
                continue;
            $aKeys = bx_srv($sModule, 'get_products_names', array($iVendor, $iLimit));
            if (!$aKeys)
                continue;
            $aValues = array_fill(0, count($aKeys), $sModule);
            $aRet = array_merge($aRet, array_combine($aKeys, $aValues));
        }

        return $aRet;
    }

    public function getPayments()
    {
        $aPayments = array(
            '' => _t('_Select_one')
        );
        foreach($this->_aObjects as $aObject) {
            if(empty($aObject) || !is_array($aObject))
                continue;

            $aPayments[$aObject['object']] = _t($aObject['title']);
        }

        return $aPayments;
    }

    public function updateDependentModules($sModule = 'all', $bInstall = true)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'update_dependent_modules'))
            return;

        bx_srv($this->_sActive, 'update_dependent_modules', array($sModule, $bInstall));
    }

    public function getProvidersCart($iVendorId)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_providers_cart'))
    		return array();

    	$aSrvParams = array($iVendorId);
        return bx_srv($this->_sActive, 'get_providers_cart', $aSrvParams);
    }

    public function getOption($sOption)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_option'))
            return '';

    	return bx_srv($this->_sActive, 'get_option', array($sOption));
    }

    public function getOrdersUrl()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_orders_url', 'Orders'))
            return '';

    	return bx_srv($this->_sActive, 'get_orders_url', array(), 'Orders');
    }
    
    public function getOrdersCount($sType)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'get_orders_count', 'Orders'))
            return array();

        $aSrvParams = array($sType);
        return bx_srv($this->_sActive, 'get_orders_count', $aSrvParams, 'Orders');
    }

    public function getOrdersInfo($aConditions)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'get_orders_info', 'Orders'))
            return array();

        $aSrvParams = array($aConditions);
        return bx_srv($this->_sActive, 'get_orders_info', $aSrvParams, 'Orders');
    }

    public function getPendingOrdersUrl()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_pending_orders_url', 'Orders'))
            return '';

    	return bx_srv($this->_sActive, 'get_pending_orders_url', array(), 'Orders');
    }

    public function getPendingOrdersInfo($aConditions)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'get_pending_orders_info', 'Orders'))
            return array();

        $aSrvParams = array($aConditions);
        return bx_srv($this->_sActive, 'get_pending_orders_info', $aSrvParams, 'Orders');
    }

    public function getSubscriptionOrdersInfo($aConditions)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscription_orders_info', 'Subscriptions'))
            return array();

        $aSrvParams = array($aConditions);
        return bx_srv($this->_sActive, 'get_subscription_orders_info', $aSrvParams, 'Subscriptions');
    }

    public function getSubscriptionsInfo($aConditions, $bCheckInProvider = false)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscriptions_info', 'Subscriptions'))
            return array();

        $aSrvParams = array($aConditions, $bCheckInProvider);
        return bx_srv($this->_sActive, 'get_subscriptions_info', $aSrvParams, 'Subscriptions');
    }

    public function getInvoicesUrl()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_invoices_url', 'Commissions'))
            return '';

    	return bx_srv($this->_sActive, 'get_invoices_url', array(), 'Commissions');
    }

    public function getInvoicesCount($sType)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'get_invoices_count', 'Commissions'))
            return array();

        $aSrvParams = array($sType);
        return bx_srv($this->_sActive, 'get_invoices_count', $aSrvParams, 'Commissions');
    }

    public function getCartUrl($iVendor = 0)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_url', 'Cart'))
    		return '';

    	return bx_srv($this->_sActive, 'get_cart_url', array($iVendor), 'Cart');
    }

    public function getCartItemsCount()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_items_count', 'Cart'))
            return 0;

        $aSrvParams = array();
        return bx_srv($this->_sActive, 'get_cart_items_count', $aSrvParams, 'Cart');
    }
    
    public function getCartItems($iVendorId, $iModuleId)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_items', 'Cart'))
            return 0;

        $aSrvParams = array($iVendorId, $iModuleId);
        return bx_srv($this->_sActive, 'get_cart_items', $aSrvParams, 'Cart');
    }

    public function getCartItemDescriptor($iVendorId, $iModuleId, $iItemId, $iItemCount)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_item_descriptor', 'Cart'))
            return '';

    	$aSrvParams = array($iVendorId, $iModuleId, $iItemId, $iItemCount);
        return bx_srv($this->_sActive, 'get_cart_item_descriptor', $aSrvParams, 'Cart');
    }

    /**
     * Returns cart JavaScript code which should be included in the page to make "Add To Cart" and "Subscribe" buttons work properly.
     */
    public function getCartJs($sType = '', $iVendorId = 0)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_js', 'Cart'))
            return '';

        $aSrvParams = array($sType, $iVendorId);
        return bx_srv($this->_sActive, 'get_cart_js', $aSrvParams, 'Cart');
    }

    /**
     * Adds an item to cart in background mode.
     */
	public function addToCart($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $aCustom = array())
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'add_to_cart', 'Cart'))
            return array();

        $aSrvParams = array($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $aCustom);
        return bx_srv($this->_sActive, 'add_to_cart', $aSrvParams, 'Cart');
    }

    /**
     * Returns "Add To Cart" JavaScript code to use in onclick attribute.
     */
    public function getAddToCartJs($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect = false, $aCustom = array())
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_add_to_cart_js', 'Cart'))
            return array();

        $aSrvParams = array($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect, $aCustom);
        return bx_srv($this->_sActive, 'get_add_to_cart_js', $aSrvParams, 'Cart');
    }

    /**
     * Returns the whole "Add To Cart" link including HTML tag BUTTON and cart JavaScript code.
     */
    public function getAddToCartLink($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect = false, $aCustom = array())
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'get_add_to_cart_link', 'Cart'))
            return '';

        $aSrvParams = array($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect, $aCustom);
        return bx_srv($this->_sActive, 'get_add_to_cart_link', $aSrvParams, 'Cart');
    }

    /**
     * Subscribe an item in background mode.
     */
    public function subscribeWithAddons($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1, $sItemAddons = '', $sRedirect = '', $aCustom = array())
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'subscribe_with_addons', 'Subscriptions'))
            return array();

        $aSrvParams = array($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount, $sItemAddons, $sRedirect, $aCustom);
        return bx_srv($this->_sActive, 'subscribe_with_addons', $aSrvParams, 'Subscriptions');
    }

    public function getSubscriptionsUrl()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscriptions_url', 'Subscriptions'))
            return '';

    	return bx_srv($this->_sActive, 'get_subscriptions_url', array(), 'Subscriptions');
    }

    public function getSubscribeUrl($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscribe_url', 'Subscriptions'))
            return '';

    	return bx_srv($this->_sActive, 'get_subscribe_url', array($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount), 'Subscriptions');
    }

    /**
     * Returns "Subscribe" JavaScript code to use in onclick attribute.
     */
    public function getSubscribeJs($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1, $sRedirect = '', $aCustom = array())
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscribe_js', 'Subscriptions'))
            return array();

        $aSrvParams = array($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount, $sRedirect, $aCustom);
        return bx_srv($this->_sActive, 'get_subscribe_js', $aSrvParams, 'Subscriptions');
    }

    /**
     * Returns "Subscribe" JavaScript code to use in onclick attribute.
     */
    public function getSubscribeJsWithAddons($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1, $sItemAddons = '', $sRedirect = '', $aCustom = array())
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscribe_js_with_addons', 'Subscriptions'))
            return array();

        $aSrvParams = array($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount, $sItemAddons, $sRedirect, $aCustom);
        return bx_srv($this->_sActive, 'get_subscribe_js_with_addons', $aSrvParams, 'Subscriptions');
    }

    /**
     * Returns the whole "Subscribe" link including HTML tag BUTTON and cart JavaScript code.
     */
    public function getSubscribeLink($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1, $sRedirect = '', $aCustom = array())
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscribe_link', 'Subscriptions'))
            return '';

        $aSrvParams = array($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount, $sRedirect, $aCustom);
        return bx_srv($this->_sActive, 'get_subscribe_link', $aSrvParams, 'Subscriptions');
    }

    public function sendSubscriptionExpirationLetters($iPendingId, $sOrderId)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'send_subscription_expiration_letters', 'Subscriptions'))
            return '';

        $aSrvParams = array($iPendingId, $sOrderId);
        return bx_srv($this->_sActive, 'send_subscription_expiration_letters', $aSrvParams, 'Subscriptions');
    }

    public function authorizeCheckoutSingle($iVendorId, $sProvider, $aItems = array())
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'authorize_checkout'))
            return '';

        $aSrvParams = array(BX_PAYMENT_TYPE_SINGLE, $iVendorId, $sProvider, $aItems);
        return bx_srv($this->_sActive, 'authorize_checkout', $aSrvParams);
    }
    
    public function authorizeCheckoutRecurring($iVendorId, $sProvider, $aItems = array())
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'authorize_checkout'))
            return '';

        $aSrvParams = array(BX_PAYMENT_TYPE_RECURRING, $iVendorId, $sProvider, $aItems);
        return bx_srv($this->_sActive, 'authorize_checkout', $aSrvParams);
    }
    
    public function captureAuthorizedCheckoutSingle($sOrder)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'capture_authorized_checkout'))
            return '';

        $aSrvParams = array(BX_PAYMENT_TYPE_SINGLE, $sOrder);
        return bx_srv($this->_sActive, 'capture_authorized_checkout', $aSrvParams);
    }
    
    public function captureAuthorizedCheckoutRecurring($sOrder)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'capture_authorized_checkout'))
            return '';

        $aSrvParams = array(BX_PAYMENT_TYPE_RECURRING, $sOrder);
        return bx_srv($this->_sActive, 'capture_authorized_checkout', $aSrvParams);
    }

    public function initializeCheckout($iVendorId, $sProvider, $aItems = array())
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'initialize_checkout'))
            return '';

        $aSrvParams = array($iVendorId, $sProvider, $aItems);
        return bx_srv($this->_sActive, 'initialize_checkout', $aSrvParams);
    }

    public function cancelSubscription($sOrderId)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'cancel', 'Subscriptions'))
            return false;

        $aSrvParams = [$sOrderId];
        return bx_srv($this->_sActive, 'cancel', $aSrvParams, 'Subscriptions');
    }

    public function processOrder($iSellerId, $iClientId, $iModuleId, $aItems, $sType, $sOrder)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'process_order', 'Orders'))
            return false;

        $aSrvParams = array($iSellerId, $iClientId, $iModuleId, $aItems, $sType, $sOrder);
        return bx_srv($this->_sActive, 'process_order', $aSrvParams, 'Orders');
    }

    public function processOrderByPending($iPendingId, $sOrder)
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'process_order_by_pending', 'Orders'))
            return false;

        $aSrvParams = array($iPendingId, $sOrder);
        return bx_srv($this->_sActive, 'process_order_by_pending', $aSrvParams, 'Orders');
    }

    public function generateLicense()
    {
        if(!BxDolRequest::serviceExists($this->_sActive, 'generate_license'))
            return '';

        $aSrvParams = array();
        return bx_srv($this->_sActive, 'generate_license', $aSrvParams, 'Module', true);
    }
}

/** @} */
