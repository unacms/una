<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

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
        $aVars['active_profile'] = BxDolProfile::getInstance()->getUnit();
        $aVars['menu_notifications'] = BxDolMenu::getObjectInstance('sys_account_notifications')->getCode();
        $aVars['bx_if:multiple_profiles_mode'] = array(
            'condition' => 1 != (int)getParam('sys_account_limit_profiles_number'),
            'content' => array(
				'url_switch_profile' => BxDolPermalinks::getInstance()->permalink('page.php?i=account-profile-switcher')
        	),
        );

        $a = BxDolService::call('system', 'account_profile_switcher', array(), 'TemplServiceProfiles');
        $aVars['profile_switcher'] = $a['content'];

        return $aVars;
    }
}

/** @} */
