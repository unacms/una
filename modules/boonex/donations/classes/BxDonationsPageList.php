<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Donations Donations
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDonationsPageList extends BxTemplPage
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_donations';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $oTemplate);

        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if($oMenuSubmenu) {
            $sMenuSubmenu = 'sys_account_dashboard';
            $oMenuSubmenu->setObjectSubmenu($sMenuSubmenu, array('title' => _t('_sys_menu_item_title_account_dashboard'), 'link' => '', 'icon' => ''));

            BxDolMenu::getObjectInstance($sMenuSubmenu)->setSelected($this->_sModule, 'dashboard-donations-list');
        }
    }
}

/** @} */
