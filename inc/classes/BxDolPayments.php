<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolPayments extends BxDol implements iBxDolSingleton
{
	protected $_oDb;

	protected $_aObjects;
	protected $_sActive;

    public function __construct()
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

    public function getProviders($iVendorId, $sProvider = '')
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_providers'))
    		return array();

    	$aSrvParams = array($iVendorId, $sProvider);
        return BxDolService::call($this->_sActive, 'get_providers', $aSrvParams);
    }

	public function getOption($sOption)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_option'))
    		return '';

    	return BxDolService::call($this->_sActive, 'get_option', array($sOption));
    }

    public function getOrdersLink()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_orders_link', 'Orders'))
    		return '';

    	return BxDolService::call($this->_sActive, 'get_orders_link', array(), 'Orders');
    }

    public function getOrdersCount($sType)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_orders_count_new', 'Orders'))
    		return array();

		$aSrvParams = array($sType);
		return BxDolService::call($this->_sActive, 'get_orders_count_new', $aSrvParams, 'Orders');
    }

    public function getCartLink()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_link', 'Cart'))
    		return '';

    	return BxDolService::call($this->_sActive, 'get_cart_link', array(), 'Cart');
    }

    public function getCartItemsCount()
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_items_count', 'Cart'))
    		return array();

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
    
    public function getCartJs($bWrapped = true)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_cart_js', 'Cart'))
			return '';

		$aSrvParams = array($bWrapped);
		return BxDolService::call($this->_sActive, 'get_cart_js', $aSrvParams, 'Cart');
    }

    public function getAddToCartJs($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect = false, $bWrapped = true)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'get_add_to_cart_js', 'Cart'))
			return array();

		$aSrvParams = array($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect, $bWrapped);
		return BxDolService::call($this->_sActive, 'get_add_to_cart_js', $aSrvParams, 'Cart');
    }

    public function getAddToCartLink($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect = false)
    {
		if(!BxDolRequest::serviceExists($this->_sActive, 'get_add_to_cart_link', 'Cart'))
			return '';

		$aSrvParams = array($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect);
		return BxDolService::call($this->_sActive, 'get_add_to_cart_link', $aSrvParams, 'Cart');
    }

    public function initializeCheckout($iVendorId, $sProvider, $aItems = array())
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'initialize_checkout'))
			return '';

		$aSrvParams = array($iVendorId, $sProvider, $aItems);
		return BxDolService::call($this->_sActive, 'initialize_checkout', $aSrvParams);
    }

	public function prolongSubscription($sOrderId)
    {
    	if(!BxDolRequest::serviceExists($this->_sActive, 'prolong_subscription'))
			return '';

		$aSrvParams = array($sOrderId);
		return BxDolService::call($this->_sActive, 'prolong_subscription', $aSrvParams);
    }
}

/** @} */
