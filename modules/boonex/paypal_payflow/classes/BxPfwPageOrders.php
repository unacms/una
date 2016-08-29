<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

bx_import('BxDolPageView');

class BxPfwPageOrders extends BxDolPageView
{
	protected $_oMain;
    protected $_sType;

    function BxPfwPageOrders($sType, &$oMain)
    {
        parent::BxDolPageView('bx_pfw_orders');

        $this->_sType = $sType;
        $this->_oMain = $oMain;

        $GLOBALS['oTopMenu']->setCurrentProfileID($this->_oMain->getUserId());
        $GLOBALS['oTopMenu']->setCustomVar('sys_payment_module_uri', $this->_oMain->_oConfig->getUri());
    }

	function getBlockCode_Orders()
    {
        if(empty($this->_sType))
            $this->_sType = BX_PMT_ORDERS_TYPE_PROCESSED;

        return $this->_oMain->getOrdersBlock($this->_sType);
    }
}
