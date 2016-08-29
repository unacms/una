<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

bx_import('BxDolPageView');

class BxPfwPageDetails extends BxDolPageView
{
	protected $_oMain;

    function BxPfwPageDetails(&$oMain)
    {
        parent::BxDolPageView('bx_pfw_details');

        $this->_oMain = $oMain;

        $GLOBALS['oTopMenu']->setCurrentProfileID($this->_oMain->getUserId());
        $GLOBALS['oTopMenu']->setCustomVar('sys_payment_module_uri', $this->_oMain->_oConfig->getUri());
    }

	function getBlockCode_Details()
    {
        return $this->_oMain->getDetailsForm();
    }
}
