<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxDolMenu
 */
class BxTemplMenuAccountPopup extends BxBaseMenuAccountPopup
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    protected function _getMenuItemProfileActive ($aItem)
    {
        return $this->_oTemplate->parseHtmlByName('map_profile_active.html', [
            'profile_unit' => $this->_oProfile->getUnit(),
            'color_scheme_switcher' => BxTemplFunctions::getInstance()->getColorSchemeSwitcher(),
        ]);
    }
}

/** @} */
