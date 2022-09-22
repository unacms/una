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
        list($aCssCalendar, $aJsCalendar) = BxBaseFormView::getCssJsCalendar();

        $this->_oTemplate->addCss(array_merge($aCssCalendar, ['manage_tools.css']));
        $this->_oTemplate->addJs(array_merge($aJsCalendar, ['BxDolAuditManageTools.js', 'BxDolGrid.js']));
        $this->_oTemplate->addJsTranslation(['_sys_grid_search']);

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
    
    protected function _getCellContentId ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = '';
        if ($aRow['content_id'] > 0){
			$sLink = BxDolRequest::serviceExists($aRow['content_module'], 'get_link') ? BxDolService::call($aRow['content_module'], 'get_link', array($aRow['content_id'])) : '';
			$sTitle = BxDolRequest::serviceExists($aRow['content_module'], 'get_title') ? BxDolService::call($aRow['content_module'], 'get_title', array($aRow['content_id'])) : $aRow['content_title'];
            if ($sLink){
                $mixedValue = BxDolTemplate::getInstance()->parseLink($sLink, $sTitle);
            }
            else{
                $mixedValue = $sTitle;
            }
        }
        $sLinkExtras = '';
        $aExtras = unserialize($aRow['extras']);
        if (isset($aExtras['display_info'])){
            $sLinkExtras = $this->_oTemplate->parseLink('javascript:void(0)',' <i class="sys-icon info-circle"></i>' , array(
                'title' => '',
                'bx_grid_action_single' => 'show_stat',
                'bx_grid_action_data' => $aRow['extras']
            ));
        }
        
        return parent::_getCellDefault($mixedValue . $sLinkExtras, $sKey, $aField, $aRow);
    }
    
    protected function _getCellContextProfileId ($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['context_profile_id'] > 0){
    	    $oProfile = BxDolProfile::getInstance($aRow['context_profile_id']);
            if ($oProfile){
                $mixedValue = BxDolTemplate::getInstance()->parseLink($oProfile->getUrl(), $oProfile->getDisplayName());
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
    
    protected function _getCellContentModule ($mixedValue, $sKey, $aField, $aRow)
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
    
    
    protected function _getCellProfileId ($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['profile_id'] > 0){
    	    $oProfile = BxDolProfile::getInstance($aRow['profile_id']);
            if ($oProfile){
    	        $mixedValue = BxDolTemplate::getInstance()->parseLink($oProfile->getUrl(), $oProfile->getDisplayName());
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
    
    protected function _getCellAuthorId ($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['profile_id'] > 0){
    	    $oProfile = BxDolProfile::getInstance($aRow['profile_id']);
            if ($oProfile){
    	        $mixedValue = BxDolTemplate::getInstance()->parseLink($oProfile->getUrl(), $oProfile->getDisplayName());
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
        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . 
            $this->_getFilterSelectOne($this->_sFilterProfileName, $this->_sFilterProfileValue, $this->_aFilterProfileValues) . 
            $this->_getFilterSelectOne($this->_sFilterActionName, $this->_sFilterActionValue, $this->_aFilterActionValues) .
            $this->_getFilterDatePicker($this->_sFilterFromDateName, $this->_sFilterFromDateValue) .
            $this->_getFilterLabel('-') .
            $this->_getFilterDatePicker($this->_sFilterToDateName, $this->_sFilterToDateValue) .
            $this->_getFilterButton();
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
               // 'onChange' => 'javascript:' . $this->sJsObject . '.onChangeFilter(this)'
            ),
            'value' => $sFilterValue,
            'values' => $aFilterValues
        );

        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputModules);
    }
    
    protected function _getFilterLabel($sFilterValue)
    {
        $aInputModules = array(
            'type' => 'value',
            'value' => $sFilterValue,
            'tr_attrs' => array('class' => 'bx-grid-controls-filter-label'),
        );

        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputModules, true);
    }
    
    protected function _getFilterDatePicker($sFilterName, $sFilterValue)
    {
        if(empty($sFilterName))
            return '';
        
        $aInputModules = array(
            'type' => 'datepicker',
            'name' => $sFilterName,
            'attrs' => array(
                'id' => 'bx-grid-' . $sFilterName . '-' . $this->_sObject,
            ),
            'tr_attrs' => array('class' => 'bx-grid-controls-filter-datepicker'),
            'value' => $sFilterValue,
        );

        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputModules, true);
    }
    
    protected function _getFilterButton()
    {
        $aInputModules = array(
            'type' => 'button',
            'name' => 'button',
            'attrs' => array(
                'id' => 'bx-grid-button-' . $this->_sObject,
                'onClick' => 'javascript:' . $this->sJsObject . '.onChangeFilter(this)',
            ),
            'tr_attrs' => array('class' => 'bx-grid-controls-filter-button'),
            'value' => _t('_Search'),
        );

        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputModules, true);
    }
    
    public function performActionShowStat()
    {
		$aTmp2 = bx_get('ids');
		$sData = $aTmp2[0];
        $aData = unserialize($sData);
        $sContentInfo = '';
        if (isset($aData['display_info'])){
            foreach($aData['display_info'] as $sKey => $sValue)
                $sContentInfo .= $sKey . ': ' . $sValue;
        }
		
		$sContent = BxTemplStudioFunctions::getInstance()->popupBox('sys-audit-content-info', _t('_sys_audit_content_info_popup_title'), $sContentInfo);
        
		echoJson(array('popup' => $sContent));
	}
    
}

/** @} */
