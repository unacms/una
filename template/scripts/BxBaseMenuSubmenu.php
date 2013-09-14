<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxTemplMenu');

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenuSubmenu extends BxTemplMenu 
{

    protected $_sObjectSubmenu = false;
    protected $_sMainMenuItemSelected = false;

    public function __construct ($aObject, $oTemplate) 
    {
        parent::__construct ($aObject, $oTemplate);
    }

    /**
     * Set current submenu object
     * @param $sMenuObject menu object name
     * @param $sForceMainMenuSelection force main menu item selection by meni item name
     */
    public function setObjectSubmenu ($sMenuObject, $sForceMainMenuSelection = false)
    { 
        $this->_sObjectSubmenu = $sMenuObject; 
        $this->_sMainMenuItemSelected = $sForceMainMenuSelection;
    }

    /** 
     * Get menu code.
     * @return string
     */
    public function getCode () 
    {
        $aMenuItemSelected = $this->_getSelectedMenuItem ();
        if (!$aMenuItemSelected)
            return false;

        if (!$this->_sObjectSubmenu && $aMenuItemSelected['submenu_object'])
            $this->_sObjectSubmenu = $aMenuItemSelected['submenu_object'];

        $aVars = array (
            'object' => $this->_sObject,
            'title' => $aMenuItemSelected['title'],
            'icon' => $aMenuItemSelected['icon'],
        );

        $this->_addJsCss();

        $oSubmenu = null;
        if ($this->_sObjectSubmenu)
            $oSubmenu = BxDolMenu::getObjectInstance($this->_sObjectSubmenu);
                
        if ('home' == $aMenuItemSelected['name']) {
            $oSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu_main');
            return $oSubmenu->getCode();
        } else {
            return $this->_oTemplate->parseHtmlByName($this->_aObject['template'], $aVars) . ($oSubmenu ? $oSubmenu->getCode() : '');
        }
    }

    protected function _getSelectedMenuItem () {
        $aRet = array();
        if (!isset($this->_aObject['menu_items']))
            $this->_aObject['menu_items'] = $this->_oQuery->getMenuItems();

        foreach ($this->_aObject['menu_items'] as $a) {

            if (isset($a['active']) && !$a['active'])
                continue;

            if (isset($a['visible_for_levels']) && !$this->_isVisible($a))
                continue;

            $isSelected = false;

            if ($this->_sMainMenuItemSelected)
                $isSelected = $this->_sMainMenuItemSelected == $a['name'];
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
