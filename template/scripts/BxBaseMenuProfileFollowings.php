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
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_bMultilevel = true;
        $this->_bDisplayAddons = true;
    }

    protected function getMenuItemsRaw ()
    {
        $aMenuItems = parent::getMenuItemsRaw();
        if(empty($aMenuItems) || !is_array($aMenuItems))
            return $aMenuItems;

        $iProfile = bx_get_logged_profile_id();
        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_subscriptions');

        foreach($aMenuItems as $iIndex => $aItem) {
            $aIds = $oConnection->getConnectedContentByType($iProfile, array($aItem['module']));

            if(empty($aItem['onclick']))
                $aMenuItems[$iIndex]['onclick'] = 'bx_toggle_pf(this)';

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

            if(!empty($aSubmenu))
                $aMenuItems[$iIndex]['subitems'] = $aSubmenu;
            else
                unset($aMenuItems[$iIndex]);
        }

        return $aMenuItems;
    }

    protected function _getMenuIcon ($a)
    {
        list($sIcon, $sIconUrl, $sIconA) = parent::_getMenuIcon($a);

        if(!$sIcon && !$sIconUrl && !$sIconA && !empty($a['icon']) && strpos($a['icon'], '.') !== false)
            $sIconUrl = $a['icon'];

        return array($sIcon, $sIconUrl, $sIconA);
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
