<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPaymentStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule = "", $sPage = "")
    {
    	$this->_sModule = 'bx_payment';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $sPage);

        $this->aMenuItems[] = array('name' => 'providers', 'icon' => 'money-bill-alt', 'title' => '_bx_payment_lmi_cpt_providers');
    }

    protected function getProviders()
    {
        $oGrid = BxDolGrid::getObjectInstance($this->_oModule->_oConfig->getObject('grid_providers'), BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return '';

        return $oGrid->getCode();
    }
}

/** @} */
