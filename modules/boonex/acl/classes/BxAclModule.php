<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    PaidLevels Paid Levels
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolAcl');

define('BX_ACL_LICENSE_TYPE_SINGLE', 'single'); //--- one-time payment license
define('BX_ACL_LICENSE_TYPE_RECURRING', 'recurring'); //--- recurring payment license

class BxAclModule extends BxDolModule
{
    /**
     * Constructor
     */
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

	/**
     * ACTION METHODS
     */

    /**
     * SERVICE METHODS
     */
    
    /**
     * @page service Service Calls
     * @section bx_acl Paid Levels
     * @subsection bx_acl-page_blocks Page Blocks
     * @subsubsection bx_acl-get_block_view get_block_view
     * 
     * @code bx_srv('bx_acl', 'get_block_view', [...]); @endcode
     * 
     * Get page block with a list of available ACL levels to purchase or get for Free. List is represented as table.
     *
     * @return HTML string with a list of ACL levels to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML. On error empty string is returned.
     * 
     * @see BxAclModule::serviceGetBlockView
     */
    /** 
     * @ref bx_acl-get_block_view "get_block_view"
     */
	public function serviceGetBlockView()
	{
		$sGrid = $this->_oConfig->getGridObject('view');
		$oGrid = BxDolGrid::getObjectInstance($sGrid);
        if(!$oGrid)
            return '';

        $this->_oTemplate->addCss(array('view.css'));
		return array(
            'content' => $oGrid->getCode()
        );
	}

	/**
     * @page service Service Calls
     * @section bx_acl Paid Levels
     * @subsection bx_acl-page_blocks Page Blocks
     * @subsubsection bx_acl-get_membership_actions get_membership_actions
     * 
     * @code bx_srv('bx_acl', 'get_membership_actions', [...]); @endcode
     * 
     * Get page block with a list of current membership level's actions.
     *
     * @return HTML string with a list of actions to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML. On error empty string is returned.
     * 
     * @see BxAclModule::serviceGetMembershipActions
     */
    /** 
     * @ref bx_acl-get_membership_actions "get_membership_actions"
     */
	public function serviceGetMembershipActions($iProfileId)
	{
		if($iProfileId != $this->getUserId())
			return '';

		return $this->_oTemplate->displayMembershipActions($iProfileId);
	}

	/**
     * @page service Service Calls
     * @section bx_acl Paid Levels
     * @subsection bx_acl-payments Payments
     * @subsubsection bx_acl-get_payment_data get_payment_data
     * 
     * @code bx_srv('bx_acl', 'get_payment_data', [...]); @endcode
     * 
     * Get an array with module's description. Is needed for payments processing module.
     * 
     * @return an array with module's description.
     * 
     * @see BxAclModule::serviceGetPaymentData
     */
    /** 
     * @ref bx_acl-get_payment_data "get_payment_data"
     */
	public function serviceGetPaymentData()
    {
        return $this->_aModule;
    }

