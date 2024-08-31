<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Profile's withdrawals page.
 */
class BxCreditsPageWithdrawals extends BxTemplPage
{
    protected $_sModule;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_credits';

        parent::__construct($aObject, $oTemplate);

        if(($oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu')) !== false) {
            $sMenuSubmenu = 'sys_account_dashboard';
            $oMenuSubmenu->setObjectSubmenu($sMenuSubmenu, array('title' => _t('_sys_menu_item_title_account_dashboard'), 'link' => '', 'icon' => ''));

            BxDolMenu::getObjectInstance($sMenuSubmenu)->setSelected($this->_sModule, 'credits-manage');
        }
    }
}

/** @} */
