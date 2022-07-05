<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxTemplMenuMoreAuto
 */
class BxTemplMenuSite extends BxTemplMenuMoreAuto
{
    protected $_sModule;
    protected $_oModule;
    
    protected $_bSiteMenu;
    protected $_bSiteMenuInPanel;
    protected $_bSiteMenuSubmenu;

    protected $_aHideFromSiteMenuInPanel;

    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_sModule = 'bx_lucid';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_sJsClassMoreAuto = 'BxLucidMenuMoreAuto';
        $this->_sJsCallMoreAuto = "if(!{js_object}) {var {js_object} = new {js_class}({js_params}); $(document).ready(function() {{js_object}.init();});}";

        $this->_bSiteMenu = $this->_sObject == 'sys_site';
        $this->_bSiteMenuInPanel = $this->_sObject == 'sys_site_in_panel';
        $this->_bSiteMenuSubmenu = false;

        $this->_aHideFromSiteMenuInPanel = ['more-auto'];
    }

    public function getCode ()
    {
        $sClass = 'bx-sliding-menu-main';
        $sStyle = 'display:none';
        if($this->_bSiteMenu || $this->_bSiteMenuInPanel) {
            $sClass = 'bx-sliding-smenu-main';
            $sStyle = '';
        }

        $this->_oModule->_oTemplate->addJs(array('menu_site.js'));
        return '<div id="bx-sliding-menu-' . $this->_sObject . '" class="' . $sClass . ' bx-def-z-index-nav" style="' . $sStyle . '"><div class="bx-sliding-menu-main-cnt">' . parent::getCode() . '</div></div>';
    }

    protected function _getMenuItem ($a)
    {
        //--- Hide '[More Auto]' from Site Menu in Panel
        if($this->_bSiteMenuInPanel && in_array($a['name'], $this->_aHideFromSiteMenuInPanel))
            return false;

        $aResult = parent::_getMenuItem($a);
        if(empty($aResult) || !is_array($aResult))
            return $aResult;

        $aTmplVarsSubmenu = array();
        $bTmplVarsSubmenu = $this->_bSiteMenu && $this->_bSiteMenuSubmenu && !empty($aResult['submenu_object']) && (int)$aResult['submenu_popup'] == 1;
        if($bTmplVarsSubmenu) {
            $aResult['onclick'] = '';

            $aTmplVarsSubmenu['content'] = BxDolMenu::getObjectInstance($aResult['submenu_object'])->getCode();
        }

        $aResult['bx_if:show_arrow'] = array (
            'condition' => false && $bTmplVarsSubmenu,
            'content' => array(),
        );

        $aResult['bx_if:show_line'] = array (
            'condition' => true,
            'content' => array(),
        );

        $aResult['bx_if:show_submenu'] = array (
            'condition' => $bTmplVarsSubmenu,
            'content' => $aTmplVarsSubmenu,
        );

        return $aResult;
    }
}

/** @} */
