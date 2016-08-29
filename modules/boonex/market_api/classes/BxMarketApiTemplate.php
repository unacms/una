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

/*
 * Module representation.
 */
class BxMarketApiTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_market_api';

        parent::__construct($oConfig, $oDb);
    }

    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
    	$aParams = array_merge($aParams, array(
    		'aHtmlIds' => $this->_oConfig->getHtmlIds()
    	));

    	return parent::getJsCode($sType, $aParams, $bWrap);
    }
}

/** @} */
