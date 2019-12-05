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

class BxTimelineMenuItemActionsAll extends BxTimelineMenuItemActions
{
    protected $_aBrowseParams;
    protected $_aSubmenus;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_timeline';

        parent::__construct($aObject, $oTemplate);

        $this->_aBrowseParams = array();

        $this->_aSubmenus = array(
            'menu_item_actions' => false,
            'menu_item_manage' => false
        );
    }

    public function addMarkersExt($a)
    {
        $bResult = $this->addMarkers($a);
        if(!$bResult)
            return $bResult;

        foreach($this->_aSubmenus as $sSubmenu)
            if(($oSubmenu = $this->_getSubmenu($sSubmenu)) !== false)
                $oSubmenu->addMarkers($a);

        return $bResult;
    }

    protected function _setBrowseParams($aBrowseParams = array())
    {
        $this->_aBrowseParams = $aBrowseParams;

        parent::_setBrowseParams($aBrowseParams);
    }

    protected function _getSubmenu($sName)
    {
        if(!$this->_aSubmenus[$sName]) {
            $sObject = $this->_oModule->_oConfig->getObject($sName);

            $this->_aSubmenus[$sName] = BxDolMenu::getObjectInstance($sObject);
            if(!$this->_aSubmenus[$sName])
                return false;

            $this->_aSubmenus[$sName]->setTemplateNameItem($this->_sTmplNameItem);
            $this->_aSubmenus[$sName]->setEvent($this->_aEvent, $this->_aBrowseParams);

            $this->addMarkers($this->_aSubmenus[$sName]->getMarkers());
        }

        return $this->_aSubmenus[$sName];
    }
    
    /**
     * Items taken from 'Actions' menu
     */
    protected function _getMenuItemItemComment($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemItemShare($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
    

    /**
     * Items taken from 'Manage' menu
     */
    protected function _getMenuItemItemPin($aItem)
    {
        return $this->_getMenuItemByNameManage($aItem);
    }

    protected function _getMenuItemItemUnpin($aItem)
    {
        return $this->_getMenuItemByNameManage($aItem);
    }

    protected function _getMenuItemItemStick($aItem)
    {
        return $this->_getMenuItemByNameManage($aItem);
    }
    
    protected function _getMenuItemItemUnstick($aItem)
    {
        return $this->_getMenuItemByNameManage($aItem);
    }

    protected function _getMenuItemItemPromote($aItem)
    {
        return $this->_getMenuItemByNameManage($aItem);
    }
    
    protected function _getMenuItemItemUnpromote($aItem)
    {
        return $this->_getMenuItemByNameManage($aItem);
    }
    
    protected function _getMenuItemItemEdit($aItem)
    {
        return $this->_getMenuItemByNameManage($aItem);
    }

    protected function _getMenuItemItemDelete($aItem)
    {
        return $this->_getMenuItemByNameManage($aItem);
    }

    protected function _getMenuItemByNameActions($aItem, $aParams = array())
    {
        $oMenuManage = $this->_getSubmenu('menu_item_actions');
        if(!$oMenuManage)
            return false;

        $aItem = $oMenuManage->getMenuItem($aItem['name']);
        if(empty($aItem) || !is_array($aItem))
            return false;

        return $aItem['item'];
    }

    protected function _getMenuItemByNameManage($aItem, $aParams = array())
    {
        $oMenuManage = $this->_getSubmenu('menu_item_manage');
        if(!$oMenuManage)
            return false;

        $aItem = $oMenuManage->getMenuItem($aItem['name']);
        if(empty($aItem) || !is_array($aItem))
            return false;

        return $aItem['item'];
    }
}

/** @} */
