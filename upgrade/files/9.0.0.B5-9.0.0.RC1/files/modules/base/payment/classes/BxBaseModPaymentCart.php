<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModPaymentCart extends BxDol
{
	protected $MODULE;
	protected $_oModule;

	function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

	public function serviceGetCartUrl($iVendor = 0)
    {
    	if(!$this->_oModule->isLogged())
            return '';

		if($iVendor == 0)
    		return $this->_oModule->_oConfig->getUrl('URL_CARTS');

    	return  bx_append_url_params($this->_oModule->_oConfig->getUrl('URL_CART'), array('seller_id' => $iVendor));
    }

    public function serviceGetCartJs($sType = '')
    {
        return $this->_oModule->_oTemplate->displayCartJs($sType);
    }

    public function serviceGetAddToCartJs($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect = false)
    {
		$iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
        if(empty($iModuleId))
            return '';

        return $this->_oModule->_oTemplate->displayAddToCartJs($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect);
    }

	public function serviceGetAddToCartLink($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect = false)
    {
        $iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
        if(empty($iModuleId))
            return '';

		return $this->_oModule->_oTemplate->displayAddToCartLink($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect);
    }

	public function serviceGetSubscriptionsUrl($iVendor = 0)
    {
    	if(!$this->_oModule->isLogged())
            return '';

		if($iVendor == 0)
    		return $this->_oModule->_oConfig->getUrl('URL_SUBSCRIPTIONS');

    	return  bx_append_url_params($this->_oModule->_oConfig->getUrl('URL_SUBSCRIPTIONS'), array('seller_id' => $iVendor));
    }

	public function serviceGetSubscribeUrl($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1)
    {
    	if(!$this->_oModule->isLogged())
            return '';

    	return  bx_append_url_params($this->_oModule->_oConfig->getUrl('URL_SUBSCRIBE'), array(
    		'seller_id' => $iVendorId,
    		'seller_provider' => $sVendorProvider,
    		'module_id' => $mixedModuleId, 
    		'item_id' => $iItemId,
    		'item_count' => $iItemCount
    	));
    }

	public function serviceGetSubscribeJs($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1, $sRedirect = '')
    {
		$iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
        if(empty($iModuleId))
            return '';

        return $this->_oModule->_oTemplate->displaySubscribeJs($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount, $sRedirect);
    }

	public function serviceGetSubscribeLink($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1, $sRedirect = '')
    {
        $iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
        if(empty($iModuleId))
            return '';

		return $this->_oModule->_oTemplate->displaySubscribeLink($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount, $sRedirect);
    }

	public function serviceGetCartItemDescriptor($iVendorId, $iModuleId, $iItemId, $iItemCount)
	{
		return $this->_oModule->_oConfig->descriptorA2S(array($iVendorId, $iModuleId, $iItemId, $iItemCount));
	}

    public function serviceGetCartItemsCount($iUserId = 0)
    {
    	$iUserId = !empty($iUserId) ? $iUserId : $this->_oModule->getProfileId();
        if(empty($iUserId))
            return 0;

        $aInfo = $this->getInfo(BX_PAYMENT_TYPE_SINGLE, $iUserId);

        $iCount = 0;
        foreach($aInfo as $iVendorId => $aVendorCart)
            $iCount += $aVendorCart['items_count'];

        return $iCount;
    }

	protected function _parseByVendor($iUserId)
    {
        $sItems = $this->_oModule->_oDb->getCartItems($iUserId);
        return $this->_reparseBy($this->_oModule->_oConfig->descriptorsM2A($sItems), 'vendor_id');
    }

    protected function _parseByModule($iUserId)
    {
        $sItems = $this->_oModule->_oDb->getCartItems($iUserId);
        return $this->_reparseBy($this->_oModule->_oConfig->descriptorsM2A($sItems), 'module_id');
    }

	protected function _reparseBy($aItems, $sKey)
    {
        $aResult = array();
        foreach($aItems as $aItem)
            if(isset($aItem[$sKey]))
                $aResult[$aItem[$sKey]][] = $aItem;

        return $aResult;
    }
}

/** @} */
