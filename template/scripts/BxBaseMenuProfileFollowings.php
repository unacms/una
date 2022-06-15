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
class BxBaseMenuProfileFollowings extends BxTemplMenu
{
    protected $_sConnection;
    protected $_oConnection;

    protected $_iPerPageDefault;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_bMultilevel = true;
        $this->_bDisplayAddons = true;

        $this->_sConnection = 'sys_profiles_subscriptions';
        $this->_oConnection = BxDolConnection::getObjectInstance($this->_sConnection);

        $this->_iPerPageDefault = 10;
    }

    public function performActionLoadMore($sContextModule)
    {
        $aResult = [];

        $iStart = (int)bx_get('start');
        $iPerPage = (int)bx_get('per_page');
        
        $iProfile = bx_get_logged_profile_id();
        $aSubitems = $this->_getMenuSubitems($iProfile, $sContextModule, $iStart, $iPerPage);
        if(empty($aSubitems) || !is_array($aSubitems))
            return echoJson($aResult);

        $aTmplVarsSubitems = [];
        foreach($aSubitems as $aSubitem) {
            $aSubitem = $this->_getMenuItem($aSubitem);
            if($aSubitem !== false)
                $aTmplVarsSubitems[] = $aSubitem;
        }

        $aResult['content'] = $this->_oTemplate->parseHtmlByName(str_replace('.html', '_subitems.html', $this->getTemplateName()), [
            'bx_repeat:menu_items' => $aTmplVarsSubitems,
        ]);

        return echoJson($aResult);
    }

    protected function getMenuItemsRaw ()
    {
        $aMenuItems = parent::getMenuItemsRaw();
        if(empty($aMenuItems) || !is_array($aMenuItems))
            return $aMenuItems;

        $iProfile = bx_get_logged_profile_id();

        foreach($aMenuItems as $iIndex => $aItem) {
            if(empty($aItem['onclick']))
                $aMenuItems[$iIndex]['onclick'] = "javascript:bx_menu_toggle(this, '" . $this->_sObject . "', '" . $aItem['name'] . "')";

            $aSubmenu = $this->_getMenuSubitems($iProfile, $aItem['module']);

            if(!empty($aSubmenu))
                $aMenuItems[$iIndex]['subitems'] = $aSubmenu;
            else
                unset($aMenuItems[$iIndex]);
        }

        return $aMenuItems;
    }
    
    protected function _getMenuSubitems($iProfile, $sContextModule, $iStart = 0, $iPerPage = 0)
    {
        if(!$iPerPage)
            $iPerPage = $this->_iPerPageDefault;

        $aIds = $this->_oConnection->getConnectedContentByType($iProfile, [$sContextModule], false, $iStart, $iPerPage + 1);

        $bNext = false;
        if(count($aIds) > $iPerPage) {
            array_pop($aIds);
            $bNext = true;
        }        

        $aSubmenu = array();
        foreach($aIds as $iId) {
            $oContext = BxDolProfile::getInstance($iId);
            if(!$oContext)
                continue;

            $aSubmenu[] = array(
                'id' => 'context-' . $iId, 
                'name' => 'context-' . $iId, 
                'class' => '', 
                'link' => $oContext->getUrl(), 
                'onclick' => '', 
                'target' => '_self', 
                'title' => $oContext->getDisplayName(), 
                'icon' => $oContext->getIcon(),
                'active' => 1
            );
        }

        if($bNext) {
            $sMoreName = 'context-type-' . str_replace('_', '-', $sContextModule) . '-more';

            $aSubmenu[] = array(
                'id' => $sMoreName, 
                'name' => $sMoreName, 
                'class' => '', 
                'link' => 'javascript:void(0)', 
                'onclick' => "javascript:bx_menu_followings_load_more(this, '" . $this->_sObject . "', '" . $sContextModule . "', " . ($iStart + $iPerPage) . ", " . $iPerPage . ")", 
                'target' => '_self', 
                'title' => _t('_sys_show_more'), 
                'icon' => 'chevron-down',
                'active' => 1
            );
        }

        return $aSubmenu;
    }

    protected function _getTmplVarsAddon($mixedAddon, $aMenuItem)
    {
        $aAddon = parent::_getTmplVarsAddon($mixedAddon, $aMenuItem);

        $sAddonF = '';
        if(!empty($aAddon['addon']))
            $sAddonF = $this->_oTemplate->parseHtmlByTemplateName('menu_item_addon', array(
                'content' => $aAddon['addon']
            ));

        return array(
            'addon' => $aAddon['addon'],
            'addonf' => $sAddonF		
        );
    }
}

/** @} */
