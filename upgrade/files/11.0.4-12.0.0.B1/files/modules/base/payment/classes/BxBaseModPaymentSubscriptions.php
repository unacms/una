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

    protected $_bSingleSeller;
    protected $_iSingleSeller;

    function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        $this->_bSingleSeller = $this->_oModule->_oConfig->isSingleSeller();

        $this->_iSingleSeller = 0;
        if($this->_bSingleSeller)
            $this->_iSingleSeller = $this->_oModule->_oConfig->getSiteAdmin();
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-integration Integration
     * @subsubsection bx_base_payment-get_subscriptions_url get_subscriptions_url
     * 
     * @code bx_srv('bx_payment', 'get_subscriptions_url', [...], 'Subscriptions'); @endcode
     * 
     * Get subscriptions page URL.
     *
     * @param $iVendor (optional) integer value with vendor ID.
     * @return string with URL.
     * 
     * @see BxBaseModPaymentSubscriptions::serviceGetSubscriptionsUrl
     */
    /** 
     * @ref bx_base_payment-get_subscriptions_url "get_subscriptions_url"
     */
    public function serviceGetSubscriptionsUrl($iVendor = 0)
    {
    	if(!$this->_oModule->isLogged())
            return '';

        if($iVendor == 0)
            return $this->_oModule->_oConfig->getUrl('URL_SUBSCRIPTIONS');

    	return  bx_append_url_params($this->_oModule->_oConfig->getUrl('URL_SUBSCRIPTIONS'), array('seller_id' => $iVendor));
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-integration Integration
     * @subsubsection bx_base_payment-get_subscribe_url get_subscribe_url
     * 
     * @code bx_srv('bx_payment', 'get_subscribe_url', [...], 'Subscriptions'); @endcode
     * 
     * Get action URL to initialize subscription.
     *
     * @param $iVendor integer value with vendor ID.
     * @param $sVendorProvider string value with a name of payment provider to be used for processing.
     * @param $mixedModuleId mixed value (ID, Name or URI) determining a module from which the action was initiated.
     * @param $iItemId integer value with item ID.
     * @param $iItemCount (optional) integer value with a number of items for purchasing. It's equal to 1 in case of subscription.
     * @return string with URL.
     * 
     * @see BxBaseModPaymentSubscriptions::serviceGetSubscribeUrl
     */
    /** 
     * @ref bx_base_payment-get_subscribe_url "get_subscribe_url"
     */
    public function serviceGetSubscribeUrl($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1)
    {
    	if(!$this->_oModule->isLogged())
            return '';

        if($this->_bSingleSeller)
            $iVendorId = $this->_iSingleSeller;

    	return  bx_append_url_params($this->_oModule->_oConfig->getUrl('URL_SUBSCRIBE'), array(
    		'seller_id' => $iVendorId,
    		'seller_provider' => $sVendorProvider,
    		'module_id' => $mixedModuleId, 
    		'item_id' => $iItemId,
    		'item_count' => $iItemCount
    	));
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-integration Integration
     * @subsubsection bx_base_payment-get_subscribe_js get_subscribe_js
     * 
     * @code bx_srv('bx_payment', 'get_subscribe_js', [...], 'Subscriptions'); @endcode
     * 
     * Get JavaScript code to use in OnClick attributes of HTML elements.
     *
     * @param $iVendor integer value with vendor ID.
     * @param $sVendorProvider string value with a name of payment provider to be used for processing.
     * @param $mixedModuleId mixed value (ID, Name or URI) determining a module from which the action was initiated.
     * @param $iItemId integer value with item ID.
     * @param $iItemCount (optional) integer value with a number of items for purchasing. It's equal to 1 in case of subscription.
     * @param $sRedirect (optional) string value with redirect URL, if it's needed.
     * @return string with JavaScript code.
     * 
     * @see BxBaseModPaymentSubscriptions::serviceGetSubscribeJs
     */
    /** 
     * @ref bx_base_payment-get_subscribe_js "get_subscribe_js"
     */
    public function serviceGetSubscribeJs($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1, $sRedirect = '', $aCustom = array())
    {
        $iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
        if(empty($iModuleId))
            return '';

        if($this->_bSingleSeller)
            $iVendorId = $this->_iSingleSeller;

        return $this->_oModule->_oTemplate->displaySubscribeJs($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount, $sRedirect, $aCustom);
    }
    
    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-integration Integration
     * @subsubsection bx_base_payment-get_subscribe_js get_subscribe_js
     * 
     * @code bx_srv('bx_payment', 'get_subscribe_js', [...], 'Subscriptions'); @endcode
     * 
     * Get JavaScript code to use in OnClick attributes of HTML elements.
     *
     * @param $iVendor integer value with vendor ID.
     * @param $sVendorProvider string value with a name of payment provider to be used for processing.
     * @param $mixedModuleId mixed value (ID, Name or URI) determining a module from which the action was initiated.
     * @param $iItemId integer value with item ID.
     * @param $iItemCount (optional) integer value with a number of items for purchasing. It's equal to 1 in case of subscription.
     * @param $sItemAddons (optional) string with attached addons.
     * @param $sRedirect (optional) string value with redirect URL, if it's needed.
     * @return string with JavaScript code.
     * 
     * @see BxBaseModPaymentSubscriptions::serviceGetSubscribeJs
     */
    /** 
     * @ref bx_base_payment-get_subscribe_js "get_subscribe_js"
     */
    public function serviceGetSubscribeJsWithAddons($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1, $sItemAddons = '', $sRedirect = '', $aCustom = array())
    {
        $iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
        if(empty($iModuleId))
            return '';

        if($this->_bSingleSeller)
            $iVendorId = $this->_iSingleSeller;

        return $this->_oModule->_oTemplate->displaySubscribeJsWithAddons($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount, $sItemAddons, $sRedirect, $aCustom);
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-integration Integration
     * @subsubsection bx_base_payment-get_subscribe_link get_subscribe_link
     * 
     * @code bx_srv('bx_payment', 'get_subscribe_link', [...], 'Subscriptions'); @endcode
     * 
     * Get "Subscribe" link including HTML tag and cart JavaScript code.
     *
     * @param $iVendor integer value with vendor ID.
     * @param $sVendorProvider string value with a name of payment provider to be used for processing.
     * @param $mixedModuleId mixed value (ID, Name or URI) determining a module from which the action was initiated.
     * @param $iItemId integer value with item ID.
     * @param $iItemCount (optional) integer value with a number of items for purchasing. It's equal to 1 in case of subscription.
     * @param $sRedirect (optional) string value with redirect URL, if it's needed.
     * @return HTML string with link to display on the site.
     * 
     * @see BxBaseModPaymentSubscriptions::serviceGetSubscribeLink
     */
    /** 
     * @ref bx_base_payment-get_subscribe_link "get_subscribe_link"
     */
    public function serviceGetSubscribeLink($iVendorId, $sVendorProvider, $mixedModuleId, $iItemId, $iItemCount = 1, $sRedirect = '', $aCustom = array())
    {
        $iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
        if(empty($iModuleId))
            return '';

        if($this->_bSingleSeller)
            $iVendorId = $this->_iSingleSeller;

        return $this->_oModule->_oTemplate->displaySubscribeLink($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount, $sRedirect, $aCustom);
    }
}

/** @} */
