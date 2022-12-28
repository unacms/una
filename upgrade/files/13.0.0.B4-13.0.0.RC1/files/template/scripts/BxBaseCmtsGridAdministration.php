<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBaseCmtsGridAdministration extends BxDolCmtsGridAdministration
{
    protected $sJsObject = 'oBxDolCmtsManageTools';
    
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
            'sObjNameGrid' => 'sys_cmts_administration'
        );
        return BxDolTemplate::getInstance()->_wrapInTagJsCode("var " . $this->sJsObject . " = new BxDolCmtsManageTools(" . json_encode($aParams) . ");");
    }
    
    public function performActionDelete($aParams = array())
    {
        $iAffected = 0;
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }
        
        $sFilter = bx_get('filter');
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);
        if(!empty($this->_sFilter1Value)){
            $this->_oSelectedModule = BxDolModule::getInstance($this->_sFilter1Value);
            $oCmts = BxDolCmts::getObjectInstance($this->_oSelectedModule->_oConfig->CNF['OBJECT_COMMENTS'], 0, false);
            if ($oCmts){
                $aIdsAffected = array ();
                foreach($aIds as $iId) {
                    $aTmp = $oCmts->remove($iId);
                    if (isset($aTmp['id'])){
                        $aIdsAffected[] = $iId;
                        $iAffected++;
                    }
                }
            }
        }
        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t('_sys_grid_delete_failed')));
    }
    
    protected function _getFilterControls()
    {
        parent::_getFilterControls();

        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . $this->_getSearchInput();
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

    protected function _getSearchInput()
    {
        $aInputSearch = array(
            'type' => 'text',
            'name' => 'search',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject,
                'onKeyup' => 'javascript:$(this).off(\'keyup focusout\'); ' . $this->sJsObject . '.onChangeFilter(this)',
                'onBlur' => 'javascript:' . $this->sJsObject . '.onChangeFilter(this)',
            )
        );
        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputSearch);
    }
    
    protected function _getCellCmtTime($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellCmtText($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = strmaxtextlen($aRow['cmt_text']);

        if($this->_oCmts && $this->_oCmts->isEnabled()) {
            $this->_oCmts->setId($aRow['cmt_object_id']);

            $mixedValue = $this->_oTemplate->parseLink($this->_oCmts->getViewUrl($aRow['cmt_id']), $mixedValue, ['target' => '_blank']);
        }

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellCmtAuthorId($mixedValue, $sKey, $aField, $aRow)
    {
        if((int)$aRow['cmt_author_id'] != 0)
            $oProfile = BxDolProfile::getInstance($aRow['cmt_author_id']);
        else
            $oProfile = BxDolProfileUndefined::getInstance();

        $sProfile = $oProfile->getDisplayName();

    	$sAccountEmail = '';
    	$sManageAccountUrl = '';
    	if($oProfile && $oProfile instanceof BxDolProfile && BxDolAcl::getInstance()->isMemberLevelInSet(128)) {
            $sAccountEmail = $oProfile->getAccountObject()->getEmail();
            $sManageAccountUrl = $this->_getManageAccountUrl($sAccountEmail);
    	}

        $mixedValue = $this->_oTemplate->parseHtmlByName('author_link.html', [
            'href' => $oProfile->getUrl(),
            'title' => bx_html_attribute($sProfile),
            'content' => $sProfile,
        	'bx_if:show_account' => [
                    'condition' => !empty($sManageAccountUrl), 
                    'content' => [
                        'href' => $sManageAccountUrl,
                        'title' => _t('_sys_grid_txt_account_manager'),
                        'content' => $sAccountEmail
                    ]
        	]
        ]);

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellHeaderReports ($sKey, $aField)
    {
        $s = parent::_getCellHeaderDefault($sKey, $aField);
        return preg_replace ('/<a(.*?)>(.*?)<\/a>/', '<a$1 title="' . bx_html_attribute(_t('_sys_txt_reports_title')) . '"><i class="sys-icon exclamation-triangle"></i></a>', $s);
    }
    
    protected function _getCellReports($mixedValue, $sKey, $aField, $aRow)
    {
        if ($mixedValue == 0){
            $mixedValue = '';
        }
        else{
            $oReports = BxDolReport::getObjectInstance('sys_cmts', $aRow['id']);
            if ($oReports){
                $mixedValue = $oReports->getCounter().$oReports->getJsScript();
            }
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getManageAccountUrl($sFilter = '')
    {
    	$sModuleAccounts = 'bx_accounts';
    	if(!BxDolModuleQuery::getInstance()->isEnabledByName($sModuleAccounts))
            return '';

        $sTypeUpc = strtoupper($this->_sManageType);
        $oModuleAccounts = BxDolModule::getInstance($sModuleAccounts);
        if(!$oModuleAccounts || empty($oModuleAccounts->_oConfig->CNF['URL_MANAGE_' . $sTypeUpc]))
            return '';

        $sLink = $oModuleAccounts->_oConfig->CNF['URL_MANAGE_' . $sTypeUpc];
        $sLink = bx_absolute_url(BxDolPermalinks::getInstance()->permalink($sLink));
        if(!empty($sFilter))
            $sLink = bx_append_url_params($sLink, array('filter' => $sFilter));

        return $sLink;
    }
}

/** @} */
