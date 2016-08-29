<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

bx_import('BxDolPageView');

class BxPfwPageCart extends BxDolPageView
{
	protected $_oMain;

    function BxPfwPageCart(&$oMain)
    {
        parent::BxDolPageView('bx_pfw_cart');

        $this->_oMain = $oMain;

        $GLOBALS['oTopMenu']->setCurrentProfileID($this->_oMain->getUserId());
        $GLOBALS['oTopMenu']->setCustomVar('sys_payment_module_uri', $this->_oMain->_oConfig->getUri());
    }

	function getBlockCode_Featured()
    {
        return $this->_oMain->getCartContent(BX_PMT_ADMINISTRATOR_ID);
    }

    function getBlockCode_Common()
    {
        return $this->_oMain->getCartContent(BX_PMT_EMPTY_ID);
    }
}
