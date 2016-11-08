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
 * Entry create/edit pages
 */
class BxMarketPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_market';
        parent::__construct($aObject, $oTemplate);
    }

    public function getCode ()
    {
    	$sResult = parent::getCode();
    	$sResult .= $this->_oModule->_oTemplate->getJsCode('entry');

		$this->_oModule->_oTemplate->setCover($this->_aContentInfo);

    	$this->_oModule->_oTemplate->addJs(array('fancybox/jquery.fancybox.pack.js', 'entry.js'));
		$this->_oModule->_oTemplate->addCss(array(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'fancybox/|jquery.fancybox.css', 'entry.css'));
    	return $sResult;
    }
}

/** @} */
