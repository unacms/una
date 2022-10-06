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
        
        $aSystems = BxDolReport::getSystems();
        $sSelected = bx_get('object');
        
        if ($sSelected == ''){
            $sSelected = reset($aSystems)['name'];
        }
        
        $oGrid = BxDolGrid::getObjectInstance('sys_reports_administration');
        $oGrid->setObject($aSystems[$sSelected]['name']);
        
        if(!$oGrid)
            return '';

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addJs(array('BxDolReportsManageTools.js', 'BxDolGrid.js'));
		$oTemplate->addCss(array('manage_tools.css'));
        $oTemplate->addJsTranslation(array('_sys_grid_search'));
        
    	return array(
            'content' =>$oGrid->getCode(),
            'menu' => $oMenu
        );
    }
    
    public function serviceGetReportsCount($sObjectReposrt, $iStatus)
    {
        $oReport = BxDolReport::getObjectInstance($sObjectReposrt, 0, false);
        $iCount = $oReport->getCountByStatus($iStatus);
        
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

        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(['type' => 'modules', 'active' => 1]);

    	$aModulesList = [];
        foreach($aModules as $aModule) {
            $oModule = BxDolModule::getInstance($aModule['name']);
            if(!$oModule || !($oModule instanceof iBxDolContentInfoService))
                continue;

            if(empty($oModule->_oConfig->CNF['OBJECT_GRID_ADMINISTRATION']) || !bx_is_srv($aModule['name'], 'manage_tools'))
                continue;

            $aModulesList[$aModule['uri']] = $aModule;
        }

        $sSelected = bx_get('module');        
        if($sSelected == '')
            $sSelected = reset($aModulesList)['uri'];

        $sContent = '';
        if(isset($aModulesList[$sSelected])) {
            $sSelectedModule = $aModulesList[$sSelected]['name'];

            $aBlock = bx_srv($sSelectedModule, 'manage_tools', array('administration'));
            if(!empty($aBlock) && is_array($aBlock))
                $sContent = $aBlock['content'];
        }
        else {
            $sSelectedModule = 'system';

            $sMethod = '_getManageContent' . bx_gen_method_name($sSelected);
            if(method_exists($this, $sMethod))
                $sContent = $this->$sMethod();
        }

        $oMenu->setMenuData($aModulesList);
        $oMenu->setSelected($sSelectedModule, $sSelected);

    	return array(
            'content' => $sContent,
            'menu' => $oMenu
        );
    }

    protected function _getManageContentCmts()
    {
        $aBlock = bx_srv('system', 'manage_tools', ['administration'], 'TemplCmtsServices');
        if(empty($aBlock) || !is_array($aBlock))
            return '';

        return $aBlock['content'];
    }
}

/** @} */
