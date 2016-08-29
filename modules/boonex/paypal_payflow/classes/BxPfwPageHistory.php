<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

bx_import('BxDolPageView');

class BxPfwPageHistory extends BxDolPageView
{
	protected $_oMain;
    protected $_iVendorId;

    function BxPfwPageHistory($sType, &$oMain)
    {
        parent::BxDolPageView('bx_pfw_history');

        $this->_iVendorId = $sType == 'site' ? BX_PMT_ADMINISTRATOR_ID : BX_PMT_EMPTY_ID;
        $this->_oMain = $oMain;

        $GLOBALS['oTopMenu']->setCurrentProfileID($this->_oMain->getUserId());
        $GLOBALS['oTopMenu']->setCustomVar('sys_payment_module_uri', $this->_oMain->_oConfig->getUri());
    }

	function getBlockCode_History()
    {
        return $this->_oMain->getCartHistory($this->_iVendorId);
    }
}
