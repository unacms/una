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

class BxBaseModPaymentDetails extends BxDol
{
	protected $MODULE;
	protected $_oModule;

	function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

	public function serviceGetDetailsUrl()
    {
    	if(!$this->_oModule->isLogged())
            return '';

    	return  $this->_oModule->_oConfig->getUrl('URL_DETAILS');
    }
}

/** @} */
