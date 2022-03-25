<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxStripeConnectStudioOptions extends BxTemplStudioOptions
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($sType = '', $sCategory = '', $sMix = '')
    {
        parent::__construct($sType, $sCategory, $sMix);

        $this->_sModule = 'bx_stripe_connect';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function getCustomValueRedirectUrl($aItem, $mixedValue)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . $CNF['URI_REDIRECT'];
    }
}
/** @} */
