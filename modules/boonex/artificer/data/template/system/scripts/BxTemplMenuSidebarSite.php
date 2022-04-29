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
class BxTemplMenuSidebarSite extends BxTemplMenu
{
    protected $_aHideFromMenu;

    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);
        
        $this->_aHideFromMenu = ['search', 'more-auto'];
    }

    protected function _getMenuItem ($a)
    {
        if(in_array($a['name'], $this->_aHideFromMenu))
            return false;

        $aResult = parent::_getMenuItem($a);
        if(empty($aResult) || !is_array($aResult))
            return $aResult;

        $aTmplVarsSubmenu = array();
        $bTmplVarsSubmenu = !empty($aResult['submenu_object']);
        if($bTmplVarsSubmenu) {
            $aResult['onclick'] = "javascript:return bx_sidebar_dropdown_toggle(this)";
            $aResult['class_add'] .= ' bx-si-dropdown-has';

            if(($oSubmenu = BxDolMenu::getObjectInstance($aResult['submenu_object'])) !== false)
                $aTmplVarsSubmenu['bx_repeat:submenu_items'] = $oSubmenu->getMenuItems(); 
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
