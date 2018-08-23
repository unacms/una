<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Channels Channels
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry all actions menu
 */
class BxCnlMenuViewActionsAll extends BxBaseModGroupsMenuViewActionsAll
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_channels';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemEditChannelCover($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemEditChannelProfile($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemDeleteChannelProfile($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }
}

/** @} */
