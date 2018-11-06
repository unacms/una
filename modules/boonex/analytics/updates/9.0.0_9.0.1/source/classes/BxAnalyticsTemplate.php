<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Analytics Analytics
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import ('BxDolModuleTemplate');

class BxAnalyticsTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_analytics';
        parent::__construct($oConfig, $oDb);
    }
    
    function getCanvas()
    {
        $aTmp = $this->getModule()->getSelectedModules();
        $aModules = $aTmp[0];
        $aModulesList = $aTmp[1];
        $aModulesList2 = array();
        foreach($aModules as $sModule){
            $aModulesList2[] = array('value' => $sModule, 'title' => $aModulesList[$sModule]);
        }
        
        $iDaysBefore = getParam('bx_analytics_default_interval_day');
        $sDate = date('d/m/Y', time() - $iDaysBefore * 86400) . ' - ' . date('d/m/Y');
        $this->addJs(array('chart.min.js', 'analytics.js', BX_DIRECTORY_PATH_MODULES . 'boonex/analytics/plugins/daterangepicker/|daterangepicker.js', BX_DIRECTORY_PATH_MODULES . 'boonex/analytics/plugins/datatables/|datatables.min.js'));
        $this->addCss(array('main.css', BX_DIRECTORY_PATH_MODULES . 'boonex/analytics/plugins/daterangepicker/|daterangepicker.css', BX_DIRECTORY_PATH_MODULES . 'boonex/analytics/plugins/datatables/|datatables.min.css'));
        return $this->getJsCode('analytics') . $this->parseHtmlByName('canvas.html', array(
             'bx_repeat:items' => $aModulesList2,
             'interval' => $sDate,
             'export_to_csv_title' => _t('_bx_analytics_txt_export_to_csv_title')
            ));
    }
}

/** @} */
