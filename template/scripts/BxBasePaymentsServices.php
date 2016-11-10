<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCoreBaseRepresentation UNA Core Base Representation Classes
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
