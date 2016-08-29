<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    MarketApi MarketApi
 * @ingroup     TridentModules
 *
 * @{
 */

class BxMarketApiResponse extends BxDolAlertsResponse
{
    public function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance('bx_market_api');
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
    	$sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);
		if(!method_exists($this, $sMethod))
			return;

		return $this->$sMethod($oAlert);
    }

    protected function _processAccountLogin($oAlert)
    {
    	bx_require_authentication();

    	$oSession = BxDolSession::getInstance();
    	$sSessionKeysPrefix = $this->_oModule->_oConfig->getSessionKeysPrefix();

    	if($oSession->isValue($sSessionKeysPrefix . 'purchase'))
    		$this->_oModule->addToCart();

		if($oSession->isValue($sSessionKeysPrefix . 'subscribe'))
    		$this->_oModule->subscribe();
    }

	protected function _processSystemBeforeRegisterPayment($oAlert)
	{
		if(empty($oAlert->aExtras['pending']) || !is_array($oAlert->aExtras['pending']))
			return;

		$aPending = $oAlert->aExtras['pending'];
		if(empty($aPending['type']) || $aPending['type'] != 'recurring')
			return;

		$this->_oModule->redirectAfterPayment();
	}

	protected function _processSystemRegisterPayment($oAlert)
    {
    	$this->_oModule->redirectAfterPayment();
    }
}

/** @} */
