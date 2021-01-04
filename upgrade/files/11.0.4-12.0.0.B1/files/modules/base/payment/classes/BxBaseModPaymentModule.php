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

class BxBaseModPaymentModule extends BxBaseModGeneralModule
{
    protected $_sLangsPrefix;

    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);

        $this->_sLangsPrefix = $this->_oConfig->getPrefix('langs');
    }

    public function serviceGetSafeServices()
    {
        return array();
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-integration Integration
     * @subsubsection bx_base_payment-update_dependent_modules update_dependent_modules
     * 
     * @code bx_srv('bx_payment', 'update_dependent_modules', [...]); @endcode
     * 
     * Get payments dependent modules and save them. 
     *
     * @param $sModule (optional) string value with module name. All modules are used by default.
     * @param $bInstall (optional) boolean value determining whether the install or uninstall operation is performed.
     * 
     * @see BxBaseModPaymentModule::serviceUpdateDependentModules
     */
    /** 
     * @ref bx_base_payment-update_dependent_modules "update_dependent_modules"
     */
    public function serviceUpdateDependentModules($sModule = 'all', $bInstall = true)
    {
    	$aModules = $sModule == 'all' ? $this->_oDb->getModulesBy(array('type' => 'modules'), false) : array($this->_oDb->getModuleByName($sModule, false));

        foreach($aModules as $aModule) {
            if(empty($aModule) || empty($aModule['name']))
                continue;

            $mixedData = $this->callGetPaymentData($aModule['name']);
            if($mixedData === false)
                continue;

            $sMethodName = $bInstall ? 'insertModule' : 'deleteModule';
            $this->_oDb->$sMethodName($mixedData);
        }
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-other Other
     * @subsubsection bx_base_payment-generate_license generate_license
     * 
     * @code bx_srv('bx_payment', 'generate_license'); @endcode
     * 
     * Generate license. 
     *
     * @return a string with license.
     * 
     * @see BxBaseModPaymentModule::serviceGenerateLicense
     */
    /** 
     * @ref bx_base_payment-generate_license "generate_license"
     */
    public function serviceGenerateLicense()
    {
        return $this->_oConfig->getLicense();
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-other Other
     * @subsubsection bx_base_payment-get_currency_info get_currency_info
     * 
     * @code bx_srv('bx_payment', 'get_currency_info', [...]); @endcode
     * 
     * Get default currency info (sign and code). 
     *
     * @return an array with currency info.
     * 
     * @see BxBaseModPaymentModule::serviceGetCurrencyInfo
     */
    /** 
     * @ref bx_base_payment-get_currency_info "get_currency_info"
     */
    public function serviceGetCurrencyInfo()
    {
        return array(
            'sign' => $this->_oConfig->getDefaultCurrencySign(),
            'code' => $this->_oConfig->getDefaultCurrencyCode()
        );
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-other Other
     * @subsubsection bx_base_payment-get_option get_option
     * 
     * @code bx_srv('bx_payment', 'get_option', [...]); @endcode
     * 
     * Get value of payment provider configuration option. 
     *
     * @param $sOption string value with option name. 
     * @return string with option value.
     * 
     * @see BxBaseModPaymentModule::serviceGetOption
     */
    /** 
     * @ref bx_base_payment-get_option "get_option"
     */
    public function serviceGetOption($sOption)
    {
    	$sMethod = 'get' . bx_gen_method_name($sOption);
    	if(method_exists($this->_oConfig, $sMethod))
    		return $this->_oConfig->$sMethod();

    	return $this->_oDb->getParam($this->_oConfig->getPrefix('options') . $sOption);
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-other Other
     * @subsubsection bx_base_payment-get_provider get_provider
     * 
     * @code bx_srv('bx_payment', 'get_provider', [...]); @endcode
     * 
     * Get payment provider object by its name and vendor ID. 
     *
     * @param $sProvider string value with provider name. 
     * @param $mixedVendorId mixed value with vendor ID.
     * @return provider object, instance of BxBaseModPaymentProvider.
     * 
     * @see BxBaseModPaymentModule::serviceGetProvider
     */
    /** 
     * @ref bx_base_payment-get_provider "get_provider"
     */
    public function serviceGetProvider($sProvider, $mixedVendorId = BX_PAYMENT_EMPTY_ID)
    {
        return $this->getObjectProvider($sProvider, $mixedVendorId);
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-other Other
     * @subsubsection bx_base_payment-get_providers_cart get_providers_cart
     * 
     * @code bx_srv('bx_payment', 'get_providers_cart', [...]); @endcode
     * 
     * Get list of available payment providers which can process single time payments via Shopping Cart. 
     *
     * @param $iVendorId integer value with vendor ID. 
     * @return an array with special format.
     * 
     * @see BxBaseModPaymentModule::serviceGetProvidersCart
     */
    /** 
     * @ref bx_base_payment-get_providers_cart "get_providers_cart"
     */
    public function serviceGetProvidersCart($iVendorId)
    {
        $aVendorProviders = $this->_oDb->getVendorInfoProvidersSingle($iVendorId);

        $aResult = array();
        foreach($aVendorProviders as $aProvider) {
            $aProvider['caption_cart'] = _t($this->_sLangsPrefix . 'txt_cart_' . $aProvider['name']);

            $aResult[] = $aProvider;
        }

        return $aResult;
    }

    public function isSingleSeller()
    {
        return $this->_oConfig->isSingleSeller();
    }

    public function getVendorInfo($iUserId)
    {
        return array_merge($this->getProfileInfo($iUserId), array(
            'currency_code' => $this->_oConfig->getDefaultCurrencyCode(),
            'currency_sign' => $this->_oConfig->getDefaultCurrencySign()
        ));
    }

	public function getObjectJoin()
    {
        $sClassName = $this->_oConfig->getClassPrefix() . 'Join';
        if(!isset($GLOBALS['bxDolClasses'][$sClassName])) {
        	bx_import('Join', $this->_aModule);
            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName();
        }

        return $GLOBALS['bxDolClasses'][$sClassName];
    }

	public function getObjectCart()
    {
        $sClassName = $this->_oConfig->getClassPrefix() . 'Cart';
        if(!isset($GLOBALS['bxDolClasses'][$sClassName])) {
        	bx_import('Cart', $this->_aModule);
            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName();
        }

        return $GLOBALS['bxDolClasses'][$sClassName];
    }

    public function getObjectOrders()
    {
    	$sClassName = $this->_oConfig->getClassPrefix() . 'Orders';
        if(!isset($GLOBALS['bxDolClasses'][$sClassName])) {
        	bx_import('Orders', $this->_aModule);
            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName();
        }

        return $GLOBALS['bxDolClasses'][$sClassName];
    }

	public function getObjectDetails()
    {
    	$sClassName = $this->_oConfig->getClassPrefix() . 'Details';
        if(!isset($GLOBALS['bxDolClasses'][$sClassName])) {
        	bx_import('Details', $this->_aModule);
            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName();
        }

        return $GLOBALS['bxDolClasses'][$sClassName];
    }

    public function getObjectSubscriptions()
    {
    	$sClassName = $this->_oConfig->getClassPrefix() . 'Subscriptions';
        if(!isset($GLOBALS['bxDolClasses'][$sClassName])) {
        	bx_import('Subscriptions', $this->_aModule);
            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName();
        }

        return $GLOBALS['bxDolClasses'][$sClassName];
    }

    public function getObjectProvider($sProvider, $mixedVendorId = BX_PAYMENT_EMPTY_ID)
    {
        $aProvider = $this->_oDb->getProviders(array('type' => 'by_name', 'name' => $sProvider));
        if(empty($aProvider) || !is_array($aProvider) || empty($aProvider['class_name']))
            return false;

        if(is_numeric($mixedVendorId) && (int)$mixedVendorId != BX_PAYMENT_EMPTY_ID) {
            $aProvider['vendor'] = (int)$mixedVendorId;
            $aProvider['options'] = $this->_oDb->getOptions((int)$mixedVendorId, $aProvider['id']);
        }

        $sClassPath = !empty($aProvider['class_file']) ? BX_DIRECTORY_PATH_ROOT . $aProvider['class_file'] : $this->_oConfig->getClassPath() . $aProvider['class_name'] . '.php';
        if(!file_exists($sClassPath))
            return false;

        require_once($sClassPath);
        return new $aProvider['class_name']($aProvider);
    }

    public function callGetPaymentData($mixedModule)
    {
    	$sMethod = 'get_payment_data';
		if(!BxDolRequest::serviceExists($mixedModule, $sMethod)) 
			return false;

		return BxDolService::call($mixedModule, $sMethod);
    }

    public function callGetCartItem($mixedModule, $aParams)
    {
    	return BxDolService::call($mixedModule, 'get_cart_item', $aParams);
    }

    public function callGetCartItems($mixedModule, $aParams)
    {
    	return BxDolService::call($mixedModule, 'get_cart_items', $aParams);
    }

    public function callRegisterCartItem($mixedModule, $aParams)
    {
    	return BxDolService::call($mixedModule, 'register_cart_item', $aParams);
    }

    public function callReregisterCartItem($mixedModule, $aParams)
    {
    	return BxDolService::call($mixedModule, 'reregister_cart_item', $aParams);
    }

	public function callRegisterSubscriptionItem($mixedModule, $aParams)
    {
    	return BxDolService::call($mixedModule, 'register_subscription_item', $aParams);
    }
    
    public function callReregisterSubscriptionItem($mixedModule, $aParams)
    {
    	return BxDolService::call($mixedModule, 'reregister_subscription_item', $aParams);
    }

    public function callUnregisterCartItem($mixedModule, $aParams)
    {
        return BxDolService::call($mixedModule, 'unregister_cart_item', $aParams);
    }

    public function callUnregisterSubscriptionItem($mixedModule, $aParams)
    {
        return BxDolService::call($mixedModule, 'unregister_subscription_item', $aParams);
    }

    public function callCancelSubscriptionItem($mixedModule, $aParams)
    {
    	return BxDolService::call($mixedModule, 'cancel_subscription_item', $aParams);
    }

    public function log($mixedContents, $sSection = '', $sTitle = '')
    {
        if(is_array($mixedContents))
            $mixedContents = var_export($mixedContents, true);	
        else if(is_object($mixedContents))
            $mixedContents = json_encode($mixedContents);

        if(empty($sSection))
            $sSection = "Core";

        $sTitle .= "\n";

        bx_log('sys_payments', ":\n[" . $sSection . "] " . $sTitle . $mixedContents);
    }
}

/** @} */
