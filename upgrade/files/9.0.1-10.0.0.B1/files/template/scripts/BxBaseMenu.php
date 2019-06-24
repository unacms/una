<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenu extends BxDolMenu
{
    protected $_oTemplate;
    protected $_aOptionalParams = array('target' => '', 'onclick' => '');

    public function __construct ($aObject, $oTemplate)
    {
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
    public function getCode ()
    {
        $sMenuTitle = isset($this->_aObject['title']) ? _t($this->_aObject['title']) : 'Menu-' . rand(0, PHP_INT_MAX);
        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->beginMenu($sMenuTitle);

        $s = '';
        $aVars = $this->_getTemplateVars ();
        if (!empty($aVars['bx_repeat:menu_items'])) {
            $this->_addJsCss();
            $s = $this->_oTemplate->parseHtmlByName($this->_aObject['template'], $aVars);
        }

        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->endMenu($sMenuTitle);

        return $s;
    }
    
    public function getCodeItem ($sName)
    {
        if(empty($sName))
            return '';

        $sCode = $this->_oTemplate->getHtml(str_replace('.html', '_item.html', $this->_aObject['template']));
        if(empty($sCode))
            return '';

        $mixedTmplVars = $this->getMenuItem($sName);
        if($mixedTmplVars === false)
            return '';

        return $this->_oTemplate->parseHtmlByContent($sCode, $mixedTmplVars);
    }

    /**
     * Get template variables array
     * @return array
     */
    protected function _getTemplateVars ()
    {
        return array (
            'object' => $this->_sObject,
            'bx_repeat:menu_items' => $this->getMenuItems (),
        );
    }

    /**
     * Get menu items array, which is ready to pass to menu template. 
     * @return array or false
     */
    public function getMenuItems ()
    {
        if (!isset($this->_aObject['menu_items']))
            $this->_aObject['menu_items'] = $this->getMenuItemsRaw ();

        $aItems = array();
        foreach ($this->_aObject['menu_items'] as $aItem) {
            $aItem = $this->_getMenuItem ($aItem);
            if($aItem !== false)
                $aItems[] = $aItem;
        }

        return $aItems;
    }
    
    /**
     * Get menu item array, which is ready to pass to whole menu or 
     * single menu item template. May return false if single menu item 
     * is requested but cannot be shown by circumstances.
     * @return array or false
     */
    public function getMenuItem ($sName)
    {
        if (!isset($this->_aObject['menu_items']))
            $this->_aObject['menu_items'] = $this->getMenuItemsRaw ();

        if(!empty($sName))
            return $this->_getMenuItem($this->_aObject['menu_items'][$sName]);
    }

    /**
     * Get menu items array, this is just a wrapper for DB function for make it easier to override.
     * It is used in @see BxBaseMenu::getMenuItems
     * @return array
     */
    protected function getMenuItemsRaw ()
    {
        return $this->_oQuery->getMenuItems();
    }

	protected function _getMenuItem ($a)
	{
            $aHiddenOn = array(
                pow(2, BX_DB_HIDDEN_PHONE - 1) => 'bx-def-media-phone-hide',
                pow(2, BX_DB_HIDDEN_TABLET - 1) => 'bx-def-media-tablet-hide',
                pow(2, BX_DB_HIDDEN_DESKTOP - 1) => 'bx-def-media-desktop-hide',
                pow(2, BX_DB_HIDDEN_MOBILE - 1) => 'bx-def-mobile-app-hide'
            );
        
		if (isset($a['active']) && !$a['active'])
			return false;

		if (isset($a['visible_for_levels']) && !$this->_isVisible($a))
        	return false;

		$a['title'] = _t($a['title']);
		$a['bx_if:title'] = array(
			'condition' => !empty($a['title']),
			'content' => array(
				'title' => $a['title']
			)
		);

        $this->removeMarker('addon');

		$a = $this->_replaceMarkers($a);

		$mixedAddon = $this->_getMenuAddon($a);
        if (!is_array($mixedAddon))
		    $this->addMarkers(array('addon' => $mixedAddon));

		$a = $this->_replaceMarkers($a);

		list ($sIcon, $sIconUrl, $sIconA) = $this->_getMenuIcon($a);

		$a['class_add'] = $this->_isSelected($a) ? 'bx-menu-tab-active' : '';
        
        $sHiddenOnCssClasses = '';
        if(!empty($a['hidden_on']))
            foreach($aHiddenOn as $iHiddenOn => $sClass)
                if((int)$a['hidden_on'] & $iHiddenOn)
                    $sHiddenOnCssClasses .= ' ' . $sClass;
        $a['class_add'] .=  $sHiddenOnCssClasses;
		$a['link'] = isset($a['link']) ? $this->_oPermalinks->permalink($a['link']) : 'javascript:void(0);';
		$a['title_attr'] = bx_html_attribute(strip_tags($a['title']));
		$a['bx_if:image'] = array (
			'condition' => (bool)$sIconUrl,
			'content' => array('icon_url' => $sIconUrl),
		);
		$a['bx_if:icon'] = array (
        	'condition' => (bool)$sIcon,
            'content' => array('icon' => $sIcon),
		);
		$a['bx_if:icon-a'] = array (
        	'condition' => (bool)$sIconA,
            'content' => array('icon-a' => $sIconA),
		);
		$a['bx_if:title'] = array (
			'condition' => (bool)$a['title'],
			'content' => array(
				'title' => $a['title'],
		        'title_attr' => $a['title_attr']
		    ),
		);
		$a['bx_if:addon'] = array (
			'condition' => (bool)$mixedAddon,
			'content' => $this->_getTmplVarsAddon($mixedAddon, $a)
		);

		foreach ($this->_aOptionalParams as $sName => $sDefaultValue)
        	if (!isset($a[$sName]))
            	$a[$sName] = $sDefaultValue;

		return $a;
	}

    protected function _getMenuIcon ($a)
    {
        $sIcon = false;
        $sIconA = false;
        $sIconUrl = false;
        if (!empty($a['icon'])) {
            if ((int)$a['icon'] > 0 ) {
                $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);
                $sIconUrl = $oStorage ? $oStorage->getFileUrlById((int)$a['icon']) : false;
            } else {
                if (false === strpos($a['icon'], '.')) { 
                    if (0 === strncmp($a['icon'], 'a:', 2))
                        $sIconA = substr($a['icon'], 2); // animated icon
                    else
                        $sIcon = $a['icon']; // font icons
                } else {
                    $sIconUrl = $this->_oTemplate->getIconUrl($a['icon']);
                }
            }
        }
        return array ($sIcon, $sIconUrl, $sIconA);
    }

    protected function _getMenuAddon ($aMenuItem)
    {
        if (empty($aMenuItem['addon']))
            return '';

        return BxDolService::callSerialized($aMenuItem['addon'], $this->_aMarkers);
    }

    /**
     * Add css/js files which are needed for menu display and functionality.
     */
    protected function _addJsCss()
    {
        $this->_oTemplate->addCss('menu.css');
    }

	protected function _getTmplVarsAddon($mixedAddon, $aMenuItem) 
	{
	    return array(
	    	'addon' => $mixedAddon,
	        'addonf' => ''
	    );
	}
}

/** @} */
