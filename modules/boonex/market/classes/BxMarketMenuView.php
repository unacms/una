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

	public function getCode()
    {
    	list($sJsCode, $sJsMethod) = BxDolPayments::getInstance()->getAddToCartJs($this->_aContentInfo['author'], $this->MODULE, $this->_aContentInfo['id'], 1);
        $this->addMarkers(array(
        	'add_to_cart_onclick' => $sJsMethod
        ));

        return $sJsCode . parent::getCode();
    }
}

/** @} */
