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
class BxBaseMenuAccountPopup extends BxTemplMenuCustom
{
    protected $_oProfile;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_oProfile = BxDolProfile::getInstance();
    }

    protected function _getCode($sTmplName, $aTmplVars)
    {
        if(!$this->_oProfile)
            return '';

        return parent::_getCode($sTmplName, $aTmplVars);
    }

    protected function _getMenuItemProfileActive ($aItem)
    {
        return $this->_oTemplate->parseHtmlByName('map_profile_active.html', [
            'profile_unit' => $this->_oProfile->getUnit()
        ]);
    }

    protected function _getMenuItemProfileNotifications ($aItem)
    {
        return $this->_oTemplate->parseHtmlByName('map_profile_notifications.html', [
            'menu' => BxDolMenu::getObjectInstance('sys_account_notifications')->getCode()
        ]);
    }

    protected function _getMenuItemProfileSwitcher ($aItem)
    {
        $aResult = bx_srv('system', 'account_profile_switcher', [], 'TemplServiceProfiles');
        return $aResult['content'];
    }

    protected function _getMenuItemProfileCreate ($aItem)
    {
        if(!BxDolAccount::isAllowedCreateMultiple($this->_oProfile->id()))
            return '';

        return $this->_oTemplate->parseHtmlByName('map_profile_create.html', [
            'url_switch_profile' => BxDolPermalinks::getInstance()->permalink('page.php?i=account-profile-switcher')
        ]);
    }
}

/** @} */
