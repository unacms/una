<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenuSubmenu extends BxTemplMenu
{
    protected $_aSocialSharingService = false;
    protected $_sObjectActionsMenu = false;
    protected $_sObjectSubmenu = false;
    protected $_mixedMainMenuItemSelected = false;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    /**
     * Set current menu object in submenu
     * @param $sMenuObject menu object name
     * @param $sForceMainMenuSelection force main menu item selection by menu item name
     */
    public function setObjectSubmenu ($sMenuObject, $sForceMainMenuSelection = false)
    {
        $this->_sObjectSubmenu = $sMenuObject;
        $this->_mixedMainMenuItemSelected = $sForceMainMenuSelection;
    }

    /**
     * Set current actions menu in submenu
     * @param $sActionsMenuObject menu object name
     */
    public function setObjectActionsMenu ($sActionsMenuObject)
    {
        $this->_sObjectActionsMenu = $sActionsMenuObject;
    }

    /**
     * Set social sharing menu in submenu
     * @param $a menu service call array (module, method, 
     */
    public function setServiceSocialSharing ($a)
    {
        $this->_aSocialSharingService = $a;
    }

    /**
     * Get menu code.
     * @return string
     */
    public function getCode ()
    {
        $aMenuItemSelected = $this->_getSelectedMenuItem ();
        if (isset($aMenuItemSelected['set_name']) && 'sys_site' == $aMenuItemSelected['set_name'] && 'home' == $aMenuItemSelected['name'])
            return '';

        $this->_addJsCss();

        $oMenuSubmenu = BxDolMenu::getObjectInstance($this->_sObjectSubmenu);
        $aVars = array (
            'object' => $this->_sObject,
            'id' => 'bx-menu-submenu-menu',
            'title' => $aMenuItemSelected['title'],
            'link' => BxDolPermalinks::getInstance()->permalink($aMenuItemSelected['link']),
            'popup' => $oMenuSubmenu ? BxTemplFunctions::getInstance()->transBox('bx-menu-submenu-menu', '<div class="bx-def-padding">' . $oMenuSubmenu->getCode() . '</div>', true) : '',
            'bx_if:menu' => array (
                'condition' => $oMenuSubmenu,
                'content' => array(),
            ),
            'bx_if:image' => array (
                'condition' => false !== strpos($aMenuItemSelected['icon'], '.'),
                'content' => array('icon_url' => $aMenuItemSelected['icon']),
            ),
            'bx_if:icon' => array (
                'condition' => false === strpos($aMenuItemSelected['icon'], '.'),
                'content' => array('icon' => $aMenuItemSelected['icon']),
            ),
            'bx_repeat:menus' => array (),
        );

        $aMenus = $this->getSubmenuParams($aMenuItemSelected); 

        foreach ($aMenus as $aMenu) {
            $sPopupContent = '';
            if (isset($aMenu['object']) && ($oMenu = BxDolMenu::getObjectInstance($aMenu['object'])))
                $sPopupContent = $oMenu->getCode();
            elseif (isset($aMenu['service']) && is_array($aMenu['service']))
                $sPopupContent = BxDolService::call($aMenu['service']['module'], $aMenu['service']['method'], isset($aMenu['service']['params']) ? $aMenu['service']['params'] : array(), isset($aMenu['service']['class']) ? $aMenu['service']['class'] : 'Module');
            if (!$sPopupContent)
                continue;

            $aVars['bx_repeat:menus'][] = array (
                'id' => $aMenu['id'],
                'icon' => $aMenu['icon'],
                'popup' => BxTemplFunctions::getInstance()->transBox($aMenu['id'], '<div class="bx-def-padding">' . $sPopupContent . '</div>', true),
            );
        }

        if (!$aVars['bx_repeat:menus'] && (!$oMenuSubmenu || !$aMenuItemSelected))
            return '';

        $sMenu = $this->_oTemplate->parseHtmlByName($this->_aObject['template'], $aVars);

        return $this->_oTemplate->parseHtmlByName('menu_main_submenu_wrapper.html', array('menu' => $sMenu));
    }

    protected function getSubmenuParams($aMenuItemSelected)
    {
        if (!$this->_sObjectSubmenu && !$this->_mixedMainMenuItemSelected && $aMenuItemSelected['submenu_object'])
            $this->_sObjectSubmenu = $aMenuItemSelected['submenu_object'];

        return array (
            'social_sharing_menu' => array ('id' => 'bx-menu-social-sharing-menu', 'icon' => 'share', 'service' => $this->_aSocialSharingService),
            'actions_menu' => array ('id' => 'bx-menu-actions-menu', 'icon' => 'cog', 'object' => $this->_sObjectActionsMenu),            
        );
    }

    protected function _getSelectedMenuItem ()
    {
        if (is_array($this->_mixedMainMenuItemSelected))
            return $this->_mixedMainMenuItemSelected;

        if (!isset($this->_aObject['menu_items']))
            $this->_aObject['menu_items'] = $this->_oQuery->getMenuItems();

        foreach ($this->_aObject['menu_items'] as $a) {

            if (isset($a['active']) && !$a['active'])
                continue;

            if (isset($a['visible_for_levels']) && !$this->_isVisible($a))
                continue;

            $isSelected = false;

            if ($this->_mixedMainMenuItemSelected)
                $isSelected = $this->_mixedMainMenuItemSelected == $a['name'];
            else
                $isSelected = $this->_isSelected($a) || ($this->_sObjectSubmenu && $this->_sObjectSubmenu == $a['submenu_object']);

            if (!$isSelected)
                continue;

            $a = $this->_replaceMarkers($a);
            $a['title'] = _t($a['title']);
            return $a;
        }

        return false;
    }
}

/** @} */
