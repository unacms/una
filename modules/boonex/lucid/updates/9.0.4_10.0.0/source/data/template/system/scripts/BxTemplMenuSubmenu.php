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
class BxTemplMenuSubmenu extends BxBaseMenuSubmenu
{
    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    protected function _getJsCode($aParams = array())
    {
        return parent::_getJsCode(array(
            'sClassBar' => 'bx-menu-main-submenu-wrp',
            'sClassMenu' => 'bx-menu-main-submenu-group'
        ));
    }
}

/** @} */
