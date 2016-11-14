<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry actions menu in popup
 */
class BxBaseModTextMenuViewActions extends BxBaseModTextMenuView
{
    protected function getMenuItemsRaw ()
    {
        return $this->_getMenuItemsCombined ();
    }
}

/** @} */
