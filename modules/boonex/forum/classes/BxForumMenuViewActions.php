<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry social actions menu
 */
class BxForumMenuViewActions extends BxBaseModTextMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_forum';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemSubscribeDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemUnsubscribeDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemStickDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemUnstickDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemLockDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemUnlockDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemHideDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemUnhideDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemEditDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemDeleteDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }
    
    protected function _getMenuItemResolveDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
    
    protected function _getMenuItemUnresolveDiscussion($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }
}

/** @} */
