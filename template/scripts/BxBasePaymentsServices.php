<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to Payments.
 */
class BxBasePaymentsServices extends BxDol
{
    protected $_sPrelistsCurrency;

    public function __construct()
    {
        parent::__construct();

        $this->_sPrelistsCurrency = 'Currency';
    }

    public function serviceGetOptionsCurrencyCodeDefault()
    {
        $aCurrencies = BxDolForm::getDataItems($this->_sPrelistsCurrency);

        $aResult = array();
        foreach($aCurrencies as $sKey => $sValue)
            $aResult[] = array(
                'key' => $sKey,
                'value' => $sValue
            );

        return $aResult;
    }

    public function serviceGetOptionsCurrencySignDefault()
    {
        $aCurrencies = BxDolForm::getDataItems($this->_sPrelistsCurrency, false, BX_DATA_VALUES_ALL);

        $aResult = array();
        foreach($aCurrencies as $aCurrency) {
            $aData = array();
            if(empty($aCurrency['Data']) || !($aData = unserialize($aCurrency['Data'])))
                continue;

            $aResult[] = array(
                'key' => htmlspecialchars($aData['sign'], ENT_XHTML),
                'value' => $aData['sign']
            );
        }

        return $aResult;
    }

    public function serviceGetPayments()
    {
        return BxDolPayments::getInstance()->getPayments();
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services
     * @subsection bx_system_general-payments Payments
     * @subsubsection bx_system_general-get_cart_items_count get_cart_items_count
     * 
     * @code bx_srv('system', 'get_cart_items_count', [], 'TemplPaymentsServices'); @endcode
     * @code {{~system:get_cart_items_count:TemplPaymentsServices~}} @endcode
     * 
     * Get number of items in shopping cart for currently logged in profile.
     * 
     * @see BxBasePaymentsServices::serviceGetChartStats
     */
    /** 
     * @ref bx_system_general-get_cart_items_count "get_cart_items_count"
     */
    public function serviceGetCartItemsCount()
    {
    	return BxDolPayments::getInstance()->getCartItemsCount();
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services
     * @subsection bx_system_general-payments Payments
     * @subsubsection bx_system_general-get_orders_count get_orders_count
     * 
     * @code bx_srv('system', 'get_orders_count', ["new"], 'TemplPaymentsServices'); @endcode
     * @code {{~system:get_orders_count:TemplPaymentsServices["new"]~}} @endcode
     * 
     * Get number of order by type for currently logged in profile.
     * @param $sType type, for example: new
     * 
     * @see BxBasePaymentsServices::serviceGetOrdersCount
     */
    /** 
     * @ref bx_system_general-get_orders_count "get_orders_count"
     */
    public function serviceGetOrdersCount($sType)
    {
    	return BxDolPayments::getInstance()->getOrdersCount($sType);
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services
     * @subsection bx_system_general-payments Payments
     * @subsubsection bx_system_general-get_invoices_count get_invoices_count
     * 
     * @code bx_srv('system', 'get_invoices_count', ["unpaid"], 'TemplPaymentsServices'); @endcode
     * @code {{~system:get_invoices_count:TemplPaymentsServices["unpaid"]~}} @endcode
     * 
     * Get number of invoices by type for currently logged in profile.
     * @param $sType type, for example: unpaid
     * 
     * @see BxBasePaymentsServices::serviceGetInvoicesCount
     */
    /** 
     * @ref bx_system_general-get_invoices_count "get_invoices_count"
     */
    public function serviceGetInvoicesCount($sType)
    {
    	return BxDolPayments::getInstance()->getInvoicesCount($sType);
    }

    public function serviceGetLiveUpdatesCart($aMenuItemParent, $aMenuItemChild, $iCount = 0)
    {
        $iCountNew = BxDolPayments::getInstance()->getCartItemsCount();
        if($iCountNew == $iCount)
			return false;

        return array(
    		'count' => $iCountNew, // required
    		'method' => 'bx_menu_show_live_update(oData)', // required
    		'data' => array(
    			'code' => BxDolTemplate::getInstance()->parseHtmlByTemplateName('menu_item_addon', array(
    				'content' => '{count}'
                )),
                'mi_parent' => $aMenuItemParent,
                'mi_child' => $aMenuItemChild
    		),  // optional, may have some additional data to be passed in JS method provided using 'method' param above.
    	);
    }

    public function serviceGetLiveUpdatesOrders($aMenuItemParent, $aMenuItemChild, $iCount = 0)
    {
        $iCountNew = BxDolPayments::getInstance()->getOrdersCount('new');
        if($iCountNew == $iCount)
			return false;

        return array(
    		'count' => $iCountNew, // required
    		'method' => 'bx_menu_show_live_update(oData)', // required
    		'data' => array(
    			'code' => BxDolTemplate::getInstance()->parseHtmlByTemplateName('menu_item_addon', array(
    				'content' => '{count}'
                )),
                'mi_parent' => $aMenuItemParent,
                'mi_child' => $aMenuItemChild
    		),  // optional, may have some additional data to be passed in JS method provided using 'method' param above.
    	);
    }
    
    public function serviceGetLiveUpdatesInvoices($aMenuItemParent, $aMenuItemChild, $iCount = 0)
    {
        $iCountNew = BxDolPayments::getInstance()->getInvoicesCount('unpaid');
        if($iCountNew == $iCount)
            return false;

        return array(
            'count' => $iCountNew, // required
            'method' => 'bx_menu_show_live_update(oData)', // required
            'data' => array(
                'code' => BxDolTemplate::getInstance()->parseHtmlByTemplateName('menu_item_addon', array(
                    'content' => '{count}'
                )),
                'mi_parent' => $aMenuItemParent,
                'mi_child' => $aMenuItemChild
            ),  // optional, may have some additional data to be passed in JS method provided using 'method' param above.
    	);
    }
}

/** @} */
