<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBaseReportsGrid extends BxTemplGrid
{
    protected $_sParamsDivider;
    
    protected $_sFilter1Name;
    protected $_sFilter1Value;
    protected $_aFilter1Values;
    protected $sJsObject = 'oBxDolReportsManageTools';
    
    protected $_aReportSystemInfo;
    
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
        
        $this->_sParamsDivider = '#-#';

        $this->_sDefaultSortingOrder = 'DESC';

        $this->_sFilter1Name = 'filter1';
        $this->_aFilter1Values = array(
            '' => _t('_report_status_all'),
            0 => _t('_report_status_new'),
            1 => _t('_report_status_check_in'),
            2 => _t('_report_status_check_out'),
        );

        $sFilter2 = bx_get($this->_sFilter1Name);
        if(!empty($sFilter2)) {
            $this->_sFilter1Value = bx_process_input($sFilter2);
            $this->_aQueryAppend[$this->_sFilter1Name] = $this->_sFilter1Value;
        }

        if (bx_get('object')){
            $this->setObject(bx_get('object'));
        }
    }
    
    public function getCode($isDisplayHeader = true)
    {
        return $this->getJsCode() . parent::getCode($isDisplayHeader);
    }
    
    public function setObject($sObjectName)
    {
        $oReport = BxDolReport::getObjectInstance($sObjectName, -1, false);
        $this->_aReportSystemInfo = $oReport->getSystemInfo();
        $this->_aOptions['table'] = $this->_aReportSystemInfo['table_track'];
        $this->_aQueryAppend['object'] = $sObjectName;
    }
    
    public function getJsCode()
    {
        $aParams = array(
            'sObjName' => $this->sJsObject,
            'aHtmlIds' => array(),
            'oRequestParams' => array(),
            'sObjNameGrid' => 'sys_reports_administration'
        );
        return BxDolTemplate::getInstance()->_wrapInTagJsCode("var " . $this->sJsObject . " = new BxDolReportsManageTools(" . json_encode($aParams) . ");");
    }
    
    public function performActionCheckIn()
    {
        $this->_performActionStatus(BX_DOL_REPORT_STASUS_IN_PROCESS, '_report_comment_check_in');
    }
    
    public function performActionCheckOut()
    {
        $this->_performActionStatus(BX_DOL_REPORT_STASUS_PROCESSED, '_report_comment_check_out');
    }
    
    private function _performActionStatus($iStatus, $sCmtsText)
    {
        $aIds = bx_get('ids');
        
        $iAffected = 0;
        $aIdsAffected = array ();
        foreach($aIds as $iId) {
            $oReport = BxDolReport::getObjectInstance($this->_aReportSystemInfo['name'], $iId, true);
            $oReport->changeStatusReport($iStatus, bx_get_logged_profile_id(), _t($sCmtsText));
            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => $mixedResult));
    }
    
    
    protected function _getCellDate($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellNotes($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = '';
        if ($this->_aReportSystemInfo['object_comment'] && $this->_aReportSystemInfo['object_comment'] != ''){
            
            $oCmts = BxDolCmts::getObjectInstance($this->_aReportSystemInfo['object_comment'], $aRow['object_id']);
            if ($oCmts){
                $oModule = BxDolModule::getInstance($this->_aReportSystemInfo['name']);
                $mixedValue =  BxDolTemplate::getInstance()->parseLink('javascript:', $oCmts->getCommentsCount(), array("onclick" => "bx_get_notes(this, '" . $oModule->_aModule['uri'] . "', '" . $aRow['object_id'] . "')"));  
            }    
        }

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellComments($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = '';
        
        if ($this->_aReportSystemInfo['object_comment'] && $this->_aReportSystemInfo['object_comment'] != ''){
            
            $oCmts = BxDolCmts::getObjectInstance($this->_aReportSystemInfo['object_comment'], -$aRow['id']);
            if ($oCmts){
                $oModule = BxDolModule::getInstance($this->_aReportSystemInfo['name']);
                $mixedValue =  BxDolTemplate::getInstance()->parseLink('javascript:', $oCmts->getCommentsCount(), array("onclick" => "bx_get_notes(this, '" . $oModule->_aModule['uri'] . "', '" . - $aRow['id'] . "')")); 
            }
        }

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellObject ($mixedValue, $sKey, $aField, $aRow)
    {
        $sTitle = $sUrl = '';
        if($this->_aReportSystemInfo['name'] == 'sys_cmts'){
            $oCmts = BxDolCmts::getObjectInstanceByUniqId($aRow['object_id']);
            if($oCmts) {
                $aCmts = $oCmts->getCommentsBy([
                    'type' => 'uniq_id', 
                    'uniq_id' => $aRow['object_id']
                ]);

                $sTitle = $oCmts->getViewText($aCmts['cmt_id']);
                $sUrl = $oCmts->getViewUrl($aCmts['cmt_id']);
            }
        }
        else {
            $oContentInfo = BxDolContentInfo::getObjectInstance($this->_aReportSystemInfo['name']);
            if($oContentInfo) {
                $sTitle = $oContentInfo->getContentTitle($aRow['object_id']);
                $sUrl = $oContentInfo->getContentLink($aRow['object_id']);
            }
        }
 
        if($sTitle == '')
            $sTitle = _t('_undefined');
        
        if($sUrl != '')
            $sTitle = $this->_oTemplate->parseHtmlByName('account_link.html', array(
                'href' => $sUrl,
                'title' => bx_html_attribute($sTitle),
                'content' => $sTitle
            ));
        
        return parent::_getCellDefault($sTitle, $sKey, $aField, $aRow);
    }
    
    protected function _getCellAuthor ($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['author_id'] > 0){
    	    $oProfile = BxDolProfile::getInstance($aRow['author_id']);
            if ($oProfile){
    	        $sProfile = $oProfile->getDisplayName();
                $mixedValue =  $this->_oTemplate->parseHtmlByName('account_link.html', array(
                    'href' => $oProfile->getUrl(),
                    'title' => $sProfile,
                    'content' => $sProfile
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
    
    protected function _getCellCheckedBy ($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['checked_by'] > 0){
    	    $oProfile = BxDolProfile::getInstance($aRow['checked_by']);
            if ($oProfile){
    	        $sProfile = $oProfile->getDisplayName();
                $mixedValue =  $this->_oTemplate->parseHtmlByName('account_link.html', array(
                    'href' => $oProfile->getUrl(),
                    'title' => $sProfile,
                    'content' => $sProfile
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
    
    protected function _getCellStatus ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_sys_txt_report_status_' . $mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getActionCheckIn($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if($aRow["status"] == BX_DOL_REPORT_STASUS_IN_PROCESS)
            return '';
        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
    
    protected function _getActionCheckOut($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if ($aRow["checked_by"] != bx_get_logged_profile_id())
            return '';
        
        if($aRow["status"] != BX_DOL_REPORT_STASUS_IN_PROCESS)
            return '';
        
        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
    
    protected function _getActionAudit($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=dashboard-audit&module=' . $this->_aReportSystemInfo['module_name'] . '&content_id=' . $aRow['object_id']));

    	$a['attr'] = array_merge($a['attr'], array(
    		"onclick" => "window.open('" . $sUrl . "','_self');"
    	));

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getFilterControls()
    {
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
            'values' => array_merge(array('' => 'All'), $aFilterValues)
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

    protected function _getDataSql ($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] = "SELECT * FROM `" . $this->_aReportSystemInfo['table_track'] . "` " . $this->_aOptions['source'];

        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

        if(isset($this->_sFilter1Value) && $this->_sFilter1Value !== ''){
            $this->_aOptions['source'] .= " AND `status` = " . $this->_sFilter1Value;
        }

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
