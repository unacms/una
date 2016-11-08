<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Payment Payment
 * @ingroup     TridentModules
 *
 * @{
 */

class BxPaymentPageCart extends BxBaseModPaymentPage
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_payment';

        parent::__construct($aObject, $oTemplate);
        
        $this->_oModule->setSiteSubmenu('menu_cart_submenu', 'cart');
    }
}

/** @} */
