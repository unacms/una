<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxTemplMenu');
bx_import('BxDolPermalinks');

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenuAccountPopup extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    protected function _getTemplateVars ()
    {
        $aVars = parent::_getTemplateVars ();

        $aVars['bx_repeat:menu_items'] = array(true);
        $aVars['profile_display_name'] = BxDolProfile::getInstance()->getDisplayName();
        $aVars['url_switch_profile'] = BxDolPermalinks::getInstance()->permalink('page.php?i=account-profile-switcher');
        $aVars['menu_account'] = BxDolMenu::getObjectInstance('sys_account')->getCode();
        $aVars['menu_notifications'] = BxDolMenu::getObjectInstance('sys_account_notifications')->getCode();

        $a = BxDolService::call('system', 'account_profile_switcher', array(), 'TemplServiceProfiles');
        $aVars['profile_switcher'] = $a['content'];

        return $aVars;
    }
}

/** @} */
