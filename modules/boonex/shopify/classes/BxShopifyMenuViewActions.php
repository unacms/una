<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Shopify Shopify
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry social actions menu
 */
class BxShopifyMenuViewActions extends BxBaseModTextMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_shopify';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemEditShopifyEntry($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemDeleteShopifyEntry($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
}

/** @} */
