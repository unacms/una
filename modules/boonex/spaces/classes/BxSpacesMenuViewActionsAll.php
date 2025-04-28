<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Spaces Spaces
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry all actions menu
 */
class BxSpacesMenuViewActionsAll extends BxBaseModGroupsMenuViewActionsAll
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_spaces';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemJoinSpaceProfile($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemEditSpaceCover($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemEditSpaceProfile($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemEditSpacePricing($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemInviteToSpace($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemDeleteSpaceProfile($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemApproveSpaceProfile($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }
    
    protected function _getMenuItemRate($aItem, $aParams = [])
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!is_array($aParams))
            $aParams = [];

        return parent::_getMenuItemVote($aItem, array_merge($aParams, [
            'object' => $CNF['OBJECT_VOTES_STARS']
        ]));
    }
}

/** @} */
