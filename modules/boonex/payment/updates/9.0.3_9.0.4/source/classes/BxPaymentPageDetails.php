<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPaymentPageDetails extends BxTemplPage
{
    protected $MODULE;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_payment';

        parent::__construct($aObject, $oTemplate);

        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if($oMenuSubmenu) {
            $sSubmenu = 'sys_account_settings_submenu';
            $oMenuSubmenu->setObjectSubmenu($sSubmenu, array('title' => _t('_sys_menu_item_title_account_settings'), 'link' => '', 'icon' => ''));

            BxDolMenu::getObjectInstance($sSubmenu)->setSelected($this->MODULE, 'payment-details');
        }
    }
}

/** @} */
