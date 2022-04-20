<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineMenuFeeds extends BxTemplMenu
{
    protected $_sModule;
    protected $_oModule;

    protected $_sJsObject;
    protected $_sStylePrefix;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_timeline';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $this->_oModule->_oTemplate);

        $this->_bMultilevel = true;
        $this->_bDisplayAddons = true;
    }

    public function setBrowseParams($aParams = [])
    {
        $this->_sJsObject = $this->_oModule->_oConfig->getJsObjectView($aParams);
        $this->_sStylePrefix = $this->_oModule->_oConfig->getPrefix('style');

        $aMarkers = array();
        foreach($aParams as $sKey => $mixedValue)
            if(!is_array($mixedValue))
                $aMarkers[$sKey] = $mixedValue;
        $aMarkers['js_object_view'] = $this->_sJsObject;

        $this->addMarkers($aMarkers);
    }

    protected function getMenuItemsRaw()
    {
        $aMenuItems = parent::getMenuItemsRaw();
        if(empty($aMenuItems) || !is_array($aMenuItems))
            return $aMenuItems;

        $iProfile = bx_get_logged_profile_id();
        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_subscriptions');
        $sOnClickMask = "return " . $this->_sJsObject. ".changeFeed(this, '%s', {context: %d})";

        foreach($aMenuItems as $iIndex => $aItem) {
            $aIds = $oConnection->getConnectedContentByType($iProfile, array($aItem['module']));

            if(empty($aItem['onclick']))
                $aMenuItems[$iIndex]['onclick'] = $this->_sJsObject . '.toggleMenuItemFeeds(this)';

            $aSubmenu = array();
            foreach($aIds as $iId) {
                $oContext = BxDolProfile::getInstance($iId);
                if(!$oContext)
                    continue;

                $aSubmenu[] = array(
                    'id' => 'context-' . $iId, 
                    'name' => 'context-' . $iId, 
                    'module' => $aItem['module'],
                    'class' => '', 
                    'link' => $oContext->getUrl(), 
                    'onclick' => sprintf($sOnClickMask, $aItem['module'], $iId), 
                    'target' => '_self', 
                    'title' => $oContext->getDisplayName(), 
                    'icon' => $oContext->getIcon(),
                    'active' => 1,
                    'context_id' => $iId
                );
            }

            if(!empty($aSubmenu))
                $aMenuItems[$iIndex]['subitems'] = $aSubmenu;
        }

        return $aMenuItems;
    }
    
    protected function _getTemplateVars ()
    {
        $aResult = parent::_getTemplateVars();
        $aResult['style_prefix'] = $this->_sStylePrefix;
        return $aResult;
    }
    
    protected function _getMenuItem ($a)
    {
        if($a['name'] == 'divider' && parent::_getMenuItem($a) !== false) 
            return $this->_oModule->_oTemplate->parseHtmlByName('menu_item_divider.html', []);

        $aResult = parent::_getMenuItem($a);
        if(!$aResult)
            return $aResult;

        return array_merge($aResult, [
            'js_object' => $this->_sJsObject,
            'style_prefix' => $this->_sStylePrefix,
            'bx_if:show_toggle' => [
                'condition' => $this->_bMultilevel && !empty($a['subitems']),
                'content' => [
                    'js_object' => $this->_sJsObject,
                    'style_prefix' => $this->_sStylePrefix,
                ]
            ]
        ]);
    }
}

/** @} */
