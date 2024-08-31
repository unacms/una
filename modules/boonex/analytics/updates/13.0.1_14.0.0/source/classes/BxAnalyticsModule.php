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

/**
 * Analytics module
 */     
define('BX_ANALYTICS_SYSTEM', 'system');
define('BX_ANALYTICS_TOP_BY_LIKES', 'top_by_likes');
define('BX_ANALYTICS_TOP_BY_VIEWS', 'top_by_views');
define('BX_ANALYTICS_CONTENT_TOTAL', 'content_total');
define('BX_ANALYTICS_CONTENT_SPEED', 'content_speed');
define('BX_ANALYTICS_MOST_FOLLOWED_PROFILES', 'top_by_followers');
define('BX_ANALYTICS_MOST_ACTIVE_PROFILES', 'top_by_activity');
class BxAnalyticsModule extends BxDolModule
{
    protected $aColors = array('#3366CC', '#DC3912', '#FF9900', '#109618', '#990099');
          
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }
          
    /**
    * Service methods
    */
          
    /**
    * @page service Service Calls
    * @section bx_analytics Analytics
    * @subsection bx_analytics-other Other
    * @subsubsection bx_analytics get_canvas
    * 
    * @code bx_srv('bx_analytics', 'get_canvas', [...]); @endcode
    * 
    * Get canvas  
    * 
    * @return an html. 
    * 
    * @see BxAnalyticsModule::serviceGetCanvas
    */
    /** 
    * @ref bx_analytics-get_canvas "get_canvas"
    */
    public function serviceGetCanvas()
    {
		$sMsg = $this->checkAllowed();
		if($sMsg !== CHECK_ACTION_RESULT_ALLOWED){
			$this->_oTemplate->displayAccessDenied($sMsg, BX_PAGE_EMBED);
			exit;
		}
		
        return $this->_oTemplate->getCanvas();
    }
	
	/**
    * @page service Service Calls
    * @section bx_analytics Analytics
    * @subsection bx_analytics-other Other
    * @subsubsection bx_analytics is_avaliable
    * 
    * @code bx_srv('bx_analytics', 'is_avaliable', [...]); @endcode
    * 
    * Check avalability for current profile  
    * 
    * @return boolean. 
    * 
    * @see BxAnalyticsModule::serviceIsAvaliable
    */
    /** 
    * @ref bx_analytics-is_avaliable "is_avaliable"
    */
	public function serviceIsAvaliable()
    {
		return $this->checkAllowed() === CHECK_ACTION_RESULT_ALLOWED ? true : false;
    }
	
          
    /**
    * @page service Service Calls
    * @section bx_analytics Analytics
    * @subsection bx_analytics-other Other
    * @subsubsection bx_analytics get_modules
    * 
    * @code bx_srv('bx_analytics', 'get_modules', [...]); @endcode
    * 
    * Get modules list  
    * 
    * @return an html. 
    * 
    * @see BxAnalyticsModule::serviceGetModules
    */
    /** 
    * @ref bx_analytics-get_modules "get_modules"
    */
    public function serviceGetModules()
    {
        $aResult = array();
        $aResult[BX_ANALYTICS_SYSTEM] = _t('_bx_analytics_system_text');
        $BxDolModuleQuery = BxDolModuleQuery::getInstance();
        $aModules = $BxDolModuleQuery->getModulesBy(array('type' => 'modules', 'active' => 1));
        foreach($aModules as $aModule){
            $oModule = BxDolModule::getInstance($aModule['name']);
            if($oModule instanceof iBxDolContentInfoService){
                $aResult[$aModule['name']] = $aModule['title'];
            }
        }
        
        /**
         * @hooks
         * @hookdef hook-bx_analytics-get_modules 'bx_analytics', 'get_modules' - hook on get modules list with avalaible analytics
         * - $unit_name - equals `get_modules`
         * - $action - equals `get_modules` 
         * - $object_id - not used
         * - $sender_id -not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `list` - [array] by ref, array of modules for analytics as [module_name] = module_title, can be overridden in hook processing
         * @hook @ref hook-bx_analytics-get_modules
         */
        bx_alert($this->_aModule['name'], 'get_modules', 0, 0, array(
            'list' => &$aResult,
        ));
        
        return $aResult;
    }
          
    /**
    * Action methods
    */
          
    public function actionGetReports($sModuleName)
    {
		if(!$this->serviceIsAvaliable())
            return '';
            
        header('Content-Type: application/json');
             
        $aSystemsVote = BxDolVote::getSystems();
        $aRv = array();
        if ($sModuleName == BX_ANALYTICS_SYSTEM){
            $aRv[BX_ANALYTICS_TOP_BY_LIKES] = _t('_bx_analytics_type_top_contents_by_likes');
            $aRv[BX_ANALYTICS_TOP_BY_VIEWS] = _t('_bx_analytics_type_top_by_views');
            $aRv[BX_ANALYTICS_CONTENT_TOTAL] = _t('_bx_analytics_type_accounts_growth');
            $aRv[BX_ANALYTICS_CONTENT_SPEED] = _t('_bx_analytics_type_accounts_speed');
        }
        else{
            $oModule = BxDolModule::getInstance($sModuleName);
                  
            if(BxDolRequest::serviceExists($sModuleName, 'act_as_profile') && BxDolService::call($sModuleName, 'act_as_profile') == true){
                $aRv[BX_ANALYTICS_MOST_FOLLOWED_PROFILES] = _t('_bx_analytics_type_most_followed_profiles');
                $aRv[BX_ANALYTICS_MOST_ACTIVE_PROFILES] = _t('_bx_analytics_type_most_active_profiles');
            }
                  
            if(isset($oModule->_oConfig->CNF['OBJECT_VOTES']) && isset($aSystemsVote[$oModule->_oConfig->CNF['OBJECT_VOTES']])){
                $aRv[BX_ANALYTICS_TOP_BY_LIKES] = _t('_bx_analytics_type_top_contents_by_likes');
            }
                  
            if(isset($oModule->_oConfig->CNF['OBJECT_VIEWS'])){
                $aRv[BX_ANALYTICS_TOP_BY_VIEWS] = _t('_bx_analytics_type_top_by_views');
            }
                  
            $aRv[BX_ANALYTICS_CONTENT_TOTAL] = _t('_bx_analytics_type_content_growth');
            $aRv[BX_ANALYTICS_CONTENT_SPEED] = _t('_bx_analytics_type_content_speed');
        }
        /**
         * @hooks
         * @hookdef hook-bx_analytics-get_reports 'bx_analytics', 'get_reports' - hook on get avalaible reports for specified module
         * - $unit_name - equals `bx_analytics`
         * - $action - equals `get_reports` 
         * - $object_id - not used
         * - $sender_id -not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `module` - [string] module name
         *      - `list` - [array] by ref, array of avalaible reports for analytics as [report_name] = report_title, can be overridden in hook processing
         * @hook @ref hook-bx_analytics-get_reports
         */
        bx_alert($this->_aModule['name'], 'get_reports', 0, 0, array(
            'module' => $sModuleName,
            'list' => &$aRv,
        ));
        
        echo json_encode($aRv);
    }
          
    public function actionGetReportsData($sModuleName, $sReportName, $sDateFrom, $sDateTo, $sType = '')
    {
        if(!$this->serviceIsAvaliable())
            return '';
        
        if ($sType == 'csv'){
            header('Content-type: text/csv');
            header('Content-disposition: attachment;filename=' . $sModuleName . '_' . $sReportName . '_' . $sDateFrom . '_' . $sDateTo . '.csv');
            echo $this->actionGetReportsDataCsv($sModuleName, $sReportName, $sDateFrom, $sDateTo);
            
        }
        
        if ($sType == ''){
            header('Content-Type: application/json');
            echo $this->actionGetReportsDataJson($sModuleName, $sReportName, $sDateFrom, $sDateTo);
        } 
    }
    
    private function actionGetReportsDataCsv($sModuleName, $sReportName, $sDateFrom, $sDateTo)
    {
        $sRv = '';
        $sColumn = ";";
        $sRow = "\n";
        $sData = $this->actionGetReportsDataJson($sModuleName, $sReportName, $sDateFrom, $sDateTo);
        $oRv = json_decode($sData, true); 
        $oData = $oRv['data'];
        $sCol1 = $oRv['strings'][0];
        $iColumnCount = count($oData['datasets']);
        $sRv .= $sCol1 . $sColumn;
        foreach($oData['datasets'] as $aItem){
            $sRv .= ($aItem['label'] != '' ? $aItem['label'] : $oRv['strings'][1]) . $sColumn;
        }
   
        $sRv .= $sRow;
        $iK = 0;
        foreach($oData['datasets'][0]['data'] as $sDx => $aItemData){
            $sTxt = "";
            if (is_array($oData['datasets'][0]['data'][$sDx]))
                $sTxt = $oData['datasets'][0]['data'][$sDx]['x'];
            else
                $sTxt = $oData['labels'][$sDx];
            if (isset($oRv['links']) && count($oRv['links'])>0) {
                $sTxt .= ' (' . $oRv['links'][$sDx] . ')';
            }
            
            $sRv .= $sTxt . $sColumn;
            for ($i = 0; $i < $iColumnCount; $i++){
                $sText = '';
                if (is_array($aItemData)) {
                    $sText = $aItemData['y'];
                }
                else {
                    $sText = $aItemData;
                }
                $sRv .= $sText . $sColumn;
            }
            $sRv .= $sRow;
            $iK++;
        }
        echo $sRv;
    }
    
    private function actionGetReportsDataJson($sModuleName, $sReportName, $sDateFrom, $sDateTo)
    { 
        $iDateFrom = strtotime($sDateFrom);
        $iDateTo = strtotime($sDateTo) + 86400;
        $sType = "bar";
        $bIsTimeX = false;
        $iMinValueY = 0;
        $iMaxValueY = 0;
        $bDefaultFillData = true;
        $sReportType = $sReportName;
        
        $aValues = array('labels' => array(), 'values' => array(array('legend' => '', 'data' => array())), 'links' => array(), 'strings' => array(0 => _t('_bx_analytics_txt_item'), 1 => _t('_bx_analytics_txt_value')));
   
        if ($sModuleName != BX_ANALYTICS_SYSTEM){
            $oModule = BxDolModule::getInstance($sModuleName);
            $aValues['strings'][0] = $oModule->_aModule['title'];
            $aValues['strings'][1] = _t('_bx_analytics_type_' . $sReportType . '_label');
        }
        else{
            $aValues['strings'][0] = _t('_bx_analytics_txt_total_content');
            $aValues['strings'][1] = _t('_bx_analytics_type_' . $sReportType . '_label');
        }
              
        switch ($sReportType) {
            case BX_ANALYTICS_TOP_BY_LIKES:
                $bDefaultFillData = false;
                $this->getViewsOrVotes(BxDolVote::getSystems(), 'OBJECT_VOTES', $sModuleName, $iDateFrom, $iDateTo, $aValues, $iMaxValueY, $iMinValueY);
                break;
                  
            case BX_ANALYTICS_TOP_BY_VIEWS:
                $bDefaultFillData = false;
                $this->getViewsOrVotes(BxDolView::getSystems(), 'OBJECT_VIEWS', $sModuleName, $iDateFrom, $iDateTo, $aValues, $iMaxValueY, $iMinValueY);
                break;
                  
            case BX_ANALYTICS_MOST_FOLLOWED_PROFILES:
                $oModule = BxDolModule::getInstance($sModuleName);
                $aData = $this->_oDb->getMostFollowedProfiles($sModuleName, $iDateFrom, $iDateTo);
                break;
                  
            case BX_ANALYTICS_MOST_ACTIVE_PROFILES:
                $aData = array();
                $oModule = BxDolModule::getInstance($sModuleName);
                $aTmp = $this->getSelectedModules();
                foreach($aTmp[0] as $sContentModule){
                    $oContentModule = BxDolModule::getInstance($sContentModule);
                    if ($oContentModule && isset($oContentModule->_oConfig->CNF['TABLE_ENTRIES']) && isset($oContentModule->_oConfig->CNF['FIELD_AUTHOR'])){
                        $sContentTable = $oContentModule->_oConfig->CNF['TABLE_ENTRIES'];
                        $sColumnAuthor = $oContentModule->_oConfig->CNF['FIELD_AUTHOR'];
                        $sColumnAdded = $oContentModule->_oConfig->CNF['FIELD_ADDED'];
                        if (!empty($sContentTable) && !empty($sColumnAuthor) && !empty($sColumnAdded) && (!BxDolRequest::serviceExists($sContentModule, 'act_as_profile') || BxDolService::call($sContentModule, 'act_as_profile') == false)){
                            $aData1 = $this->_oDb->getMostActiveProfiles($sModuleName, $sContentModule, $sContentTable, $sColumnAuthor, $sColumnAuthor, $sColumnAdded, $iDateFrom, $iDateTo);
                            foreach($aData1 as $aTmp3){
                                if (array_key_exists($aTmp3['object_id'], $aData))
                                    $aData[$aTmp3['object_id']]['value'] += $aTmp3['value'];
                                else
                                    $aData[$aTmp3['object_id']] = $aTmp3;
                            }
                        }
                    }
                }
                $aData = $this->sortArrayOfArraysAndSlice($aData, 'value', intval(getParam('bx_analytics_items_count')));
                break;
                
            default:
                $sType = "line";
                $bIsTimeX = true;
                $sTableName = '';
                $aValues['strings'][0] = _t('_bx_analytics_txt_date');
                $bDefaultFillData = false;
                if ($sModuleName != BX_ANALYTICS_SYSTEM){
                    $oModule = BxDolModule::getInstance($sModuleName);
                    if (isset($oModule->_oConfig->CNF['TABLE_ENTRIES']))
                        $sTableName = $oModule->_oConfig->CNF['TABLE_ENTRIES'];
                    $aValues['strings'][1] = ($sReportType == BX_ANALYTICS_CONTENT_TOTAL ? _t('_bx_analytics_txt_total_count', $oModule->_aModule['title']) : _t('_bx_analytics_txt_count', $oModule->_aModule['title']));
                }
                else{
                    $sTableName = 'sys_accounts';
                    $aValues['strings'][1] = ($sReportType == BX_ANALYTICS_CONTENT_TOTAL ? _t('_bx_analytics_txt_total_count', _t('_bx_analytics_txt_accounts')) : _t('_bx_analytics_txt_count', _t('_bx_analytics_txt_accounts')));
                }
                if (isset($oModule) && isset($oModule->_oConfig->CNF['FIELD_ADDED']))
                    $sColumnAdded = $oModule->_oConfig->CNF['FIELD_ADDED'];
                else
                    $sColumnAdded = 'added';
                
                $aData = array();
                $iValuePrev = 0;
                
                /**
                 * @hooks
                 * @hookdef hook-bx_analytics-get_chart_data_line 'bx_analytics', 'get_chart_data_line' - hook on get avalaible reports for specified module
                 * - $unit_name - equals `bx_analytics`
                 * - $action - equals `get_chart_data_line` 
                 * - $object_id - not used
                 * - $sender_id -not used
                 * - $extra_params - array of additional params with the following array keys:
                 *      - `module` - [string] module name
                 *      - `report_name` - [string] report name
                 *      - `report_type` - [string]  by ref, report name, can be overridden in hook processing
                 *      - `date_from` - [int] report's start of interval in php time format
                 *      - `date_to` - [int] report's end of interval in php time format
                 *      - `data` - [array] by ref, array of data for chart, can be overridden in hook processing
                 *      - `prev_value` - [int] by ref, previous value for chart (started value), can be overridden in hook processing
                 * @hook @ref hook-bx_analytics-get_chart_data_line
                 */
                bx_alert($this->_aModule['name'], 'get_chart_data_line', 0, 0, array(
                    'module' => $sModuleName,
                    'report_name' => $sReportName,
                    'report_type' => &$sReportType,
                    'date_from' => $iDateFrom,
                    'date_to' => $iDateTo,
                    'data' => &$aData,
                    'prev_value' => &$iValuePrev,
                ));
                
                
                
                if ($sColumnAdded != '' && count($aData) == 0){
                    $aData = $this->_oDb->getGrowth($sTableName, $sColumnAdded, $iDateFrom, $iDateTo);
                    $iValuePrev = $this->_oDb->getGrowthInitValue($sTableName, $sColumnAdded, $iDateFrom);
                }
                
                if (count($aData) > 0){
                    $aTmpDates = array();
                    foreach ($aData as $aValue) {
                        $sX = $aValue['period'];
                        $aTmpDates[$sX] = $aValue['count'];
                    }
                    for ($i = $iDateFrom; $i < $iDateTo ; $i = $i + 86400 ){
                        $sX = date('Y-m-d', $i);
                        if (!array_key_exists($sX, $aTmpDates)){
                            array_push($aValues['values'][0]['data'], array('x' => $sX, 'y' => $sReportType == BX_ANALYTICS_CONTENT_TOTAL ? $iValuePrev : 0));
                        }
                        else{
                            array_push($aValues['values'][0]['data'], array('x' => $sX, 'y' => $sReportType == BX_ANALYTICS_CONTENT_TOTAL ? ($iValuePrev + $aTmpDates[$sX]) : $aTmpDates[$sX]));
                            $iValuePrev += $aTmpDates[$sX];
                        }
                        //array_push($aValues['labels'], $sX);
                        if ($sReportType == BX_ANALYTICS_CONTENT_TOTAL){
                            if ($iValuePrev > $iMaxValueY)
                                $iMaxValueY = $iValuePrev;
                        }
                        else{
                            if (array_key_exists($sX, $aTmpDates) && $aTmpDates[$sX] > $iMaxValueY)
                                $iMaxValueY = $aTmpDates[$sX];
                        } 
                    }
                }
                break;
        }
          
        if ($bDefaultFillData){
            foreach ($aData as $aValue) {
                array_push($aValues['labels'], $this->getItemName($oModule->serviceGetTitle($aValue['object_id'])));
                array_push($aValues['values'][0]['data'], $aValue['value']);
                array_push($aValues['links'], $oModule->serviceGetLink($aValue['object_id']));
                if ($aValue['value'] > $iMaxValueY)
                    $iMaxValueY = $aValue['value'];
            }
        }
        $iMaxValueY = ceil($iMaxValueY * 1.1);
        
        $aDataForChartXAxes = array();
        if ($bIsTimeX){
            $sUnit = 'day';
            $iInterval = ($iDateTo - $iDateFrom) / 86400;
            if ($iInterval > 50)
                $sUnit = 'week';
            if ($iInterval > 100)
                $sUnit = 'month';
            
            $aDataForChartXAxes = array(
                'type' => 'time',
                'time' => array(
                    'tooltipFormat' => 'DD.MM.YYYY',
                    'unit' => $sUnit,
                 ),
                'display' => true,
                'distribution' => 'linear',
                'ticks' => array(
                    'display' => true, 
                    'autoSkip' => true, 
                )
            );
        }
        else{
            $aDataForChartXAxes = array(
                'ticks' => array(
                    'autoSkip' => true
                ),
                'display' => true
            );
        }
       
        $aDataForChartDatasets = array();
        for($i = 0; $i < count($aValues['values']); $i++){
            $aDataForChartDatasets[] = array(
                'label' => $aValues['values'][$i]['legend'],
                'fill' => false,
                'backgroundColor' => $this->aColors[$i],
                'borderColor' => $this->aColors[$i],
                'borderWidth' => 2,
                'data' => $aValues['values'][$i]['data']
            );
        }
            
        $aDataForChart = array(
            'type' => $sType,
            'data' => array(
                'labels' => $aValues['labels'], 
                'datasets' => $aDataForChartDatasets
            ),
            'options' => array(
                'legend' => array(
                    'position' => 'bottom', 
                    'display' => count($aValues['values']) == 1 ? false :true
                ),
                'elements' => array(
                    'point'=> array(
                        'radius' => 0
                    )
                ),
                'scales' => array(
                    'yAxes' => array(
                        array(
                            'ticks' => array(
                                'max' => $iMaxValueY, 
                                'min' => $iMinValueY, 
                                'stepSize' => $this->getStep($iMinValueY, $iMaxValueY), 
                                'autoSkip' => true
                            )
                        )
                    ),
                    'xAxes' => array($aDataForChartXAxes)
                )
            ),
            'links' => $aValues['links'],
            'strings' => $aValues['strings'],
        );
        
        return json_encode($aDataForChart);
    }
	
	public function checkAllowed($isPerformAction = false)
    {
        $aCheck = checkActionModule(bx_get_logged_profile_id(), 'use analytics', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
             return _t('_sys_txt_access_denied');
        return CHECK_ACTION_RESULT_ALLOWED;
    }
          
    public function getSelectedModules()
    {
        $aModulesList = $this->serviceGetModules();
        $aModules = array_keys($aModulesList);
        $sModulesDisabled = explode(',', getParam('bx_analytics_modules_disabled'));
        return array(array_diff($aModules, $sModulesDisabled), $aModulesList);
    }

    private function getViewsOrVotes($aSystems, $sSystem, $sModuleName, $iDateFrom, $iDateTo, &$aValues, &$iMaxValueY, &$iMinValueY)
    {
        $aData = array();
        if ($sModuleName != BX_ANALYTICS_SYSTEM){
            $oModule = BxDolModule::getInstance($sModuleName);
            $sSystem = $oModule->_oConfig->CNF['OBJECT_VIEWS'];
            if(isset($aSystems[$sSystem]))
                $aData = $this->_oDb->getTopContentByLikes($sModuleName, $aSystems[$sSystem]['table_track'], $iDateFrom, $iDateTo);
        }
        else{
            $aTmp = $this->getSelectedModules();
            foreach ($aTmp[0] as $sModule) {
                if ($sModule != BX_ANALYTICS_SYSTEM){
                    $oModule = BxDolModule::getInstance($sModule);
                    if (isset($oModule->_oConfig->CNF['OBJECT_VIEWS'])){
                        $sSystem = $oModule->_oConfig->CNF['OBJECT_VIEWS'];
                        if(isset($aSystems[$sSystem])){
                            $aData1 = $this->_oDb->getTopContentByLikes($sModule, $aSystems[$sSystem]['table_track'], $iDateFrom, $iDateTo);
                            $aData = array_merge($aData, $aData1);
                        }
                    }
                }
            }
            $aData = $this->sortArrayOfArraysAndSlice($aData, 'value', intval(getParam('bx_analytics_items_count')));
        }
        foreach ($aData as $aValue) {
            $oModule = BxDolModule::getInstance($aValue['module']);
            array_push($aValues['labels'], $this->getItemName($oModule->serviceGetTitle($aValue['object_id'])));
            array_push($aValues['values'][0]['data'], $aValue['value']);
            array_push($aValues['links'], $oModule->serviceGetLink($aValue['object_id']));
            if ($aValue['value'] > $iMaxValueY)
                $iMaxValueY = $aValue['value'];
        }
    }
          
    private function getStep($iMin, $iMax)
    {
        $iCount = 8;
        return ceil(($iMax - $iMin) / $iCount);
    }
          
    private function sortArrayOfArraysAndSlice($aArr, $sFld, $Count)
    {
        $aDataSort = array();
        foreach ($aArr as $sKey => $oRow)
        {
            $aDataSort[$sKey] = $oRow[$sFld];
        }
        array_multisort($aDataSort, SORT_DESC, $aArr);
        return array_slice($aArr, 0, $Count);
    }
          
    private function getItemName($sString)
    {
        $sString = strip_tags($sString);
        if ($sString == ''){
            $sString = _t('_bx_analytics_txt_empty_title_item');
        }
        return $sString;
    }
}

/** @} */