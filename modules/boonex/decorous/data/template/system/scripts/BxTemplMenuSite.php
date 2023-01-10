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
    protected $_bSiteMenu;
    protected $_bSiteMenuInPanel;
    protected $_bApplicationMenu;

    protected $_aHideFromSiteMenuInPanel;

    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_bSiteMenu = $this->_sObject == 'sys_site';
        $this->_bSiteMenuInPanel = $this->_sObject == 'sys_site_in_panel';
        $this->_bApplicationMenu = $this->_sObject == 'sys_application';

        $this->_aHideFromSiteMenuInPanel = ['more-auto'];
    }

    public function getCode ()
    {
        $sClass = 'bx-sliding-menu-main';
        $sStyle = 'display:none';
        if($this->_bSiteMenu || $this->_bSiteMenuInPanel || $this->_bApplicationMenu) {
            $sClass = 'bx-sliding-smenu-main';
            $sStyle = '';
        }

        $sResult = '<div id="bx-sliding-menu-' . $this->_sObject . '" class="' . $sClass . ' bx-def-z-index-nav" style="' . $sStyle . '"><div class="bx-sliding-menu-main-cnt">' . parent::getCode() . '</div></div>';
        if($this->_bSiteMenu) 
            $sResult = '<div class="cd-side-nav bx-def-box-sizing">' . $sResult . '</div>';

        return $sResult;
    }

    protected function _getMenuItem ($a)
    {
        //--- Hide '[More Auto]' from Site Menu in Panel
        if($this->_bSiteMenuInPanel && in_array($a['name'], $this->_aHideFromSiteMenuInPanel))
            return false;

        return parent::_getMenuItem ($a);
    }
}

/** @} */
