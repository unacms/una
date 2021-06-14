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
 * View entry all actions menu
 */
class BxBaseModTextMenuViewActions extends BxBaseModGeneralMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemApprove($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
}

/** @} */
