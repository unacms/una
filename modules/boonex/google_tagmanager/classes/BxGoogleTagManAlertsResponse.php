<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    GoogleTagMan Google Tag Manager
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGoogleTagManAlertsResponse extends BxDolAlertsResponse
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();

        $this->_sModule = 'bx_googletagman';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function response($oAlert)
    {
    	$sMethod = 'process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);
    	if(method_exists($this, $sMethod))
    		$this->$sMethod($oAlert);

        parent::response($oAlert);
    }

    protected function processBxPaymentFinalizeCheckout(&$oAlert)
    {
        $aPending = $oAlert->aExtras['pending'];

        $aItems = explode(':', $aPending['items']);
        if(!$aItems)
            return;
        
        $oAlert->aExtras['message'] = _t($oAlert->aExtras['message']) . $this->_oModule->serviceTrackingCodePurchase($aPending, $aItems);
    }
}

/** @} */
