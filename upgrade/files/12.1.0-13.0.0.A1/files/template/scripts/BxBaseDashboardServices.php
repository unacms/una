<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Services for dashboard objects functionality
 * @see BxDolChart
 */
class BxBaseDashboardServices extends BxDol
{
    public function serviceManageReports()
    {
        $oMenu = BxDolMenu::getObjectInstance('sys_dashboard_reports');
        if(!$oMenu)
            return '';

        $BxDolModuleQuery = BxDolModuleQuery::getInstance();
        $aModules = $BxDolModuleQuery->getModulesBy(array('type' => 'modules', 'active' => 1));
    	$aModulesList = array();
        foreach($aModules as $iKey => $aModule){
            $oModule = BxDolModule::getInstance($aModule['name']);
            if ($oModule instanceof iBxDolContentInfoService){
                $CNF = $oModule->_oConfig->CNF;
                if (isset($CNF['OBJECT_REPORTS'])){
                    $aModule['selected'] = false;
                    $aModulesList[$aModule['uri']] = $aModule;
                }
                else{
                    unset($aModules[$iKey]);
                }
            }
            else{
                unset($aModules[$iKey]);
            }
        }
        $aModules = array_values($aModules);
        $sSelected = bx_get('module');
        
        if ($sSelected == ''){
            $aModulesList[$aModules[0]['uri']]['selected'] = true; 
            $sSelected = $aModules[0]['uri'];
        }
        else{
            $aModulesList[$sSelected]['selected'] = true;
        }
        
        $oMenu->setMenuData($aModulesList);
        
        $oGrid = BxDolGrid::getObjectInstance('sys_reports_administration');
        $oGrid->setModule($aModulesList[$sSelected]['name']);
        
        if(!$oGrid)
            return '';

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addJs(array('BxDolReportsManageTools.js', 'BxDolGrid.js'));
		$oTemplate->addCss(array('manage_tools.css'));
        $oTemplate->addJsTranslation(array('_sys_grid_search'));

    	return array(
            'content' => $oGrid->getCode(),
            'menu' => $oMenu
        );
    }
    
    public function serviceGetReportsCount($sModule, $iStatus)
    {
        $iCount = 0;
        $oModule = BxDolModule::getInstance($sModule);
        if ($oModule instanceof iBxDolContentInfoService){
            $CNF = $oModule->_oConfig->CNF;
            if (isset($CNF['OBJECT_REPORTS']))
                $iCount = BxDolService::call($sModule, 'reports_count_by_status', array($iStatus));
        }
        if ($iCount > 0)
            return $iCount;
        return ;
    }
    
    public function serviceManageAudit()
    {
        return bx_srv('system', 'manage_tools', array(), 'TemplAuditServices');
    }
    
    public function serviceManageContent()
    {
        $oMenu = BxDolMenu::getObjectInstance('sys_dashboard_content');
        if(!$oMenu)
            return '';

        $BxDolModuleQuery = BxDolModuleQuery::getInstance();
        $aModules = $BxDolModuleQuery->getModulesBy(array('type' => 'modules', 'active' => 1));
    	$aModulesList = array();
        foreach($aModules as $iKey => $aModule){
            $oModule = BxDolModule::getInstance($aModule['name']);
            if ($oModule instanceof iBxDolContentInfoService && BxDolRequest::serviceExists($aModule['name'], 'manage_tools')){
                $aModule['selected'] = false;
                $aModulesList[$aModule['uri']] = $aModule;
            }
            else{
                unset($aModules[$iKey]);
            }
        }
        $aModules = array_values($aModules);
        $sSelected = bx_get('module');
        
        if ($sSelected == ''){
            $aModulesList[$aModules[0]['uri']]['selected'] = true; 
            $sSelected = $aModules[0]['uri'];
        }
        else{
            $aModulesList[$sSelected]['selected'] = true;
        }
        
        $oMenu->setMenuData($aModulesList);
        
        $aGrid = BxDolService::call($aModulesList[$sSelected]['name'], 'manage_tools', array('administration'));
     
    	return array(
            'content' => $aGrid['content'],
            'menu' => $oMenu
        );
    }
    
}

/** @} */
