<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

class BxRibbonsModule extends BxBaseModGeneralModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }
    
    /**
     * ACTION METHODS
     */
    
    public function actionGetRibbons($iProfileId)
    {
        if($this->checkAllowed(true) != CHECK_ACTION_RESULT_ALLOWED)
            echo '';
        echo BxTemplFunctions::getInstance()->transBox('bx_ribbons', $this->_oTemplate->getRibbonsForSelector($iProfileId));
    }
 
    public function actionSetRibbons($iProfileId, $sRibbonsIds)
    {
       if($this->checkAllowed(true) != CHECK_ACTION_RESULT_ALLOWED)
            echo '';
        
       $this->_oDb->clearRibbonsForProfile($iProfileId);
       $aTmp = explode(',', $sRibbonsIds);
       foreach($aTmp as $iId){
           $this->_oDb->addRibbonToProfile($iProfileId, $iId);
       }
    }
    
    /**
     * SERVICE METHODS
     */
    
    /**
     * @page service Service Calls
     * @section bx_ribbons Ribbons
     * @subsection bx_ribbons-page_blocks Page Blocks
     * @subsubsection bx_ribbons-include_js include_js
     * 
     * @code bx_srv('bx_ribbons', 'include_js', [...]); @endcode
     * 
     * Get HTML for JS code including. 
     *
     * @return HTML code 
     * 
     * @see BxRibbonsModule::serviceIncludeJs
     */
    /** 
     * @ref bx_ribbons-include_js "include_js"
     */
    public function serviceIncludeJs ()
    {
        $this->_oTemplate->addJs(array('main.js'));
        return $this->_oTemplate->getJsCode('ribbons');
    }
    
    /**
     * @page service Service Calls
     * @section bx_ribbons Ribbons
     * @subsection bx_ribbons-page_blocks Page Blocks
     * @subsubsection bx_ribbons-get_ribbons_manage get_ribbons_manage
     * 
     * @code bx_srv('bx_ribbons', 'get_ribbons_manage', [...]); @endcode
     * 
     * Get HTML form for manage. 
     *
     * @return HTML form code
     * 
     * @see BxRibbonsModule::serviceGetRibbonsManage
     */
    /** 
     * @ref bx_ribbons-get_ribbons_manage "get_ribbons_manage"
     */
    public function serviceGetRibbonsManage()
    {
        $this->_oTemplate->addJs('jquery.form.min.js');
        $oGrid = BxDolGrid::getObjectInstance($this->_oConfig->CNF['OBJECT_GRID']);
        if(!$oGrid)
            return '';
        
        return $oGrid->getCode();
    }

    /**
     * @page service Service Calls
     * @section bx_ribbons Ribbons
     * @subsection bx_ribbons-page_blocks Page Blocks
     * @subsubsection bx_ribbons-get_ribbons get_ribbons
     * 
     * @code bx_srv('bx_ribbons', 'get_ribbons', [...]); @endcode
     * 
     * Get HTML code for block.
     *
     * @param $iProfileId integer value with profile ID.
     * @return form code 
     * 
     * @see BxRibbonsModule::serviceGetRibbons
     */
    /** 
     * @ref bx_ribbons-get_ribbons "get_ribbons"
     */
    public function serviceGetRibbons($iProfileId)
    {
        return $this->_oTemplate->getRibbonsForBlock($iProfileId);
    }
    
    /**
     * @page service Service Calls
     * @section bx_ribbons Ribbons
     * @subsection bx_ribbons-page_blocks Page Blocks
     * @subsubsection bx_ribbons-include_js get_modules
     * 
     * @code bx_srv('bx_ribbons', 'get_modules', [...]); @endcode
     * 
     * Get array of profiles modules for options
     *
     * @return array
     * 
     * @see BxRibbonsModule::serviceGetModules
     */
    /** 
     * @ref bx_ribbons-get_modules "get_modules"
     */
    public function serviceGetModules()
    {
        $aResult = array();
        $BxDolModuleQuery = BxDolModuleQuery::getInstance();
        $aModules = $BxDolModuleQuery->getModulesBy(array('type' => 'modules', 'active' => 1));
        foreach($aModules as $aModule){
            if(BxDolRequest::serviceExists($aModule['name'], 'act_as_profile')){
                $aResult[$aModule['name']] = $aModule['title'];
            }
        }
        return $aResult;
    }
    
    // ====== PERMISSION METHODS
    
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedAdd ($isPerformAction = false)
    {
        return $this->checkAllowed($isPerformAction);
    }
    
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedEdit ($aDataEntry, $isPerformAction = false)
    {
        return $this->checkAllowed($isPerformAction);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedDelete (&$aDataEntry, $isPerformAction = false)
    {
        return $this->checkAllowed($isPerformAction);
    }
    
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedSetThumb ($iContentId = 0)
    {
        return CHECK_ACTION_RESULT_ALLOWED;
    }
    
    private function checkAllowed($isPerformAction = false)
    {
        $aCheck = checkActionModule($this->_iProfileId, 'use ribbons', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return _t('_sys_txt_access_denied');
        return CHECK_ACTION_RESULT_ALLOWED;
    }
}

/** @} */