    /**
     * @page service Service Calls
     * @section bx_acl Paid Levels
     * @subsection bx_acl-payments Payments
     * @subsubsection bx_acl-get_cart_item get_cart_item
     * 
     * @code bx_srv('bx_acl', 'get_cart_item', [...]); @endcode
     * 
     * Get an array with level's description. Is used in Shopping Cart in payments processing module.
     * 
     * @param $iItemId level's ID.
     * @return an array with level's description. Empty array is returned if something is wrong.
     * 
     * @see BxAclModule::serviceGetCartItem
     */
    /** 
     * @ref bx_acl-get_cart_item "get_cart_item"
     */
    public function serviceGetCartItem($iItemId)
    {
    	$CNF = &$this->_oConfig->CNF;

        if(!$iItemId)
			return array();

		$aItem = $this->_oDb->getPrices(array('type' => 'by_id_full', 'value' => $iItemId));
        if(empty($aItem) || !is_array($aItem))
			return array();

		return array (
            'id' => $aItem['id'],
            'author_id' => $this->_oConfig->getOwner(),
            'name' => $aItem['name'],
            'title' => _t('_bx_acl_txt_cart_item_title', _t($aItem['level_name']), $aItem['period'], $aItem['period_unit']),
            'description' => _t($aItem['level_description']),
            'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_VIEW']),
            'price_single' => $aItem['price'],
            'price_recurring' => $aItem['price'],
            'period_recurring' => $aItem['period'],
            'period_unit_recurring' => $aItem['period_unit'],
            'trial_recurring' => $aItem['trial']
        );
    }

    /**
     * @page service Service Calls
     * @section bx_acl Paid Levels
     * @subsection bx_acl-payments Payments
     * @subsubsection bx_acl-get_cart_items get_cart_items
     * 
     * @code bx_srv('bx_acl', 'get_cart_items', [...]); @endcode
     * 
     * Get an array with levels' descriptions by seller. Is used in Manual Order Processing in payments processing module.
     * 
     * @param $iSellerId seller ID.
     * @return an array with levels' descriptions. Empty array is returned if something is wrong or seller doesn't have any paid level.
     * 
     * @see BxAclModule::serviceGetCartItems
     */
    /** 
     * @ref bx_acl-get_cart_items "get_cart_items"
     */
    public function serviceGetCartItems($iSellerId)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iSellerIdSetting = $this->_oConfig->getOwner();
    	if(empty($iSellerId) || ($iSellerId != $iSellerIdSetting && !isAdmin()))
    	    return array();

        $aItems = $this->_oDb->getPrices(array('type' => 'all_full'));
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_VIEW']);

        $aResult = array();
        foreach($aItems as $aItem)
            $aResult[] = array(
                'id' => $aItem['id'],
                'author_id' => $iSellerIdSetting,
                'name' => $aItem['name'],
                'title' => _t('_bx_acl_txt_cart_item_title', _t($aItem['level_name']), $aItem['period'], $aItem['period_unit']),
                'description' => _t($aItem['level_description']),
                'url' => $sUrl,
                'price_single' => $aItem['price'],
                'price_recurring' => $aItem['price'],
                'period_recurring' => $aItem['period'],
                'period_unit_recurring' => $aItem['period_unit'],
                'trial_recurring' => $aItem['trial']
           );

        return $aResult;
    }

    /**
     * @page service Service Calls
     * @section bx_acl Paid Levels
     * @subsection bx_acl-payments Payments
     * @subsubsection bx_acl-register_cart_item register_cart_item
     * 
     * @code bx_srv('bx_acl', 'register_cart_item', [...]); @endcode
     * 
     * Register a processed single time payment inside the Paid Levels module. Is called with payment processing module after the payment was registered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return an array with purchased prodict's description. Empty array is returned if something is wrong.
     * 
     * @see BxAclModule::serviceRegisterCartItem
     */
    /** 
     * @ref bx_acl-register_cart_item "register_cart_item"
     */
    public function serviceRegisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_ACL_LICENSE_TYPE_SINGLE);
    }

    /**
     * @page service Service Calls
     * @section bx_acl Paid Levels
     * @subsection bx_acl-payments Payments
     * @subsubsection bx_acl-register_subscription_item register_subscription_item
     * 
     * @code bx_srv('bx_acl', 'register_subscription_item', [...]); @endcode
     * 
     * Register a processed subscription (recurring payment) inside the Paid Levels module. Is called with payment processing module after the subscription was registered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return an array with subscribed prodict's description. Empty array is returned if something is wrong.
     * 
     * @see BxAclModule::serviceRegisterSubscriptionItem
     */
    /** 
     * @ref bx_acl-register_subscription_item "register_subscription_item"
     */
    public function serviceRegisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
		return $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_ACL_LICENSE_TYPE_RECURRING);
    }

    /**
     * @page service Service Calls
     * @section bx_acl Paid Levels
     * @subsection bx_acl-payments Payments
     * @subsubsection bx_acl-unregister_cart_item unregister_cart_item
     * 
     * @code bx_srv('bx_acl', 'unregister_cart_item', [...]); @endcode
     * 
     * Unregister an earlier processed single time payment inside the Paid Levels module. Is called with payment processing module after the payment was unregistered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return boolean value determining where the payment was unregistered or not.
     * 
     * @see BxAclModule::serviceUnregisterCartItem
     */
    /** 
     * @ref bx_acl-unregister_cart_item "unregister_cart_item"
     */
    public function serviceUnregisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_ACL_LICENSE_TYPE_SINGLE);
    }

    /**
     * @page service Service Calls
     * @section bx_acl Paid Levels
     * @subsection bx_acl-payments Payments
     * @subsubsection bx_acl-unregister_subscription_item unregister_subscription_item
     * 
     * @code bx_srv('bx_acl', 'unregister_subscription_item', [...]); @endcode
     * 
     * Unregister an earlier processed subscription (recurring payment) inside the Paid Levels module. Is called with payment processing module after the subscription was unregistered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return boolean value determining where the subscription was unregistered or not.
     * 
     * @see BxAclModule::serviceUnregisterSubscriptionItem
     */
    /** 
     * @ref bx_acl-unregister_subscription_item "unregister_subscription_item"
     */
    public function serviceUnregisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
    	return $this->_serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_ACL_LICENSE_TYPE_RECURRING); 
    }

    /**
     * @page service Service Calls
     * @section bx_acl Paid Levels
     * @subsection bx_acl-payments Payments
     * @subsubsection bx_acl-cancel_subscription_item cancel_subscription_item
     * 
     * @code bx_srv('bx_acl', 'cancel_subscription_item', [...]); @endcode
     * 
     * Cancel an earlier processed subscription (recurring payment) inside the Paid Levels module. Is called with payment processing module after the subscription was canceled there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @return boolean value determining where the subscription was canceled or not.
     * 
     * @see BxAclModule::serviceCancelSubscriptionItem
     */
    /** 
     * @ref bx_acl-cancel_subscription_item "cancel_subscription_item"
     */
    public function serviceCancelSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder)
    {
    	//TODO: Do something if it's necessary.
    	return true;
    }

    protected function _serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, $sType)
    {
    	$aItem = $this->serviceGetCartItem($iItemId);
        if(empty($aItem) || !is_array($aItem))
			return array();

        $aItemInfo = $this->_oDb->getPrices(array('type' => 'by_id', 'value' => $iItemId));

        $iPeriod = (int)$aItemInfo['period'];
        $sPeriodUnit = $aItemInfo['period_unit'];
		if($sType == BX_ACL_LICENSE_TYPE_RECURRING && (int)$aItemInfo['trial'] > 0) {
		    $iPeriod = (int)$aItemInfo['trial'];
		    $sPeriodUnit = 'day';
		}

        if(!BxDolAcl::getInstance()->setMembership($iClientId, $aItemInfo['level_id'], array('period' => $iPeriod, 'period_unit' => $sPeriodUnit), false, $sLicense))
            return array();

        return $aItem;
    }

    protected function _serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, $sType)
    {
    	$aItemInfo = $this->_oDb->getPrices(array('type' => 'by_id', 'value' => $iItemId));
    	if(empty($aItemInfo) || !is_array($aItemInfo))
			return false;

    	return BxDolAcl::getInstance()->unsetMembership($iClientId, $aItemInfo['level_id'], $sLicense);
    }

    /**
     * COMMON METHODS
     */
	public function getUserId()
    {
        return isLogged() ? bx_get_logged_profile_id() : 0;
    }

    public function getUserInfo($iUserId = 0)
    {
        $oProfile = BxDolProfile::getInstance($iUserId);
        if (!$oProfile)
            $oProfile = BxDolProfileUndefined::getInstance();

        return array(
            $oProfile->getDisplayName(),
            $oProfile->getUrl(),
            $oProfile->getThumb(),
            $oProfile->getUnit()
        );
    }
}

/** @} */
