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

class BxQuoteOfDayAlertsResponse extends BxDolAlertsResponse
{  
    public function __construct()
    { 
        parent::__construct();
    }
    
    public function response($oAlert)
    {
        if ($oAlert->aExtras['option'] == 'bx_quoteofday_source' || $oAlert->aExtras['option'] == 'bx_quoteofday_rss_url' || $oAlert->aExtras['option'] == 'bx_quoteofday_selection_mode'){
            $oModule = BxDolModule::getInstance('bx_quoteofday');
            $oModule->removeQuoteFromCache();
        }
    }
}

/** @} */
