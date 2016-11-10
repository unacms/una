<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Site main menu representation.
 */
class BxBaseMenuSite extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getCode ()
    {
        $s = parent::getCode ();
        return '<div id="bx-sliding-menu-' . $this->_sObject . '" class="bx-sliding-menu-main bx-def-z-index-nav bx-def-border-bottom" style="display:none;"><div class="bx-sliding-menu-main-cnt">' . $s . '</div></div>';
    }
}

/** @} */
