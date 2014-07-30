<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxTemplPage');

/**
 * Dashboard page.
 */
class BxBasePageDashboard extends BxTemplPage
{
    public function __construct($aObject, $oTemplate)
    {
        parent::__construct($aObject, $oTemplate);

        // set dashboard submenu
        bx_import('BxDolMenu');
        bx_import('BxDolPermalinks');
        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        if ($oMenuSubmenu) {
            $oMenuSubmenu->setObjectSubmenu('sys_account_dashboard_submenu', array (
                'title' => _t('_sys_menu_item_title_account_dashboard'),
                'link' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=dashboard'),
                'icon' => '',
            ));
        }
    }
}

/** @} */
