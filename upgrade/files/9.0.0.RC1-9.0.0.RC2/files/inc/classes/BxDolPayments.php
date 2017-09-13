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

    public function isActive()
    {
    	if(empty($this->_sActive))
    		return false;

    	if(!BxDolModuleQuery::getInstance()->isModuleByName($this->_sActive))
    		return false;

    	return true;
    }

	public function getDetailsUrl()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_details_url', 'Details'))
    		return '';

    	return BxDolService::call($this->_sActive, 'get_details_url', array(), 'Details');
    }

    public function isAcceptingPayments($iVendorId, $sPaymentType = '')
    {
    	if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'is_accepting_payments'))
    		return false;

    	$aSrvParams = array($iVendorId, $sPaymentType);
        return BxDolService::call($this->_sActive, 'is_accepting_payments', $aSrvParams);
    }

	public function isPaymentProvider($iVendorId, $sVendorProvider, $sPaymentType = '')
    {
    	if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'is_payment_provider'))
    		return false;

    	$aSrvParams = array($iVendorId, $sVendorProvider, $sPaymentType);
        return BxDolService::call($this->_sActive, 'is_payment_provider', $aSrvParams);
    }

	public function getPaymentProvider($iVendorId, $sVendorProvider, $sPaymentType = '')
    {
    	if(empty($this->_sActive) || !BxDolRequest::serviceExists($this->_sActive, 'get_payment_provider'))
    		return false;

    	$aSrvParams = array($iVendorId, $sVendorProvider, $sPaymentType);
        return BxDolService::call($this->_sActive, 'get_payment_provider', $aSrvParams);
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

		BxDolService::call($this->_sActive, 'update_dependent_modules', array($sModule, $bInstall));
    }

    public function getProvidersCart($iVendorId)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_providers_cart'))
    		return array();

    	$aSrvParams = array($iVendorId);
        return BxDolService::call($this->_sActive, 'get_providers_cart', $aSrvParams);
    }

	public function getOption($sOption)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_option'))
    		return '';

    	return BxDolService::call($this->_sActive, 'get_option', array($sOption));
    }

    public function getOrdersUrl()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_orders_url', 'Orders'))
    		return '';

    	return BxDolService::call($this->_sActive, 'get_orders_url', array(), 'Orders');
    }

    public function getOrdersCount($sType)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_orders_count', 'Orders'))
    		return array();

		$aSrvParams = array($sType);
		return BxDolService::call($this->_sActive, 'get_orders_count', $aSrvParams, 'Orders');
    }

    public function getOrdersInfo($aConditions)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_orders_info', 'Orders'))
    		return array();

		$aSrvParams = array($aConditions);
		return BxDolService::call($this->_sActive, 'get_orders_info', $aSrvParams, 'Orders');
    }

    public function getPendingOrdersInfo($aConditions)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_pending_orders_info', 'Orders'))
    		return array();

		$aSrvParams = array($aConditions);
		return BxDolService::call($this->_sActive, 'get_pending_orders_info', $aSrvParams, 'Orders');
    }

    public function getCartUrl($iVendor = 0)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_url', 'Cart'))
    		return '';

    	return BxDolService::call($this->_sActive, 'get_cart_url', array($iVendor), 'Cart');
    }

    public function getCartItemsCount()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_items_count', 'Cart'))
    		return 0;

		$aSrvParams = array();
		return BxDolService::call($this->_sActive, 'get_cart_items_count', $aSrvParams, 'Cart');
    }

    public function getCartItemDescriptor($iVendorId, $iModuleId, $iItemId, $iItemCount)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_item_descriptor', 'Cart'))
    		return '';

    	$aSrvParams = array($iVendorId, $iModuleId, $iItemId, $iItemCount);
		return BxDolService::call($this->_sActive, 'get_cart_item_descriptor', $aSrvParams, 'Cart');
    }

    /**
     * Returns cart JavaScript code which should be included in the page to make "Add To Cart" and "Subscribe" buttons work properly.
     */
    public function getCartJs($sType = '')
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_js', 'Cart'))
			return '';

		$aSrvParams = array($sType);
		return BxDolService::call($this->_sActive, 'get_cart_js', $aSrvParams, 'Cart');
    }

    /**
     * Adds an item to cart in background mode.
     */
	public function addToCart($iVendorId, $mixedModuleId, $iItemId, $iItemCount)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'add_to_cart', 'Cart'))
			return array();

		$aSrvParams = array($iVendorId, $mixedModuleId, $iItemId, $iItemCount);
		return BxDolService::call($this->_sActive, 'add_to_cart', $aSrvParams, 'Cart');
    }

    /**
     * Returns "Add To Cart" JavaScript code to use in onclick attribute.
     */
    public function getAddToCartJs($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect = false)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_add_to_cart_js', 'Cart'))
			return array();

		$aSrvParams = array($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect);
		return BxDolService::call($this->_sActive, 'get_add_to_cart_js', $aSrvParams, 'Cart');
    }

    /**
     * Returns the whole "Add To Cart" link including HTML tag BUTTON and cart JavaScript code.
     */
    public function getAddToCartLink($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect = false)
    {
		if(!BxDolRequest::serviceExists($this->_sActive, 'get_add_to_cart_link', 'Cart'))
			return '';

		$aSrvParams = array($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect);
		return BxDolService::call($this->_sActive, 'get_add_to_cart_link', $aSrvParams, 'Cart');
    }

    public function getSubscriptionsUrl()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscriptions_url', 'Subscriptions'))
    		return '';

    	return BxDolService::call($this->_sActive, 'get_subscriptions_url', array(), 'Subscriptions');
    }

	public function getSubscribeUrl($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscribe_url', 'Cart'))
    		return '';

    	return BxDolService::call($this->_sActive, 'get_subscribe_url', array($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount), 'Cart');
    }

	/**
     * Returns "Subscribe" JavaScript code to use in onclick attribute.
     */
    public function getSubscribeJs($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscribe_js', 'Subscriptions'))
			return array();

		$aSrvParams = array($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount);
		return BxDolService::call($this->_sActive, 'get_subscribe_js', $aSrvParams, 'Subscriptions');
    }

    /**
     * Returns the whole "Subscribe" link including HTML tag BUTTON and cart JavaScript code.
     */
    public function getSubscribeLink($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1)
    {
		if(!BxDolRequest::serviceExists($this->_sActive, 'get_subscribe_link', 'Subscriptions'))
			return '';

		$aSrvParams = array($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount);
		return BxDolService::call($this->_sActive, 'get_subscribe_link', $aSrvParams, 'Subscriptions');
    }

    public function initializeCheckout($iVendorId, $sProvider, $aItems = array())
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'initialize_checkout'))
			return '';

		$aSrvParams = array($iVendorId, $sProvider, $aItems);
		return BxDolService::call($this->_sActive, 'initialize_checkout', $aSrvParams);
    }

    /**
     * Isn't used anywhere for now. Is needed when license 'prolong' is requested from license keeping module like Market and ACL.  
     */
	public function prolongSubscription($sOrderId)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'prolong_subscription'))
			return '';

		$aSrvParams = array($sOrderId);
		return BxDolService::call($this->_sActive, 'prolong_subscription', $aSrvParams);
    }
}

/** @} */
