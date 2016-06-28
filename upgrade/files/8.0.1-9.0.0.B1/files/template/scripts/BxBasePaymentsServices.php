<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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
}

/** @} */
