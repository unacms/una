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

    public function getResults()
    {
        if(!$this->isEnabled())
            return '';

        $oForm = $this->prepareForm();
        if(!$oForm->isSubmittedAndValid()) 
            return '';

        $oContentInfo = BxDolContentInfo::getObjectInstance($this->_aObject['object_content_info']);
        if(!$oContentInfo)
            return '';

        $aParams = array();
        foreach($this->_aObject['fields'] as $aField) {
            $sValue = $oForm->getCleanValue($aField['name']);
            if(empty($sValue))
                continue;

            $aParams[$aField['name']] = array(
                'type' => $aField['search_type'],
            	'value' => $sValue,
                'operator' => $aField['search_operator']
            );
        }

        if(empty($aParams) || !is_array($aParams))
            return '';

        $aResults = false;
        bx_alert('search', 'get_data', 0, false, array('object' => $this->_aObject, 'search_params' => $aParams, 'search_results' => &$aResults));
    	if($aResults === false)
    	    $aResults = $oContentInfo->getSearchResultExtended($aParams);

    	if(empty($aResults) || !is_array($aResults))
    	    return '';

    	$sResults = '';
    	foreach($aResults as $iId)
    	    $sResults .= $oContentInfo->getContentSearchResultUnit($iId);   	

        return $sResults;
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

            if(in_array($aField['search_type'], array('checkbox_set', 'select_multiple')) && isset($aField['values']['']))
                unset($aField['values']['']);

            $aForm['inputs'][$aField['name']] = array(
                'type' => $aField['search_type'],
                'name' => $aField['name'],
                'caption' => _t($aField['caption']),
            	'values' => $aField['values'],
                'value' => $aField['search_value'],
            	'db' => array(
                    'pass' => 'Xss'
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
