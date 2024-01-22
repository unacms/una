<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModPaymentGridCarts extends BxTemplGrid
{
    protected $MODULE;

    protected $_oModule;
    protected $_oCart;

    protected $_bSingleSeller;
    protected $_iSingleSeller;

    protected $_sCurrencySign;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        $this->_oCart = $this->_oModule->getObjectCart();

        $this->_iSingleSeller = 0;
        if($this->_bSingleSeller)
            $this->_iSingleSeller = $this->_oModule->_oConfig->getSiteAdmin();

        $this->_sCurrencySign = $this->_oModule->_oConfig->getDefaultCurrencySign();

        $this->_sDefaultSortingOrder = 'DESC';

        $iClientId = bx_get('client_id');
        if($iClientId !== false)
            $this->_aQueryAppend['client_id'] = (int)$iClientId;

        $iSellerId = bx_get('seller_id');
        if($iSellerId !== false) {
            $iSellerId = (int)$iSellerId;
            $this->_aQueryAppend['seller_id'] = $iSellerId;

            if(!$this->_bSingleSeller)
                $this->_sCurrencySign = $this->_oModule->getVendorCurrencySign($iSellerId);
        }
    }

    public function addQueryParam($sKey, $sValue)
    {
    	if(empty($sKey) || !isset($sValue))
            return;

        $this->_aQueryAppend[$sKey] = $sValue;
    }
}

/** @} */
