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

class BxPaymentProviderGeneric extends BxBaseModPaymentProvider implements iBxBaseModPaymentProvider
{
    public function __construct($aConfig)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);
    }

    public function initializeCheckout($iPendingId, $aCartInfo)
    {
        return $this->_sLangsPrefix . 'gc_err_not_available';
    }

    public function finalizeCheckout(&$aData)
    {
        return ['code' => 1, 'message' => $this->_sLangsPrefix . 'gc_err_not_available'];
    }

    public function finalizedCheckout()
    {
        return ['code' => 1, 'message' => $this->_sLangsPrefix . 'gc_err_not_available'];
    }
}

/** @} */
