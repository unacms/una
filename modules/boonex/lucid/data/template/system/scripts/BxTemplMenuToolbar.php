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
class BxTemplMenuToolbar extends BxBaseMenuToolbar
{
	protected $_bSiteToolbar;

    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);
        
        $this->_bSiteToolbar = $this->_sObject == 'sys_toolbar_site';
    }

    protected function _getMenuItem ($a)
    {
    	//--- Hide 'Main Menu' and 'Search' from Site Toolbar
        if($this->_bSiteToolbar && in_array($a['name'], array('search')))
            return false;

		//--- Hide 'Login' from Member Toolbar
        if(!$this->_bSiteToolbar && in_array($a['name'], array('login', 'add-content')))
            return false;

        $a = BxTemplMenu::_getMenuItem ($a);
        if($a === false)
            return $a;

        $a['bx_if:unit'] = array(
        	'condition' => false,
        	'content' => array()
        );

        $a['class_add_a'] = '';
        switch ($a['name']) {
            case 'bx_lucid_search':
                $a['class_add'] = 'bx-def-media-phone-hide';
                break;

            case 'bx_lucid_join':
            case 'bx_lucid_login':
                $a['class_add'] = '';
                $a['class_add_a'] = ' bx-btn ' . str_replace('_', '-', $a['name']);
                break;

            case 'main-menu':
                $a['class_add_a'] = ' cd-dropdown-trigger';
                $a['link'] = 'javascript:void(0)';
                $a['onclick'] = '';
                break;
        }

        return $a;
    }

	protected function _getTmplVarsAddon($mixedAddon, $aMenuItem)
    {
        return array(
            'addon' => $mixedAddon,
            'addonf' => $this->_oTemplate->parseHtmlByTemplateName('menu_item_addon_small', array(
		        'content' => $mixedAddon
		    ))
			
        );
    }
}

/** @} */
