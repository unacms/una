<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    QuoteOfTheDay Quote of the Day
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_DOL_STUDIO_MOD_TYPE_QUOTES', 'quotes');

class BxQuoteOfDayStudioPage extends BxTemplStudioModule
{
	protected $_sModule;
	protected $_oModule;

    function __construct($sModule = "", $sPage = "")
    {
    	$this->_sModule = 'bx_quoteofday';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $sPage);
		
		$oPermalink = BxDolPermalinks::getInstance();
      	$this->aMenuItems[] = array('name' =>BX_DOL_STUDIO_MOD_TYPE_QUOTES , 'icon' => 'bars', 'title' => '_bx_quoteofday_lmi_cpt_quotes', 'link' => BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=quoteofday-manage'));
    }

}

/** @} */
