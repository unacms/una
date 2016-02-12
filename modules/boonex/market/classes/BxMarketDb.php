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

/*
 * Module database queries
 */
class BxMarketDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    /**
     * Integration with Payment based modules.  
     */
    public function isCustomer ($iClientId, $iProductId)
    {
    	$sQuery = $this->prepare("SELECT `id` FROM `" . $this->_sPrefix . "customers` WHERE `client_id` = ? AND `product_id` = ? LIMIT 1", $iClientId, $iProductId);
        return (int)$this->getOne($sQuery) > 0;
    }

	public function registerCustomer($iClientId, $iProductId, $sOrderId, $iCount, $iDate)
    {
    	$sQuery = $this->prepare("INSERT INTO `" . $this->_sPrefix . "customers`(`client_id`, `product_id`, `order_id`, `count`, `date`) VALUES(?, ?, ?, ?, ?)", $iClientId, $iProductId, $sOrderId, $iCount, $iDate);
        return (int)$this->query($sQuery) > 0;
    }

    public function unregisterCustomer($iClientId, $iProductId, $sOrderId)
    {
    	$sQuery = $this->prepare("DELETE FROM `" . $this->_sPrefix . "customers` WHERE `client_id` = ? AND `product_id` = ? AND `order_id` = ?", $iClientId, $iProductId, $sOrderId);
        return (int)$this->query($sQuery) > 0;
    }

    function isPurchasedEntry ($iClientId, $iProductId)
    {
    	$sQuery = $this->prepare("SELECT `id` FROM `" . $this->_sPrefix . "customers` WHERE `client_id` = ? AND `product_id` = ? LIMIT 1", $iClientId, $iProductId);
        return (int)$this->getOne($sQuery) > 0;
    }
}

/** @} */
