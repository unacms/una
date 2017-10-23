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
        $bSite = $this->_sObject == 'sys_site';

        $sClass = $bSite ? 'bx-sliding-smenu-main' : 'bx-sliding-menu-main';
        $sResult = '<div id="bx-sliding-menu-' . $this->_sObject . '" class="' . $sClass . ' bx-def-z-index-nav" style="display:none;"><div class="bx-sliding-menu-main-cnt">' . parent::getCode() . '</div></div>';
        if($bSite) 
            $sResult = '<div class="cd-side-nav bx-def-box-sizing">' . $sResult . '</div>';

        return $sResult;
    }
}

/** @} */
