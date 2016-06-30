<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     TridentModules
 * 
 * @{
 */

class BxBaseModPaymentGridCarts extends BxTemplGrid
{
	protected $MODULE;

	protected $_oModule;
	protected $_oCart;

	protected $_sCurrencySign;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        $this->_oCart = $this->_oModule->getObjectCart();

        $this->_sCurrencySign = $this->_oModule->_oConfig->getDefaultCurrencySign();

        $this->_sDefaultSortingOrder = 'DESC';

		$iClientId = bx_get('client_id');
        if($iClientId !== false)
            $this->_aQueryAppend['client_id'] = (int)$iClientId;

	    $iSellerId = bx_get('seller_id');
        if($iSellerId !== false)
            $this->_aQueryAppend['seller_id'] = (int)$iSellerId;
    }

	public function addQueryParam($sKey, $sValue)
    {
    	if(empty($sKey) || !isset($sValue))
    		return;

		$this->_aQueryAppend[$sKey] = $sValue;
    }
}

/** @} */
