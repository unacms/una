<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * View profile entry actions menu n popup
 */
class BxBaseModProfileMenuViewActions extends BxBaseModProfileMenuView
{
    protected function getMenuItemsRaw ()
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
