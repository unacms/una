<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolMenu');
bx_import('BxDolPaginate');

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenu extends BxDolMenu {

    protected $_oTemplate;
    protected $_aOptionalParams = array('target' => '', 'onclick' => '');

    public function __construct ($aObject, $oTemplate) {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    /** 
     * Get menu code.
     * @return string
     */
    public function getCode () {

        $aVars = $this->_getTemplateVars ();

        if (empty($aVars['bx_repeat:menu_items']))
            return false;

        $this->_addJsCss();

        return $this->_oTemplate->parseHtmlByName($this->_aObject['template'], $aVars);
    }

    /** 
     * Get template variables array
     * @return array
     */
    protected function _getTemplateVars () {
        return array (
            'object' => $this->_sObject,
            'bx_repeat:menu_items' => $this->getMenuItems (),
        );
    }

    /** 
     * Get menu items array, which are ready to pass to template.
     * @return array
     */
    public function getMenuItems () {
        $aRet = array();
        if (!isset($this->_aObject['menu_items']))
            $this->_aObject['menu_items'] = $this->_oQuery->getMenuItems();

        foreach ($this->_aObject['menu_items'] as $a) {

            if (isset($a['active']) && !$a['active'])
                continue;

            if (isset($a['visible_for_levels']) && !$this->_isVisible($a))
                continue;

            $a['title'] = _t($a['title']);

            $a = $this->_replaceMarkers($a);

            $mixedAddon = $this->_getMenuAddon($a);
            $this->addMarkers(array('addon' => $mixedAddon));

            $a = $this->_replaceMarkers($a);

            list ($sIcon, $sIconUrl) = $this->_getMenuIcon($a);

            $a['class_add'] = $this->_isSelected($a) ? 'bx-menu-tab-active' : '';
            $a['link'] = isset($a['link']) ? $this->_oPermalinks->permalink($a['link']) : 'javascript:void(0);';            
            $a['title_attr'] = bx_html_attribute($a['title']);
            $a['bx_if:image'] = array (
                'condition' => (bool)$sIconUrl,
                'content' => array('icon_url' => $sIconUrl), 
            );
            $a['bx_if:icon'] = array (
                'condition' => (bool)$sIcon,
                'content' => array('icon' => $sIcon), 
            );
            $a['bx_if:title'] = array (
                'condition' => (bool)$a['title'],
                'content' => array('title' => $a['title']), 
            );

            $a['bx_if:addon'] = array (
                'condition' => (bool)$mixedAddon,
                'content' => array('addon' => $mixedAddon), 
            );

            foreach ($this->_aOptionalParams as $sName => $sDefaultValue)
                if (!isset($a[$sName]))
                    $a[$sName] = $sDefaultValue;

            $aRet[] = $a;
        }

        return $aRet;
    }

    protected function _getMenuIcon ($a) {
        $sIcon = false;
        $sIconUrl = false;
        if (!empty($a['icon'])) {
            if ((int)$a['icon'] > 0 ) {
                bx_import('BxDolStorage');
                $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);
                $sIconUrl = $oStorage ? $oStorage->getFileUrlById((int)$a['icon']) : false;
            } else {
                if (false === strpos($a['icon'], '.')) // font icons
                    $sIcon = $a['icon'];
                else
                    $sIconUrl = $this->_oTemplate->getIconUrl($a['icon']); // TODO: it is used in studio only, so move this line of code to studio or maybe all studio icons will use font icons too ?!
            }
        }
        return array ($sIcon, $sIconUrl);
    }

    protected function _getMenuAddon ($aMenuItem) {
        if (empty($aMenuItem['addon']))
            return '';

        return BxDolService::callSerialized($aMenuItem['addon'], $this->_aMarkers);
    }

    /**
     * Add css/js files which are needed for menu display and functionality.
     */    
    protected function _addJsCss() {
        $this->_oTemplate->addCss('menu.css');
    }

}

/** @} */
