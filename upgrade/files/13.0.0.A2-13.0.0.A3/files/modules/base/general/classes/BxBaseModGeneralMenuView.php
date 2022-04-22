<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxBaseModGeneralMenuView extends BxTemplMenu
{
    protected $MODULE;
    protected $_oModule;

    protected $_iContentId;
    protected $_aContentInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        $this->addMarkers(array(
            'module' => $this->_oModule->_oConfig->getName(),
            'module_uri' => $this->_oModule->_oConfig->getUri(),
        ));
    }

    /**
     * Check if menu items is visible with extended checking linked to "allow*" method of particular module
     * Associated "allow*" method with particular menu item is stored in module config in MENU_ITEM_TO_METHOD array.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a))
            return false;

        return $this->_oModule->isMenuItemVisible($this->_sObject, $a, $this->_aContentInfo);
    }

    protected function _getMenuItemsCombined ()
    {
        // combile values from ACTIONS_VIEW_ENTRY and ACTIONS_VIEW_ENTRY_MORE menus
        $CNF = $this->_oModule->_oConfig->CNF;

        if (empty($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']))
            return array();

        $aItems = $this->_oQuery->getMenuItemsFromSet($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']);
        
        if (empty($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE']))
            return $aItems;

        $aItemsMore = $this->_oQuery->getMenuItemsFromSet($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE']);
        
        // remove "more" item from ACTIONS_VIEW_ENTRY
        $aItems = array_filter ($aItems, function ($aItem) {
            return $aItem['order'] != BX_MENU_LAST_ITEM_ORDER;
        });

        // return combined array
        return array_merge($aItems, $aItemsMore);
    }
}

/** @} */
