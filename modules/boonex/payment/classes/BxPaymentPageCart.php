<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPaymentPageCart extends BxBaseModPaymentPage
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_payment';

        parent::__construct($aObject, $oTemplate);
        
        $this->_oModule->setSiteSubmenu('menu_cart_submenu', $this->MODULE, 'cart');
    }
}

/** @} */
