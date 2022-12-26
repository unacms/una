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
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems[BX_DOL_STUDIO_MOD_TYPE_QUOTES] = array('name' => BX_DOL_STUDIO_MOD_TYPE_QUOTES , 'icon' => 'bars', 'title' => '_bx_quoteofday_lmi_cpt_quotes', 'link' => bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=quoteofday-manage')));
    }
}

/** @} */
