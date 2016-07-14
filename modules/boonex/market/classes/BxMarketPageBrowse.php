<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Market Market
 * @ingroup     TridentModules
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
