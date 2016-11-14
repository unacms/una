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

class BxBaseModPaymentSubscriptions extends BxDol
{
	protected $MODULE;
	protected $_oModule;

	function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
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

	public function serviceGetSubscribeJs($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1)
    {
		$iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
        if(empty($iModuleId))
            return '';

        return $this->_oModule->_oTemplate->displaySubscribeJs($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount);
    }

	public function serviceGetSubscribeLink($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1)
    {
        $iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
        if(empty($iModuleId))
            return '';

		return $this->_oModule->_oTemplate->displaySubscribeLink($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount);
    }
}

/** @} */
