<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBaseAuditGrid extends BxDolAuditGrid
{
    protected $sJsObject = 'oBxDolAuditManageTools';
    
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
    }
    
    public function getCode($isDisplayHeader = true)
    {
        return $this->getJsCode() . parent::getCode($isDisplayHeader);
    }
    
    public function getJsCode()
    {
        $aParams = array(
            'sObjName' => $this->sJsObject,
            'aHtmlIds' => array(),
            'oRequestParams' => array(),
        	'sObjNameGrid' => 'sys_audit_administration'
        );
        return BxDolTemplate::getInstance()->_wrapInTagJsCode("var " . $this->sJsObject . " = new BxDolAuditManageTools(" . json_encode($aParams) . ");");
    }
    
    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellAction ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t($aRow['action_lang_key'], $aRow['action_lang_key_params']), $sKey, $aField, $aRow);
    }
    
    protected function _getCellContent ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = '';
        if ($aRow['content_id'] > 0 && $aRow['content_info_object'] != ''){
            $oContentInfo = BxDolContentInfo::getObjectInstance($aRow['content_info_object']);
            if ($oContentInfo){
    	        $sTitle = bx_process_output($oContentInfo->getContentTitle($aRow['content_id']));
                if ($sTitle != ''){
                    $mixedValue =  $this->_oTemplate->parseHtmlByName('account_link.html', array(
                        'href' => $oContentInfo->getContentLink($aRow['content_id']),
                        'title' =>  $sTitle,
                        'content' =>  $sTitle,
                        'class' => 'bx-def-font-grayed'
                    ));
                }
            }
        }
        if ($mixedValue == ''){
            $mixedValue = bx_process_output($aRow['content_title']);
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellContext ($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['context_profile_id'] > 0){
    	    $oProfile = BxDolProfile::getInstance($aRow['context_profile_id']);
            if ($oProfile){
    	        $sProfile = $oProfile->getDisplayName();
                $mixedValue =  $this->_oTemplate->parseHtmlByName('account_link.html', array(
                    'href' => $oProfile->getUrl(),
                    'title' => $sProfile,
                    'content' => $sProfile,
                    'class' => 'bx-def-font-grayed'
                ));
            }
            else{
                $mixedValue = $aRow['context_profile_title'];
            }
        }
        else{
            $mixedValue = '';
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellModule ($mixedValue, $sKey, $aField, $aRow)
    {
        $oModule = bxDolModule::getInstance($aRow['content_module']);
        if($oModule && $oModule instanceof iBxDolContentInfoService){
            $mixedValue = $oModule->_aModule['title'];
        }
        else{
            $mixedValue = $aRow['content_module'];
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    
    protected function _getCellProfile ($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['profile_id'] > 0){
    	    $oProfile = BxDolProfile::getInstance($aRow['profile_id']);
            if ($oProfile){
    	        $sProfile = $oProfile->getDisplayName();
                $mixedValue =  $this->_oTemplate->parseHtmlByName('account_link.html', array(
                    'href' => $oProfile->getUrl(),
                    'title' => $sProfile,
                    'content' => $sProfile,
                    'class' => 'bx-def-font-grayed'
                ));
            }
            else{
                $mixedValue = $aRow['profile_title'];
            }
        }
        else{
            $mixedValue = '';
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    function _getFilterControls()
    {
        parent::_getFilterControls();

        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values);
    }
    
    protected function _getFilterSelectOne($sFilterName, $sFilterValue, $aFilterValues)
    {
        if(empty($sFilterName) || empty($aFilterValues))
            return '';

		foreach($aFilterValues as $sKey => $sValue)
			$aFilterValues[$sKey] = _t($sValue);

        $aInputModules = array(
            'type' => 'select',
            'name' => $sFilterName,
            'attrs' => array(
                'id' => 'bx-grid-' . $sFilterName . '-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->sJsObject . '.onChangeFilter(this)'
            ),
            'value' => $sFilterValue,
            'values' => $aFilterValues
        );

        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputModules);
    }
}

/** @} */
