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

class BxChartsModule extends BxDolModule
{
    protected $aColors = array('#3366CC','#DC3912','#FF9900','#109618','#990099','#3B3EAC','#0099C6','#DD4477','#66AA00','#B82E2E','#316395','#994499','#22AA99','#AAAA11','#6633CC','#E67300','#8B0707','#329262','#5574A6','#3B3EAC');
    
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }
    
    /**
     * Service methods
     */
    
    /**
     * @page service Service Calls
     * @section bx_charts Charts
     * @subsection bx_charts-other Other
     * @subsubsection bx_charts get_profile_modules
     * 
     * @code bx_srv('bx_charts', 'get_profile_modules', [...]); @endcode
     * 
     * Get list of avaliable prifile modules
     * 
     * @return an array with avaliable modules. 
     * 
     * @see BxChartsModule::serviceGetProfileModules
     */
    /** 
     * @ref bx_charts-get_profile_modules "get_profile_modules"
     */
    public function serviceGetProfileModules()
    {
        $aResult = array();
        $BxDolModuleQuery = BxDolModuleQuery::getInstance();
        $aModules = $BxDolModuleQuery->getModulesBy(array('type' => 'modules', 'active' => 1));
        foreach($aModules as $aModule){
            $oModule = BxDolModule::getInstance($aModule['name']);
            if($oModule instanceof iBxDolProfileService){
                $aResult[$aModule['name']] = $aModule['title'];
            }
        }
        return $aResult;
    }
    
    /**
     * @page service Service Calls
     * @section bx_charts Charts
     * @subsection bx_charts-other Other
     * @subsubsection bx_charts-on-profile get_text_modules
     * 
     * @code bx_srv('bx_charts', 'get_text_modules', [...]); @endcode
     * 
     * Get list of avaliable text modules
     * 
     * @return an array with avaliable modules. 
     * 
     * @see BxChartsModule::serviceGetTextModules
     */
    /** 
     * @ref bx_charts-get_text_modules "get_text_modules"
     */
    public function serviceGetTextModules()
    {
        $aResult = array();
        $BxDolModuleQuery = BxDolModuleQuery::getInstance();
        $aModules = $BxDolModuleQuery->getModulesBy(array('type' => 'modules', 'active' => 1));
        foreach($aModules as $aModule){
            $oModule = BxDolModule::getInstance($aModule['name']);
            if($oModule instanceof iBxDolContentInfoService && isset($oModule->_oConfig->CNF['OBJECT_VOTES'])){
                $aResult[$aModule['name']] = $aModule['title'];
            }
        }
        return $aResult;
    }
    
    /**
     * @page service Service Calls
     * @section bx_charts Charts
     * @subsection bx_charts-other Other
     * @subsubsection bx_charts-on-profile get_chart_top_contents_by_likes
     * 
     * @code bx_srv('bx_charts', 'get_chart_top_contents_by_likes', [...]); @endcode
     * 
     * Get Chart Top Contents By Likes
     * 
     * @return an html for chart1. 
     * 
     * @see BxChartsModule::serviceGetChartTopContentsByLikes
     */
    /** 
     * @ref bx_charts-get_text_modules "get_chart_top_contents_by_likes"
     */
    public function serviceGetChartTopContentsByLikes()
    {
        return $this->_oTemplate->getChart('TopContentsByLikes');
    }
    
    /**
     * @page service Service Calls
     * @section bx_charts Charts
     * @subsection bx_charts-other Other
     * @subsubsection bx_charts-on-profile get_chart_most_active_profiles
     * 
     * @code bx_srv('bx_charts', 'get_chart_most_active_profiles', [...]); @endcode
     * 
     * Get Chart Most Active Profiles
     * 
     * @return an html for chart1. 
     * 
     * @see BxChartsModule::serviceGetChartMostActiveProfiles
     */
    /** 
     * @ref bx_charts-get_text_modules "get_chart_most_active_profiles"
     */
    public function serviceGetChartMostActiveProfiles()
    {
        return $this->_oTemplate->getChart('MostActiveProfiles');
    }
    
    public function actionGetChartData($Id = 0)
    {
        header('Content-Type: application/json');
        if ($Id == 'TopContentsByLikes'){
            $aValues = array('labels' => array(), 'values' => array(), 'colors' => array(), 'links' => array());
            $aData = $this->_oDb->getTopByLikes();
            foreach ($aData as $aValue) {
                $oModule = BxDolModule::getInstance($aValue['module']);
                array_push($aValues['labels'], $oModule->serviceGetTitle($aValue['object_id']) . ' ' . $oModule->_aModule['title']);
                array_push($aValues['values'], $aValue['value']);
                array_push($aValues['links'], $oModule->serviceGetLink($aValue['object_id']));
              
            }
            $aValues['colors'] = array_slice($this->aColors, 0, count($aValues['values']));
            echo  '{"type": "doughnut", "data":{"labels":' . json_encode($aValues['labels']) . ',"datasets":[{"data":' . json_encode($aValues['values']) . ',"backgroundColor":' . json_encode($aValues['colors']) . '}]}, "links": ' . json_encode($aValues['links']) . '}';
        }
        
        if ($Id == 'MostActiveProfiles'){
            $aValues = array('labels' => array(), 'values1' => array(), 'values2' => array(), 'links' => array());
            $aData = $this->_oDb->getMostActiveProfiles();
            foreach ($aData as $aValue) {
                $oModule = BxDolModule::getInstance($aValue['module']);
                array_push($aValues['labels'], $oModule->serviceGetTitle($aValue['object_id']) . ' ' . $oModule->_aModule['title']);
                array_push($aValues['values1'], $aValue['create_count']);
                array_push($aValues['values2'], $aValue['views_count']);
                array_push($aValues['links'], $oModule->serviceGetLink($aValue['object_id']));
              
            }
            echo  '{"type":"bar",  "data": {"labels":' . json_encode($aValues['labels']) . ',"datasets": [{"label": "' . _t('_bx_charts_most_active_profiles_legend_posts') . '","backgroundColor": "' . $this->aColors[0] . '","borderColor": "' . $this->aColors[0] . '","borderWidth": 1,"data": ' . json_encode($aValues['values1']) . '}, {"label": "' . _t('_bx_charts_most_active_profiles_legend_views') . '","backgroundColor": "' . $this->aColors[1] . '","borderColor":"' . $this->aColors[1] . '","borderWidth": 1,"data": ' . json_encode($aValues['values2']) . '}]},"links": ' . json_encode($aValues['links']) . '}';
        }
    }
}

/** @} */
