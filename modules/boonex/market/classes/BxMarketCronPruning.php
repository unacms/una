<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMarketCronPruning extends BxDolCron
{
	protected $_sModule;
	protected $_oModule;

	public function __construct()
    {
    	$this->_sModule = 'bx_market';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct();
    }

    function processing()
    {
        $aLicenses = $this->_oModule->_oDb->getLicense(array('type' => 'expired'));
        foreach($aLicenses as $aLicense)
        	bx_alert($this->getName(), 'license_expire', 0, false, $aLicense);

        $this->_oModule->_oDb->processExpiredLicenses();
    }
}

/** @} */
