<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Browse entries pages.
 */
class BxMarketPageBrowse extends BxBaseModTextPageBrowse
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_market';
        parent::__construct($aObject, $oTemplate);
    }

	protected function _addJsCss()
    {
        $this->_oTemplate->addJs(array(
			'jquery.anim.js'
        ));

        parent::_addJsCss();
    }
}

/** @} */
