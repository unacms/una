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
 * Edit settings form
 */
class BxShopifyFormSettings extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_shopify';

        parent::__construct($aInfo, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }
}

/** @} */
