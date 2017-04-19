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
class BxTemplMenuSite extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);
    }
    public function getCode ()
    {
        $sClass = $this->_sObject == 'sys_site' ? 'bx-sliding-smenu-main' : 'bx-sliding-menu-main';

        return '<div id="bx-sliding-menu-' . $this->_sObject . '" class="' . $sClass . ' bx-def-z-index-nav" style="display:none;"><div class="bx-sliding-menu-main-cnt">' . parent::getCode() . '</div></div>';
    }
}

/** @} */
