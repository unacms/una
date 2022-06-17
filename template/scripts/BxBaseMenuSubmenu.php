<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
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
    protected $_oObjectSubmenu = null;
    protected $_mixedMainMenuItemSelected = false;

    protected $_sJsObject;

    protected $_iContentId;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_sJsObject = 'o' . bx_gen_method_name($this->_sObject);

        $this->_iContentId = 0;
    }

    public function isVisible()
    {
        if((int)$this->_aObject['active'] == 0)
            return false;

        $aMenuItemSelected = $this->getSelectedMenuItem();
        if(isset($aMenuItemSelected['set_name']) && $aMenuItemSelected['set_name'] == 'sys_site' && $aMenuItemSelected['name'] == 'home')
            return false;

        if(!$this->_oObjectSubmenu || !$this->_oObjectSubmenu->isVisible())
            return false;
        
        return true;
    }

    public function setContentId($iContentId)
    {
        $this->_iContentId = $iContentId;
    }

    /**
     * Get current menu in submenu
     * @return menu object name
     */
    public function getObjectSubmenu()
    {        
        return $this->_sObjectSubmenu;
    }
    
    /**
     * Set current menu object in submenu
     * @param $sMenuObject menu object name
     * @param $sForceMainMenuSelection force main menu item selection by menu item name
     */
    public function setObjectSubmenu ($sMenuObject, $sForceMainMenuSelection = false)
    {
        $this->_sObjectSubmenu = $sMenuObject;
        $this->_oObjectSubmenu = BxDolMenu::getObjectInstance($this->_sObjectSubmenu);

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
     * Get current actions menu in submenu
     * @return menu object name
     */
    public function getObjectActionsMenu()
    {        
        return $this->_sObjectActionsMenu;
    }

    /**
     * Set social sharing menu in submenu
     * @param $a menu service call array (module, method, etc)
     */
    public function setServiceSocialSharing ($a)
    {
        $this->_aSocialSharingService = $a;
    }

    /**
     * Get social sharing menu in submenu
     * @return menu service call array (module, method, etc)
     */
    public function getServiceSocialSharing ()
    {
        return $this->_aSocialSharingService;
    }

    /**
     * Get class
     * @return class
     */
    public function getClass ()
    {
        return $this->isVisible() && $this->_oObjectSubmenu->getTemplateId() == BX_MENU_TEMPLATE_SUBMENU_MORE_AUTO ? 'bx-menu-more-auto' : '';
    }

    /**
     * Get menu code.
     * @return string
     */
    public function getCode ()
    {
        if(!$this->isVisible())
            return '';

        if(!empty($this->_iContentId) && method_exists($this->_oObjectSubmenu, 'setContentId'))
            $this->_oObjectSubmenu->setContentId($this->_iContentId);

        $this->_addJsCss();
        return $this->_oObjectSubmenu->getCode();
    }

    public function getPageCoverParams ()
    {
        $aMenuItemSelected = $this->getSelectedMenuItem ();
        if (!$aMenuItemSelected)
            return '';

        $oMenuActions = BxDolMenu::getObjectInstance($this->_sObjectActionsMenu);

        $bImageInline = strpos($aMenuItemSelected['icon'], '&lt;svg') !== false || strpos($aMenuItemSelected['icon'], '<svg') !== false;

        $bVarsImage = !$bImageInline && strpos($aMenuItemSelected['icon'], '.') !== false;
        $aVarsImage = [];
        if($bVarsImage)
            $aVarsImage = [
                'icon_url' => $this->_oTemplate->getIconUrl($aMenuItemSelected['icon'])
            ];

        $aVars = array (
            'object' => $this->_sObject,
            'title' => bx_process_output($aMenuItemSelected['title']),
            'link' => BxDolPermalinks::getInstance()->permalink($aMenuItemSelected['link']),
            'actions' => $oMenuActions ? $oMenuActions->getCode() : '',
            'bx_if:image' => array (
                'condition' => $bVarsImage,
                'content' => $aVarsImage,
            ),
            'bx_if:icon' => array (
                'condition' => !$bVarsImage && !empty($aMenuItemSelected['icon']),
                'content' => array(
                    'icon' => $aMenuItemSelected['icon']
                ),
            ),
        );

        return $aVars;
    }

    public function getSelectedMenuItem ()
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
