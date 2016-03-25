<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Market Market
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxMarketMenuView extends BxBaseModTextMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_market';
        parent::__construct($aObject, $oTemplate);
    }

    protected function _isVisible ($a)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

		if(!parent::_isVisible($a))
			return false;

		$oPayment = BxDolPayments::getInstance();

		$bResult = true;
		switch ($a['name']) {
			case 'add-to-cart':
				$bResult = (float)$this->_aContentInfo[$CNF['FIELD_PRICE_SINGLE']] != 0;
				if(!$bResult) 
					break;

				list($sJsCode, $sJsMethod) = $oPayment->getAddToCartJs($this->_aContentInfo['author'], $this->MODULE, $this->_aContentInfo['id'], 1);
				$this->addMarkers(array(
		        	'add_to_cart_onclick' => $sJsMethod
		        ));
				break;

			case 'subscribe':
				$bResult = (float)$this->_aContentInfo[$CNF['FIELD_PRICE_RECURRING']] != 0;
				if(!$bResult) 
					break;

				list($sJsCode, $sJsMethod) = $oPayment->getSubscribeJs($this->_aContentInfo['author'], '', $this->MODULE, $this->_aContentInfo['id'], 1);
				$this->addMarkers(array(
		        	'subscribe_onclick' => $sJsMethod
		        )); 
				break;
		}

		return $bResult;
    }
}

/** @} */
