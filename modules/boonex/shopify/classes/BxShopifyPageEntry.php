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
 * Entry create/edit pages
 */
class BxShopifyPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_shopify';
        parent::__construct($aObject, $oTemplate);
    }

    public function getCode()
    {
        if(!$this->_aContentInfo) {
            $this->_oTemplate->displayPageNotFound();
            exit;
        }

        $iProfileId = $this->_aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_AUTHOR']];
        return $this->_oModule->serviceInclude($iProfileId) . parent::getCode();
    }

    protected function _setSubmenu($aParams)
    {
    	parent::_setSubmenu(array_merge($aParams, array(
    		'title' => '',
    		'icon' => ''
    	)));
    }
}

/** @} */
