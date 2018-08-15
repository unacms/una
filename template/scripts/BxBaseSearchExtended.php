<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Extended Search objects representation.
 * 
 * @see BxDolSearchExtended
 */
class BxBaseSearchExtended extends BxDolSearchExtended
{
    protected $_sFormClassName;
    protected $_sFormClassPath;

    protected $_oForm;
    protected $_oTemplate;

    public function __construct ($aObject, $oTemplate = null)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else if(!empty($this->_aObject['module']))
            $this->_oTemplate = BxDolModule::getInstance($this->_aObject['module'])->_oTemplate;
        else 
            $this->_oTemplate = BxDolTemplate::getInstance();

        $this->_sFormClassName = '';
        $this->_sFormClassPath = '';

        $this->_oForm = null;
    }

    public function getForm()
    {
        if(!$this->isEnabled())
            return '';

        $oForm = $this->prepareForm();
        return $oForm->getCode();
    }

    /**
     * Get search results from search form or from custom condition
     *
     * @param $aParams['cond'] custom condition to pass instead of form submission, 
     *        conditions are key&value pair, where 'key' is form input name and 
     *        'value' is the term to search for
     * @param $aParams['start'] position of first record to display 
     * @param $aParams['per_page'] number of items per page
     * @param $aParams['template'] custom unit templates to use
     * @return HTML string with search results
     */ 
    public function getResults($aParams = array())
    {
        if(!$this->isEnabled())
            return '';

        $bCondition = !empty($aParams['cond']) && is_array($aParams['cond']);
        $aCondition = $bCondition ? $aParams['cond'] : array();

        $iStart = !empty($aParams['start']) ? $aParams['start'] : 0;
        $iPerPage = !empty($aParams['per_page']) ? $aParams['per_page'] : 0;
        unset($aParams['start'], $aParams['per_page']);

        $sUnitTemplate = !empty($aParams['template']) ? $aParams['template'] : '';

        $oForm = $this->prepareForm();
        if(!empty($aCondition) && is_array($aCondition)) {
            $mixedSubmitName = $oForm->aParams['db']['submit_name'];
            if(is_array($mixedSubmitName))
                $mixedSubmitName = array_pop($mixedSubmitName);
            $aCondition[$mixedSubmitName] = 1;
            $oForm->aFormAttrs['method'] = BX_DOL_FORM_METHOD_SPECIFIC;
            $oForm->aParams['csrf']['disable'] = true;

            $oForm->initChecker(array(), $aCondition);
        }

        if(!$oForm->isSubmittedAndValid()) 
            return '';

        $oContentInfo = BxDolContentInfo::getObjectInstance($this->_aObject['object_content_info']);
        if(!$oContentInfo)
            return '';

        $aParamsSearch = array();
        foreach($this->_aObject['fields'] as $aField) {
            $sValue = $oForm->getCleanValue($aField['name']);
            if(empty($sValue) || (is_array($sValue) && bx_is_empty_array($sValue)))
                continue;

            $aParamsSearch[$aField['name']] = array(
                'type' => $aField['search_type'],
                'value' => $sValue,
                'operator' => $aField['search_operator']
            );

            if(!$bCondition)
                $aParams['cond'][$aField['name']] = $sValue;
        }

        if(empty($aParamsSearch) || !is_array($aParamsSearch))
            return '';

        if (!$iPerPage) {
            bx_import('BxDolSearch');
            $iPerPage = BX_DOL_SEARCH_RESULTS_PER_PAGE_DEFAULT;
        }

        $aResults = false;
        bx_alert('search', 'get_data', 0, false, array('object' => $this->_aObject, 'search_params' => $aParamsSearch, 'search_results' => &$aResults));
    	if($aResults === false)
    	    $aResults = $oContentInfo->getSearchResultExtended($aParamsSearch, $iStart, $iPerPage + 1);

    	if(empty($aResults) || !is_array($aResults))
    	    return '';

        if(!empty($aParams['cond']) && is_array($aParams['cond']))
            $aParams['cond'] = urlencode(serialize($aParams['cond']));

        $oPaginate = new BxTemplPaginate(array(
            'on_change_page' => "return !loadDynamicBlockAutoPaginate(this, '{start}', '{per_page}', " . bx_js_string(json_encode($aParams)) . ");",
            'start' => $iStart,
            'per_page' => $iPerPage
        ));
        $oPaginate->setNumFromDataArray($aResults);

        $bTmplVarsPaginate = $iStart || $oPaginate->getNum() > $iPerPage;
        $aTmplVarsPaginate = $bTmplVarsPaginate ? array('paginate' => $oPaginate->getSimplePaginate()) : array();

    	$sResults = '';
    	foreach($aResults as $iId)
    	    $sResults .= $oContentInfo->getContentSearchResultUnit($iId, $sUnitTemplate);   	

        return $this->_oTemplate->parseHtmlByName('search_extended_results.html', array(
            'code' => $sResults,
            'bx_if:show_paginate' => array(
                'condition' => $bTmplVarsPaginate,
                'content' => $aTmplVarsPaginate
            )
        ));
    }

    protected function &prepareForm()
    {
        if(!empty($this->_oForm) && $this->_oForm instanceof BxDolForm)
            return $this->_oForm;

        $sForm = 'sys_search_extended_' . $this->_sObject;
        $sFormSubmit = 'search' . $this->_sObject;

        $aForm = array(
            'form_attrs' => array(
                'id' => $sForm,
                'name' => $sForm,
                'action' => '',
                'method' => 'post'
            ),
            'params' => array(
                'db' => array(
                    'table' => '',
                    'key' => '',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => $sFormSubmit
                ),
            ),
            'inputs' => array()
        );

        foreach ($this->_aObject['fields'] as $aField) {
            if((int)$aField['active'] == 0)
                continue;

            if (in_array($aField['search_type'], array('checkbox_set', 'select_multiple')) && (isset($aField['values']['']) || isset($aField['values'][0]))) {
                unset($aField['values']['']);
                unset($aField['values'][0]);
            }
            
            $aAttrs = array();
            
            if(in_array($aField['search_type'], array('datepicker_range_age')) && isset($aField['search_value']) && $aField['search_value'] != ''){
                $aTmp = BxDolService::callSerialized($aField['search_value']);
                if (isset($aTmp['min']) && isset($aTmp['max'])){
                    $aField['search_value'] = $aTmp['min'] . '-' . $aTmp['max'];
                    $aAttrs = array('min' => $aTmp['min'], 'max' => $aTmp['max'], 'step' => 1);
                }
            }
            
            if(in_array($aField['search_type'], array('datepicker_range')) && isset($aField['search_value']) && $aField['search_value'] != ''){
                $aField['search_value'] = '';
            }
           
            $aForm['inputs'][$aField['name']] = array(
                'type' => $aField['search_type'],
                'name' => $aField['name'],
                'caption' => _t($aField['caption']),
            	'info' => _t($aField['info']),
                'values' => $aField['values'],
                'value' => $aField['search_value'],
                'attrs' => $aAttrs,
                'db' => array(
                    'pass' => !empty($aField['pass']) ? $aField['pass'] : 'Xss'
                )
            );
        }

        $aForm['inputs']['search'] = array(
            'type' => 'submit',
            'name' => $sFormSubmit,
            'value' => _t('_Search')
        );

        $sClass = 'BxTemplSearchExtendedForm';
        if(!empty($this->_sFormClassName)) {
            $sClass = $this->_sFormClassName;
            if(!empty($this->_sFormClassPath))
                require_once(BX_DIRECTORY_PATH_ROOT . $this->_sFormClassPath);
        }

        $this->_oForm = new $sClass($aForm, $this->_oTemplate);
        $this->_oForm->initChecker();

        return $this->_oForm;
    }
}

/** @} */
