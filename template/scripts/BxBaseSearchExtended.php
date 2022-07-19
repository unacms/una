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

    protected $_bJsMode;

    protected $_iAgeMin;
    protected $_iAgeMax;

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

        $this->_bJsMode = false;

        $this->_iAgeMin = 1;
        $this->_iAgeMax = 75;
    }

    public function getForm($aParams = array())
    {
        if(!$this->isEnabled())
            return '';

        $oForm = $this->prepareForm($aParams);
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

        $bJsMode = isset($aParams['js_mode']) ? (bool)$aParams['js_mode'] : $this->_bJsMode;
        $bCondition = !empty($aParams['cond']) && is_array($aParams['cond']);

        $iStart = !empty($aParams['start']) ? $aParams['start'] : 0;
        $iPerPage = !empty($aParams['per_page']) ? $aParams['per_page'] : 0;
        unset($aParams['start'], $aParams['per_page']);

        $sUnitTemplate = !empty($aParams['template']) ? $aParams['template'] : '';

        $oForm = $this->prepareForm($aParams);
        if($bCondition) {
            $mixedSubmitName = $oForm->aParams['db']['submit_name'];
            if(is_array($mixedSubmitName))
                $mixedSubmitName = array_pop($mixedSubmitName);
            $aParams['cond'][$mixedSubmitName] = 1;

            $oForm->aFormAttrs['method'] = BX_DOL_FORM_METHOD_SPECIFIC;
            $oForm->aParams['csrf']['disable'] = true;

            $oForm->initChecker(array(), $aParams['cond']);
        }

        if(!$oForm->isSubmittedAndValid() && !$this->_bFilterMode)
            return '';

        $oContentInfo = BxDolContentInfo::getObjectInstance($this->_aObject['object_content_info']);
        
        if(!$oContentInfo)
            return '';

        $aParamsSearch = array();
        foreach($this->_aObject['fields'] as $aField) {
            $mixedValue = $oForm->getCleanValue($aField['name']);
            if(empty($mixedValue) || (is_array($mixedValue) && bx_is_empty_array($mixedValue)))
                continue;

            $aParamsSearch[$aField['name']] = array(
                'type' => $aField['search_type'],
                'value' => $mixedValue,
                'operator' => $aField['search_operator']
            );

            if(!$bCondition) {
                if(!isset($aParams['cond']))
                    $aParams['cond'] = array();

                switch($oForm->aInputs[$aField['name']]['type']) {
                    case 'location':
                    case 'location_radius':
                        $aParams['cond'][$aField['name']] = $mixedValue['string'];

                        $aLocationComponents = BxDolMetatags::locationsParseComponents($mixedValue['array'], $aField['name']);
                        if($oForm->aInputs[$aField['name']]['type'] == 'location_radius' && count($mixedValue['array']) > count($aLocationComponents))
                            $aLocationComponents[$aField['name'] . '_rad'] = array_pop($mixedValue['array']);

                        $aParams['cond'] = array_merge($aParams['cond'], $aLocationComponents);
                        break;

                    default:
                        $aParams['cond'][$aField['name']] = $mixedValue;
                }
            }
        }

        if((empty($aParamsSearch) || !is_array($aParamsSearch)) && !$this->_bFilterMode)
            return '';

        if (!$iPerPage) {
            bx_import('BxDolSearch');
            $iPerPage = BX_DOL_SEARCH_RESULTS_PER_PAGE_DEFAULT;
        }

        $aResults = false;
        
        if (bx_get('sort')){
            $aTmp = explode(':', bx_get('sort'));
            $aParamsSearch['order'] = [['field' => $aTmp[0], 'direction' => $aTmp[1]]];
        }
        
        bx_alert('search', 'get_data', 0, false, array('object' => $this->_aObject, 'search_params' => &$aParamsSearch, 'search_results' => &$aResults));

    	if($aResults === false)
    	    $aResults = $oContentInfo->getSearchResultExtended($aParamsSearch, $iStart, $iPerPage + 1, $this->_bFilterMode);

    	if(empty($aResults) || !is_array($aResults))
    	    return '';

        if(!empty($aParams['cond']) && is_array($aParams['cond']))
            $aParams['cond'] = self::encodeConditions($aParams['cond']);

        $aPaginate = array('start' => $iStart, 'per_page' => $iPerPage);
        if(!$bJsMode) {
            $aParams['start'] = '{start}';
            $aParams['per_page'] = '{per_page}';
            list($sPageLink, $aPageParams) = bx_get_base_url_inline($aParams);

            $aPaginate['page_url'] = BxDolPermalinks::getInstance()->permalink(bx_append_url_params($sPageLink, $aPageParams, true, ['{start}', '{per_page}']));
        }
        else
            $aPaginate['on_change_page'] = "return !loadDynamicBlockAutoPaginate(this, '{start}', '{per_page}', " . bx_js_string(json_encode($aParams)) . ");";            

        $oPaginate = new BxTemplPaginate($aPaginate);
        $oPaginate->setNumFromDataArray($aResults);

        $bTmplVarsPaginate = $iStart || $oPaginate->getNum() > $iPerPage;
        $aTmplVarsPaginate = $bTmplVarsPaginate ? array('paginate' => $oPaginate->getSimplePaginate()) : array();

    	$sResults = '';
    	foreach($aResults as $iId)
    	    $sResults .= $oContentInfo->getContentSearchResultUnit($iId, $sUnitTemplate); 
        
        $sSort = '';
        if ($sResults != ''){

            $aData = $this->_aObject['sortable_fields'];
            $aValues = [];
            foreach($aData as $aField) {
                if ($aField['active'] == 1)
                    $aValues[$aField['name'] . ':' . $aField['direction']] = _t($aField['caption']) . ' ' . $aField['direction'];
            }
            
            if (!empty($aValues)){
                $aValues = array_merge(['' => _t('_sys_txt_search_sort_by_default')], $aValues);
            }
            $oForm = new BxTemplFormView(array());
            
            $sOnChange = '';
            if(!$bJsMode) {
                unset($aParams['start']);
                unset($aParams['per_page']);
                list($sPageLink, $aPageParams) = bx_get_base_url_inline($aParams);
                $sOnChange = "bx_search_extnded_sort(this,'" . BxDolPermalinks::getInstance()->permalink(bx_append_url_params($sPageLink, $aPageParams)) . "')";
            }
            else{
                $sOnChange = "return !loadDynamicBlockAutoSort(this, $(this).val()," . bx_js_string(json_encode($aParams)) . ");";
            }
            
            $sSort = '';
            if (!empty($aValues)){
                $aInputSort = array(
                    'type' => 'select',
                    'name' => 'sort',
                    'value' =>  bx_get('sort') ? bx_get('sort') : '',
                    'values' => $aValues,
                    'caption' => _t('_sys_txt_search_sort_by'),
                    'attrs' => array(
                        'onChange' => $sOnChange,
                    ),
                    'tr_attrs' => array(
                        'class' => 'sort'
                    )
                );
                $sSort = $oForm->genRow($aInputSort);
            }
            
        }

        return $this->_oTemplate->parseHtmlByName('search_extended_results.html', array(
            'sort' => $sSort,
            'code' => $sResults,
            'bx_if:show_paginate' => array(
                'condition' => $bTmplVarsPaginate,
                'content' => $aTmplVarsPaginate
            )
        ));
    }

    protected function &prepareForm($aParams = array())
    {
        if(!empty($this->_oForm) && $this->_oForm instanceof BxDolForm)
            return $this->_oForm;

        $sForm = 'sys_search_extended_' . $this->_sObject;
        $sFormSubmit = 'search' . $this->_sObject;

        list($sPageLink, $aPageParams) = bx_get_base_url_inline();

        $aForm = array(
            'form_attrs' => array(
                'id' => $sForm,
                'name' => $sForm,
                'action' => BxDolPermalinks::getInstance()->permalink(bx_append_url_params($sPageLink, array('i' => $aPageParams['i']))),
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

            if(in_array($aField['search_type'], array('checkbox_set', 'select_multiple'))) {
                if(isset($aField['values']['']))
                    unset($aField['values']['']);

                if(isset($aField['values'][0]) && !is_array($aField['values'][0]))
                    unset($aField['values'][0]);
            }

            $aAttrs = array();

            if(in_array($aField['search_type'], array('datepicker_range_age')) && !empty($aField['search_value'])) {
                $aFieldParams = BxDolService::callSerialized($aField['search_value']);
                $iMin = isset($aFieldParams['min']) && is_numeric($aFieldParams['min']) ? $aFieldParams['min'] : $this->_iAgeMin;
                $iMax = isset($aFieldParams['max']) && is_numeric($aFieldParams['max']) ? $aFieldParams['max'] : $this->_iAgeMax;

                $aField['search_value'] = $iMin . '-' . $iMax;
                $aAttrs = array('min' => $iMin, 'max' => $iMax, 'step' => 1);
            }

            if(in_array($aField['search_type'], array('datepicker_range')) && !empty($aField['search_value'])) {
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

            if(in_array($aField['search_type'], array('location', 'location_radius')))
                $aForm['inputs'][$aField['name']]['manual_input'] = true;
        }

        $aForm['inputs']['search'] = array(
            'type' => 'submit',
            'name' => $sFormSubmit,
            'value' => _t($this->_bFilterMode ? '_Apply' : '_Search')
        );

        $sClass = 'BxTemplSearchExtendedForm';
        if(!empty($this->_sFormClassName)) {
            $sClass = $this->_sFormClassName;
            if(!empty($this->_sFormClassPath))
                require_once(BX_DIRECTORY_PATH_ROOT . $this->_sFormClassPath);
        }

        $bJsMode = isset($aParams['js_mode']) ? (bool)$aParams['js_mode'] : $this->_bJsMode;
        $bCondition = !empty($aParams['cond']) && is_array($aParams['cond']);
        $aValues = !$bJsMode && $bCondition ? $aParams['cond'] : array();

        $this->_oForm = new $sClass($aForm, $this->_oTemplate);
        $this->_oForm->initChecker($aValues, $aValues);

        return $this->_oForm;
    }
}

/** @} */
