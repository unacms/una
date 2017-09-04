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
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceGetPayments()
    {
        return BxDolPayments::getInstance()->getPayments();
    }

    public function serviceGetCartItemsCount()
    {
    	return BxDolPayments::getInstance()->getCartItemsCount();
    }

    public function serviceGetOrdersCount($sType)
    {
    	return BxDolPayments::getInstance()->getOrdersCount($sType);
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
}

/** @} */
