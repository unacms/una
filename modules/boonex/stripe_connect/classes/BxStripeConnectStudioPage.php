<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     TridentModules
 *
 * @{
 */

require_once('BxStripeConnectStudioSettings.php');

define('BX_DOL_STUDIO_MOD_TYPE_ACCOUNTS', 'accounts');

class BxStripeConnectStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->_sModule = 'bx_stripe_connect';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->aMenuItems[BX_DOL_STUDIO_MOD_TYPE_ACCOUNTS] = array('name' => BX_DOL_STUDIO_MOD_TYPE_ACCOUNTS, 'icon' => 'users', 'title' => '_bx_stripe_connect_menu_item_title_accounts');
    }

    protected function getSettings()
    {
        $oPage = new BxStripeConnectStudioSettings($this->sModule);

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('module.html', array(
            'content' => $oPage->getFormCode(),
        ));
    }

    protected function getAccounts()
    {
        $oGrid = BxDolGrid::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_GRID_ACCOUNTS'], BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return '';

        return $oGrid->getCode();
    }
}

/** @} */
