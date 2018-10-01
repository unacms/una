<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Charts Charts
 * @ingroup     UnaModules
 *
 * @{
 */

class BxChartsAlertsResponse extends BxDolAlertsResponse
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function response($oAlert)
    {
        if (isset($oAlert->aExtras['category']) && isset($oAlert->aExtras['category']['type_name']) && isset($oAlert->aExtras['category']['type_group']) && $oAlert->aExtras['category']['type_name'] == 'bx_charts' && $oAlert->aExtras['category']['type_group'] == 'modules'){
            $oModule = BxDolModule::getInstance('bx_charts');
            BxDolCronQuery::getInstance()->addTransientJobClass('bx_charts_cron_transient', 'BxChartsCron', "modules/" . $oModule->_oConfig->getDirectory() . 'classes/' . $oModule->_oConfig->getClassPrefix() . 'Cron.php');
        }
    }
}

/** @} */
