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
        parent::__construct($oConfig, $oDb);
    }
    
    function getChart($chartName, $height)
    {
        $this->addJs(array('chart.min.js', 'chart.js'));
        $this->addCss(array('chart.css'));
        return $this->getJsCode('chart', array('chartName' => $chartName, 'height' => $height)) . $this->parseHtmlByName('chart.html', array('chart_name' => $chartName));
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
