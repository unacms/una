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
    protected $_bSiteMenuInPanel;
    protected $_bApplicationMenu;

    protected $_aHideFromSiteMenuInPanel;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_bSiteMenuInPanel = $this->_sObject == 'sys_site_in_panel';
        $this->_bApplicationMenu = $this->_sObject == 'sys_application';

        $this->_aHideFromSiteMenuInPanel = ['more-auto'];
    }

    public function getCode ()
    {
        $sClass = 'bx-sliding-menu-main';
        $sStyle = 'display:none';
        if($this->_bSiteMenuInPanel || $this->_bApplicationMenu) {
            $sClass = 'bx-sliding-smenu-main';
            $sStyle = '';
        }

        return '<div id="bx-sliding-menu-' . $this->_sObject . '" class="' . $sClass . ' bx-def-z-index-nav bx-def-border-bottom" style="' . $sStyle . '"><div class="bx-sliding-menu-main-cnt">' . parent::getCode() . '</div></div>';
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
