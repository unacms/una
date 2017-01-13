<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxStripeConnectAlertsResponse extends BxBaseModGeneralAlertsResponse
{
    public function __construct()
    {
    	$this->MODULE = 'bx_stripe_connect';

        parent::__construct();
    }

    public function response($oAlert)
    {
        parent::response($oAlert);

        if($oAlert->sUnit != 'bx_payment')
            return;

        $sMethod = 'processAlert' . bx_gen_method_name($oAlert->sAction);
        if(!method_exists($this->_oModule, $sMethod))
            return;

        $this->_oModule->$sMethod($oAlert->iObject, $oAlert->iSender, $oAlert->aExtras);
    }
}

/** @} */
