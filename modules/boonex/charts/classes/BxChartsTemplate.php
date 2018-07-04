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

bx_import ('BxDolModuleTemplate');

class BxChartsTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_charts';
        parent::__construct($oConfig, $oDb);
    }
    
    function getChartGrowth($sChartName, $iHeight)
    {
        $this->getModule();
        $aTmp = $this->_oModule->getSelectedModulesGrowth();
        $aModules =  $aTmp[0];
        $aModulesList = $aTmp[1];     
        $aModulesList2 = array();
        foreach($aModules as $sModule){
            $aModulesList2[] = array('value' => $sModule, 'title' => $aModulesList[$sModule]);
        }
        
        return $this->parseHtmlByName('chart-growth.html', array(
            'chart' => $this->getChart($sChartName, $iHeight),
            'bx_if:show_module_selector' => array(
                'condition' => count($aModulesList2)>1 ? true : false,
                'content' => array(
                    'chart_name' => $sChartName,
                    'bx_repeat:items' => $aModulesList2
                )
            )));
    }
    
    function getChart($sChartName, $iHeight)
    {
        $this->addJs(array('chart.min.js', 'chart.js'));
        $this->addCss(array('chart.css'));
        return $this->getJsCode('chart', array('chartName' => $sChartName)) . $this->parseHtmlByName('chart.html', array('chart_name' => $sChartName, 'height' => $iHeight));
    }
    
    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $aParams = array_merge(array(
            'sChartName' => $aParams['chartName'],
        ), $aParams);
        return parent::getJsCode($sType, $aParams, $bWrap);
    }
}

/** @} */
