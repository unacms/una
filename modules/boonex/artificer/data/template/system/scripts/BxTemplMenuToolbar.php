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
    protected $_aHideFromSiteToolbar;
    protected $_aHideFromMemberToolbar;

    protected $_bSiteToolbar;    

    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_aHideFromSiteToolbar = array('search');
        $this->_aHideFromMemberToolbar = array();

        $this->_bSiteToolbar = $this->_sObject == 'sys_toolbar_site';
    }

    protected function _getMenuItem ($a)
    {
        if(in_array($a['hidden_on'], [7, 15])) 
            return false;

    	//--- Hide '[Search]' from Site Toolbar
        if($this->_bSiteToolbar && in_array($a['name'], $this->_aHideFromSiteToolbar))
            return false;

        //--- Hide '[+ Add]' from Member Toolbar
        if(!$this->_bSiteToolbar && in_array($a['name'], $this->_aHideFromMemberToolbar))
            return false;

        $a = parent::_getMenuItem ($a);
        if($a === false)
            return $a;

        $a['class_add_a'] = '';
        switch ($a['name']) {
            case 'main-menu':
                $a['class_add_a'] = ' bx-sidebar-site-trigger';
                $a['link'] = 'javascript:void(0)';
                $a['onclick'] = '';
                break;

            case 'account':
                $a['class_add_a'] = ' bx-sidebar-account-trigger';
                $a['link'] = 'javascript:void(0)';
                $a['onclick'] = '';
                break;

            case 'add-content':
                $a['class_add'] = str_replace('bx-def-media-phone-hide', '',  $a['class_add']);

                if(isset($a['onclick']))
                    $a['onclick'] = str_replace('bx_menu_slide_inline', 'bx_menu_popup_inline', $a['onclick']);
                break;

            case 'notifications-preview':
                if(isset($a['onclick']))
                    $a['onclick'] = str_replace(["bx_menu_slide", "'site', "], ['bx_menu_popup'], $a['onclick']);
                break;
        }

        return $a;
    }
}

/** @} */
