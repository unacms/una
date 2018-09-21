<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Snipcart Snipcart
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry social actions menu
 */
class BxSnipcartMenuViewActions extends BxBaseModTextMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_snipcart';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemBuySnipcartEntry($aItem)
    {
        return $this->_oModule->_oTemplate->getSctButton($this->_aContentInfo);
    }

    protected function _getMenuItemEditSnipcartEntry($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemDeleteSnipcartEntry($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
}

/** @} */
