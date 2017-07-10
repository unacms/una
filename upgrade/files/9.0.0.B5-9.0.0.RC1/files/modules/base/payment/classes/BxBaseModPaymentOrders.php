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

class BxBaseModPaymentOrders extends BxDol
{
	protected $MODULE;
	protected $_oModule;

	function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

	public function serviceGetOrdersUrl()
    {
    	if(!$this->_oModule->isLogged())
            return '';

    	return $this->_oModule->_oConfig->getUrl('URL_ORDERS');
    }

    public function serviceGetOrdersCount($sType, $iProfileId = 0)
    {
        if(!in_array($sType, array('new')))
            return 0;

    	$iProfileId = !empty($iProfileId) ? $iProfileId : $this->_oModule->getProfileId();
        if(empty($iProfileId))
            return 0;

        $aOrders = $this->_oModule->_oDb->getOrderProcessed(array('type' => $sType, 'seller_id' => $iProfileId));
        if(empty($aOrders) || !is_array($aOrders))
            return 0;

        return count($aOrders);
    }
}

/** @} */
