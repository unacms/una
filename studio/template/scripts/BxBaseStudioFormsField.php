<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioFormsField extends BxDolStudioFormsField
{
    protected $aForm;
    protected $sTypeTitlePrefix = '_adm_form_txt_field_type_';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $sJsObject = $this->getJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        bx_import('BxTemplStudioFormView');

        $this->aForm = array(
            'form_attrs' => array(
                'id' => '',
                'action' => '',
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_form_inputs',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                'type' => array(
                    'type' => 'custom',
                    'name' => 'type',
                    'content' => '',
                    'attrs' => array(
                        'id' => 'bx-form-field-type',
                        'class' => 'bx-form-field-type',
                        'onchange' => $sJsObject . '.onSelectType()'
                    ),
                ),
                'reset' => array (
                    'type' => 'reset',
                    'name' => 'close',
                    'value' => _t('_adm_form_btn_field_cancel'),
                    'attrs' => array(
                        'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                        'class' => 'bx-def-margin-sec-left',
                    ),
                )
            )
        );

        $aTypes = array();
        foreach($this->aTypes as $sType)
            $aTypes[$sType] = _t($this->sTypeTitlePrefix . $sType);

        asort($aTypes);

        $aMenu = array();
        foreach($aTypes as $sName => $sTitle)
            $aMenu[] = array(
                'name' => $sName,
                'icon' => 'ui-' . $sName . '.png',
                'onclick' => $sJsObject . ".onSelectType('" . $sName . "', this)",
                'title' => $sTitle
            );

        $oMenu = new BxTemplStudioMenu(array('template' => 'menu_vertical.html', 'menu_items' => $aMenu));
        $this->aForm['inputs']['type']['content'] = $oMenu->getCode();
    }

    function getJsObject()
    {
        return BX_DOL_STUDIO_FORMS_FIELDS_JS_OBJECT;
    }

    function getCode($sAction, $sObject)
    {
        $sFunction = 'getCode' . $this->getClassName($sAction);
        if(method_exists($this, $sFunction))
            return $this->$sFunction($sAction, $sObject);

        return false;
    }

    protected function getCodeAdd($sAction, $sObject)
    {
        $aForm = $this->getFormAdd($sAction, $sObject);
        $oForm = new BxTemplStudioFormView($aForm);

        if($oForm->isSubmitted() && isset($oForm->aInputs['required']))
            $this->updateCheckerFields($oForm);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            //--- Process field name.
            $sInputName = $oForm->getCleanValue('name');
            if(empty($sInputName)) {
            	$sInputObject = $oForm->getCleanValue('object');

	            $sLanguage = BxDolStudioLanguagesUtils::getInstance()->getCurrentLangName(false);
	            $sInputCaption = BxDolForm::getSubmittedValue('caption-' . $sLanguage, $aForm['form_attrs']['method']);
	
	            $sInputName = $this->getFieldName($sInputObject, $sInputCaption);
	            BxDolForm::setSubmittedValue('name', $sInputName, $oForm->aFormAttrs['method']);
            }

            $this->onSubmitField($oForm);
            if(($iId = $oForm->insert()) === false)
                return false;

            $this->alterAdd($sInputName);
            return true;
        } else
            return BxTemplStudioFunctions::getInstance()->popupBox('adm-form-field-add-' . $this->aParams['display'] . '-popup', _t('_adm_form_txt_field_add_popup'), BxDolStudioTemplate::getInstance()->parseHtmlByName('form_add_field.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $sObject,
                'action' => $sAction
            )));
    }

    protected function getCodeEdit($sAction, $sObject)
    {
        $aForm = $this->getFormEdit($sAction, $sObject);
        $oForm = new BxTemplStudioFormView($aForm);

        if($oForm->isSubmitted() && isset($oForm->aInputs['required']))
            $this->updateCheckerFields($oForm);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
        	$sInputNameOld = $this->aField['name'];
            $sInputNameNew = $oForm->getCleanValue('name');

            $this->onSubmitField($oForm);
            if($oForm->update((int)$this->aField['id']) === false)
                return false;

			if(strcmp($sInputNameOld, $sInputNameNew) != 0)
				$this->alterChange($sInputNameOld, $sInputNameNew);
            return true;
        } else
            return BxTemplStudioFunctions::getInstance()->popupBox('adm-form-field-edit-' . $this->aField['type'] . '-popup', _t('_adm_form_txt_field_edit_popup', _t($this->aField['caption'])), BxDolStudioTemplate::getInstance()->parseHtmlByName('form_add_field.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $sObject,
                'action' => $sAction
            )));
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = $this->aForm;
        $aForm['form_attrs']['id'] = 'adm-form-field-add-' . $this->aParams['display'];
        $aForm['form_attrs']['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $sObject . '&a=' . $sAction . '&object=' . $this->aParams['object'] . '&display=' . $this->aParams['display'];

        if(isset($aForm['inputs']['object']))
            $aForm['inputs']['object']['value'] = $this->aParams['object'];

        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = $this->aForm;
        $aForm['form_attrs']['id'] = 'adm-form-field-edit-' . $this->aParams['display'];
        $aForm['form_attrs']['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $sObject . '&a=' . $sAction . '&object=' . $this->aParams['object'] . '&display=' . $this->aParams['display'] . '&di_id=' . (int)$this->aField['di_id'];

        foreach($aForm['inputs'] as $sKey => $aInput)
            if(!in_array($sKey, array('type_display', 'controls')))
                switch($sKey) {
                    case 'value':
                        $aForm['inputs'][$sKey]['value'] = $this->aField[$sKey];

                        if(in_array($aForm['inputs']['type']['value'], array('select', 'select_multiple', 'radio_set', 'checkbox_set'))) {
                            $sList = trim($this->getFieldValues($this->aField), BX_DATA_LISTS_KEY_PREFIX . ' ');

                            $bUseForSets = false;
                            if(in_array($aForm['inputs']['type']['value'], array('select_multiple', 'checkbox_set'))) {
                                $aList = array();
                                $this->oDb->getLists(array('type' => 'by_key', 'value' => $sList), $aList, false);

                                $bUseForSets = (int)$aList['use_for_sets'] == 1;
                            }

                            $aPreValues = BxDolForm::getDataItems($sList, $bUseForSets);
                            foreach($aPreValues as $mixedValue => $sTitle)
                                 $aForm['inputs'][$sKey]['values'][] = array('key' => $mixedValue, 'value' => $sTitle);
                        }
                        break;
                    case 'values':
                        if(!empty($this->aField[$sKey]))
                            $aForm['inputs'][$sKey]['value'] = $this->getFieldValues($this->aField);

                        if(in_array($aForm['inputs']['type']['value'], array('select', 'select_multiple', 'radio_set', 'checkbox_set'))) {
                            $aForm['inputs'][$sKey]['type'] = 'value';
                            foreach($aForm['inputs'][$sKey]['values'] as $aValue)
                                if($aValue['key'] == $aForm['inputs'][$sKey]['value']) {
                                    $aForm['inputs'][$sKey]['value'] = BxDolStudioTemplate::getInstance()->parseHtmlByName('bx_a.html', array(
                                        'href' => BX_DOL_URL_STUDIO . 'builder_forms.php?page=pre_values&list=' . trim($aValue['key'], BX_DATA_LISTS_KEY_PREFIX . ' '),
                                        'title' =>  _t('_adm_form_txt_field_values_manage'),
                                        'bx_repeat:attrs' => array(),
                                        'content' => $aValue['value']
                                    ));
                                    break;
                                }
                            unset($aForm['inputs'][$sKey]['values'], $aForm['inputs'][$sKey]['db']);
                        }
                        break;
                    case 'checked':
                        $aForm['inputs'][$sKey]['checked'] = (int)$this->aField[$sKey];
                        break;
                    case 'collapsed':
                        $aForm['inputs'][$sKey]['checked'] = (int)$this->aField[$sKey];
                        break;
                    case 'required':
                        $aForm['inputs'][$sKey]['checked'] = (int)$this->aField[$sKey];
                        if($aForm['inputs'][$sKey]['checked'] == 1 && isset($aForm['inputs']['checker_func']))
                            unset($aForm['inputs']['checker_func']['tr_attrs']['style'], $aForm['inputs']['checker_error']['tr_attrs']['style']);
                        break;
                    case 'checker_func':
                        $aForm['inputs'][$sKey]['value'] = strtolower($this->aField[$sKey]);
                        switch($aForm['inputs'][$sKey]['value']) {
                            case 'length':
                                unset($aForm['inputs']['checker_params_length_min']['tr_attrs']['style'], $aForm['inputs']['checker_params_length_max']['tr_attrs']['style']);
                                break;
                            case 'preg':
                                unset($aForm['inputs']['checker_params_preg']['tr_attrs']['style']);
                                break;
                        }
                        break;
                    case 'checker_params_length_min':
                        $aParams = unserialize($this->aField['checker_params']);
                        $aForm['inputs'][$sKey]['value'] = isset($aParams['min']) ? (int)$aParams['min'] : 0;
                        break;
                    case 'checker_params_length_max':
                        $aParams = unserialize($this->aField['checker_params']);
                        $aForm['inputs'][$sKey]['value'] = isset($aParams['max']) ? (int)$aParams['max'] : 0;
                        break;
                    case 'checker_params_preg':
                        $aParams = unserialize($this->aField['checker_params']);
                        $aForm['inputs'][$sKey]['value'] = isset($aParams['preg']) ? $aParams['preg'] : '';
                        break;
                    case 'attrs_min':
                        $aParams = unserialize($this->aField['attrs']);
                        $aForm['inputs'][$sKey]['value'] = isset($aParams['min']) ? (int)$aParams['min'] : 0;
                        break;
                    case 'attrs_max':
                        $aParams = unserialize($this->aField['attrs']);
                        $aForm['inputs'][$sKey]['value'] = isset($aParams['max']) ? (int)$aParams['max'] : 100;
                        break;
                    case 'attrs_step':
                        $aParams = unserialize($this->aField['attrs']);
                        $aForm['inputs'][$sKey]['value'] = isset($aParams['step']) ? (int)$aParams['step'] : 1;
                        break;
                    case 'attrs_src':
                        $aParams = unserialize($this->aField['attrs']);
                        $aForm['inputs'][$sKey]['value'] = isset($aParams['src']) ? $aParams['src'] : '';
                        break;
                    case 'editable':
                        $aForm['inputs'][$sKey]['checked'] = (int)$this->aField[$sKey];
                        break;
					case 'deletable':
                        $aForm['inputs'][$sKey]['checked'] = (int)$this->aField[$sKey];
                        break;
                    default:
                        $aForm['inputs'][$sKey]['value'] = $this->aField[$sKey];
                }

        if(array_key_exists($aForm['inputs']['type']['value'], $this->aTypesRelated)) {
           $aForm['inputs']['type'] = $this->getFieldTypesSelector('type', $aForm['inputs']['type']['value'], true);
           unset($aForm['inputs']['type_display']);
        }

        $aForm['inputs']['controls'][0]['value'] = _t('_adm_form_btn_field_save');

        return $aForm;
    }

    protected function getFieldTypes($sRelatedTo = '')
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTypesList = $sRelatedTo != '' && isset($this->aTypesRelated[$sRelatedTo]) ? $this->aTypesRelated[$sRelatedTo]['types'] : $this->aTypes;

        $aTypes = array();
        foreach($aTypesList as $sType)
            $aTypes[$sType] = _t($this->sTypeTitlePrefix . $sType);

        asort($aTypes);

        $aResult = array();
        foreach($aTypes as $sName => $sTitle)
            $aResult[] = array(
                'key' => $sName,
                'value' => $sTitle,
                'style' => 'background-image:url(' . $oTemplate->getIconUrl('ui-' . $sName . '.png') . ')'
            );

        return $aResult;
    }

    protected function getFieldTypesSelector($sName, $sValue, $bRelated = false)
    {
        $bRelated = $bRelated && $sValue != '' && isset($this->aTypesRelated[$sValue]);

        $aField = array(
            'type' => 'select',
            'name' => $sName,
            'caption' => _t('_adm_form_txt_field_type_display'),
            'info' => '',
            'value' => $sValue,
            'values' => $this->getFieldTypes($bRelated ? $sValue : ''),
            'required' => '',
            'attrs' => array(
                'id' => 'bx-form-field-type',
                'class' => 'bx-form-field-type',
                'disabled' => 'disabled'
            )
        );

        if($bRelated) {
            $aField['db'] = array('pass' => 'Xss');
            if((int)$this->aTypesRelated[$sValue]['reload_on_change'] == 1)
                $aField['attrs']['onchange'] = $this->getJsObject() . '.onChangeType(' . $this->aField['di_id'] . ')';
            unset($aField['attrs']['disabled']);
        }

        return $aField;
    }

    protected function getFieldName($sObject, $mixedCaption)
    {
        if($mixedCaption === false)
            return $this->genFieldName($sObject);

        $sName = get_mb_replace('/([^\d^\w]+)/u', '', $mixedCaption);
        if(empty($sName))
            return $this->genFieldName($sObject);

        $sName = get_mb_replace('/([^\d^\w^\s]+)/u', '', $mixedCaption);
        $sName = $this->getSystemName(trim($sName));
        if($this->oDb->isInput($sObject, $sName))
            $sName = $this->genFieldName($sObject, $sName);

        return $sName;
    }

    protected function getFieldValues($aField)
    {
        $mixedResult = null;

        switch($aField['type']) {
            case 'select':
            case 'select_multiple':
            case 'checkbox_set':
            case 'radio_set':
                $mixedResult = $this->aField['values'];
                break;
            case 'input_set':
                $mixedResult = $this->aField['values'];
                break;
        }

        return $mixedResult;
    }

    protected function getCheckerFields()
    {
        $aResult = array(
            'checker_func' => array(
                'type' => 'select',
                'name' => 'checker_func',
                'caption' => _t('_adm_form_txt_field_checker_func'),
                'info' => '',
                'value' => '',
                'values' => array(
                    array('key' => '', 'value' => _t('_adm_form_txt_field_checker_empty'))
                ),
                'required' => '1',
                'attrs' => array(
                    'id' => 'bx-form-field-type',
                    'onchange' => $this->getJsObject() . ".onSelectChecker(this)"
                ),
                'tr_attrs' => array(
                    'style' => 'display:none'
                ),
                'db' => array (
                    'pass' => 'Xss',
                ),
                'checker' => array (
                    'func' => 'avail',
                    'params' => array(),
                    'error' => _t('_adm_form_err_field_checker_func'),
                ),
            ),
            'checker_params' => array(
                'type' => 'hidden',
                'name' => 'checker_params',
                'value' => '',
                'db' => array (
                    'pass' => 'Xss',
                ),
            ),
            'checker_params_length_min' => array(
                'type' => 'text',
                'name' => 'checker_params_length_min',
                'caption' => _t('_adm_form_txt_field_checker_params_length_min'),
                'info' => '',
                'value' => '',
                'required' => '1',
                'tr_attrs' => array(
                    'style' => 'display:none'
                ),
                'db' => array (
                    'pass' => 'Int',
                ),
                'checker' => array (
                    'func' => 'preg',
                    'params' => array('/^[0-9]+$/'),
                    'error' => _t('_adm_form_err_field_checker_params_length_min'),
                ),
            ),
            'checker_params_length_max' => array(
                'type' => 'text',
                'name' => 'checker_params_length_max',
                'caption' => _t('_adm_form_txt_field_checker_params_length_max'),
                'info' => '',
                'value' => '',
                'required' => '1',
                'tr_attrs' => array(
                    'style' => 'display:none'
                ),
                'db' => array (
                    'pass' => 'Int',
                ),
                'checker' => array (
                    'func' => 'preg',
                    'params' => array('/^[0-9]+$/'),
                    'error' => _t('_adm_form_err_field_checker_params_length_max'),
                ),
            ),
            'checker_params_preg' => array(
                'type' => 'text',
                'name' => 'checker_params_preg',
                'caption' => _t('_adm_form_txt_field_checker_params_preg'),
                'info' => _t('_adm_form_dsc_field_checker_params_preg'),
                'value' => '',
                'required' => '1',
                'tr_attrs' => array(
                    'style' => 'display:none'
                ),
                'db' => array (
                    'pass' => 'Xss',
                ),
                'checker' => array (
                    'func' => 'avail',
                    'params' => array(),
                    'error' => _t('_adm_form_err_field_checker_params_preg'),
                ),
            ),
            'checker_error' => array(
                'type' => 'text_translatable',
                'name' => 'checker_error',
                'caption' => _t('_adm_form_txt_field_checker_error'),
                'info' => '',
                'value' => '_sys_form_txt_field_checker_error',
                'required' => '1',
                'tr_attrs' => array(
                    'style' => 'display:none'
                ),
                'db' => array (
                    'pass' => 'Xss',
                ),
                'checker' => array (
                    'func' => 'avail',
                    'params' => array(),
                    'error' => _t('_adm_form_err_field_checker_error'),
                ),
            ),
        );

        foreach($this->aCheckFunctions as $sCheckFunction)
            $aResult['checker_func']['values'][] = array('key' => $sCheckFunction, 'value' => _t('_adm_form_txt_field_checker_' . $sCheckFunction));

        return $aResult;
    }

    protected function updateCheckerFields(&$oForm)
    {
            if((int)$oForm->getCleanValue('required') == 0)
                $this->unsetCheckerFields($oForm);
            else {
                unset($oForm->aInputs['checker_func']['tr_attrs']['style'], $oForm->aInputs['checker_error']['tr_attrs']['style']);
                switch($oForm->getCleanValue('checker_func')) {
                    case 'length':
                        unset($oForm->aInputs['checker_params_length_min']['tr_attrs']['style'], $oForm->aInputs['checker_params_length_max']['tr_attrs']['style']);
                        $this->unsetCheckerFields($oForm, 'preg');
                        break;
                    case 'preg':
                        unset($oForm->aInputs['checker_params_preg']['tr_attrs']['style']);
                        $this->unsetCheckerFields($oForm, 'length');
                        break;
                    default:
                        $this->unsetCheckerFields($oForm, 'params');
                }
            }
    }

    protected function unsetCheckerFields(&$oForm, $sCheckerFunc = 'all')
    {
        switch($sCheckerFunc) {
            case 'length':
                unset(
                    $oForm->aInputs['checker_params_length_min'],
                    $oForm->aInputs['checker_params_length_max']
                );
                break;
            case 'preg':
                unset($oForm->aInputs['checker_params_preg']);
                break;
            case 'params':
                unset(
                    $oForm->aInputs['checker_params_length_min'],
                    $oForm->aInputs['checker_params_length_max'],
                    $oForm->aInputs['checker_params_preg']
                );
                break;
            case 'all':
                unset(
                    $oForm->aInputs['checker_func'],
                    $oForm->aInputs['checker_params_length_min'],
                    $oForm->aInputs['checker_params_length_max'],
                    $oForm->aInputs['checker_params_preg'],
                    $oForm->aInputs['checker_error']
                );
        }
    }

    protected function genFieldName($sObject, $sPrefix = 'field')
    {
        $aFields = array();
        $this->oDb->getInputs(array('type' => 'by_object_name_filter', 'object' => $sObject, 'name_filter' => $sPrefix . '%'), $aFields, false);

        for($iIndex = 1; true; $iIndex++)
            if(!in_array($sPrefix . $iIndex, $aFields))
                break;

        return $sPrefix . $iIndex;
    }

    protected function onSubmitField(&$oForm)
    {
        //--- Process field values.
        if(isset($oForm->aInputs['values']['db'])) {
            $sValues = $oForm->getCleanValue('values');
            if(is_string($sValues) && strpos($sValues, BX_DATA_LISTS_KEY_PREFIX) === false)
                BxDolForm::setSubmittedValue('values', serialize(explode("\n", $sValues)), $oForm->aFormAttrs['method']);
        }

        //--- Process field 'html' flag.
        if(isset($oForm->aInputs['html'])) {
            $iHtml = (int)$oForm->getCleanValue('html');
            BxDolForm::setSubmittedValue('db_pass', $iHtml == 0 ? 'Xss' : 'XssHtml', $oForm->aFormAttrs['method']);
        }

        //--- Process field checker.
        $aCheckerParams = array();
        if(isset($oForm->aInputs['checker_params_length_min'], $oForm->aInputs['checker_params_length_max'])) {
            $aCheckerParams['min'] = $oForm->getCleanValue('checker_params_length_min');
            $aCheckerParams['max'] = $oForm->getCleanValue('checker_params_length_max');
        }
        if(isset($oForm->aInputs['checker_params_preg']))
            $aCheckerParams['preg'] = $oForm->getCleanValue('checker_params_preg');

        unset($oForm->aInputs['checker_params_length_min'], $oForm->aInputs['checker_params_length_max'], $oForm->aInputs['checker_params_preg']);
        BxDolForm::setSubmittedValue('checker_params', !empty($aCheckerParams) ? serialize($aCheckerParams) : '', $oForm->aFormAttrs['method']);

        //--- Process field attrs.
        $aAttrs = array();
        if(isset($oForm->aInputs['attrs_min'], $oForm->aInputs['attrs_max'], $oForm->aInputs['attrs_step'])) {
            $aAttrs['min'] = $oForm->getCleanValue('attrs_min');
            $aAttrs['max'] = $oForm->getCleanValue('attrs_max');
            $aAttrs['step'] = $oForm->getCleanValue('attrs_step');
        } else if(isset($oForm->aInputs['attrs_src']))
            $aAttrs['src'] = $oForm->getCleanValue('attrs_src');

        unset($oForm->aInputs['attrs_min'], $oForm->aInputs['attrs_max'], $oForm->aInputs['attrs_step'], $oForm->aInputs['attrs_src']);
        BxDolForm::setSubmittedValue('attrs', serialize($aAttrs), $oForm->aFormAttrs['method']);
    }
}

class BxBaseStudioFormsFieldBlockHeader extends BxBaseStudioFormsField
{
    protected $sType = 'block_header';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aForm = array(
            'form_attrs' => array(
                'id' => '',
                'action' => '',
                'method' => 'post'
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_form_inputs',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                'module' => array(
                    'type' => 'hidden',
                    'name' => 'module',
                    'caption' => _t('_adm_form_txt_field_module'),
                    'value' => 'custom',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'object' => array(
                    'type' => 'hidden',
                    'name' => 'object',
                    'caption' => _t('_adm_form_txt_field_object'),
                    'value' => '',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'name' => array(
                    'type' => 'hidden',
                    'name' => 'name',
                	'caption' => _t('_adm_form_txt_field_name'),
                    'value' => '',
                    'db' => array (
                        'pass' => 'Xss',
                    )
                ),
                'type' => array(
                    'type' => 'hidden',
                    'name' => 'type',
                    'value' => $this->sType,
                    'db' => array (
                        'pass' => 'Xss',
                    )
                ),
                'db_pass' => array(
                    'type' => 'hidden',
                    'name' => 'db_pass',
                    'value' => $this->sDbPass,
                    'db' => array (
                        'pass' => 'Xss',
                    )
                ),
                'type_display' => $this->getFieldTypesSelector('type_display', $this->sType),
                'caption_system' => array(
                    'type' => 'text_translatable',
                    'name' => 'caption_system',
                    'caption' => _t('_adm_form_txt_field_caption_system'),
                    'info' => _t('_adm_form_dsc_field_caption_system'),
                    'value' => '_sys_form_txt_field',
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'LengthTranslatable',
                        'params' => array(3,100, 'caption_system'),
                        'error' => _t('_adm_form_err_field_caption_system'),
                    ),
                ),
                'caption' => array(
                    'type' => 'text_translatable',
                    'name' => 'caption',
                    'caption' => _t('_adm_form_txt_field_caption'),
                    'info' => _t('_adm_form_dsc_field_caption_block_header'),
                    'value' => '_sys_form_txt_field',
                    'required' => '',
                    'db' => array (
                        'pass' => 'Xss',
                    )
                ),
                'collapsed' => array(
                    'type' => 'checkbox',
                    'name' => 'collapsed',
                    'caption' => _t('_adm_form_txt_field_collapsed'),
                    'info' => _t('_adm_form_dsc_field_collapsed'),
                    'value' => '1',
                    'required' => '0',
                    'db' => array (
                        'pass' => 'Int',
                    )
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_form_btn_field_add'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_form_btn_field_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );
    }
}

class BxBaseStudioFormsFieldValue extends BxBaseStudioFormsFieldBlockHeader
{
    protected $sType = 'value';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aForm['inputs']['caption']['info'] = _t('_adm_form_dsc_field_caption');

        $aFields = array(
            'value' => array(
                'type' => 'text',
                'name' => 'value',
                'caption' => _t('_adm_form_txt_field_value_custom_text'),
                'info' => '',
                'value' => '',
                'required' => '0',
                'db' => array (
                    'pass' => 'Xss',
                )
            ),
        );
        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'caption', $aFields);

        unset(
            $this->aForm['inputs']['collapsed']
        );
    }
}

class BxBaseStudioFormsFieldText extends BxBaseStudioFormsFieldBlockHeader
{
    protected $sType = 'text';
    protected $aCheckFunctions = array('avail', 'length', 'preg', 'email');
    protected $sDbPass = 'Xss';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aParams['table_alter'] = true;
        $this->aParams['table_field_type'] = 'varchar(255)';

        $this->aForm['inputs']['caption']['info'] = _t('_adm_form_dsc_field_caption');

        $aFields = array(
            'value' => array(
                'type' => 'text',
                'name' => 'value',
                'caption' => _t('_adm_form_txt_field_value_default'),
                'info' => '',
                'value' => '',
                'required' => '0',
                'db' => array (
                    'pass' => 'Xss',
                )
            ),
            'info' => array(
                'type' => 'textarea_translatable',
                'name' => 'info',
                'caption' => _t('_adm_form_txt_field_info'),
                'info' => _t('_adm_form_dsc_field_info'),
                'value' => '_sys_form_txt_field',
                'required' => '0',
                'db' => array (
                    'pass' => 'Xss',
                )
            ),
            'required' => array(
                'type' => 'switcher',
                'name' => 'required',
                'caption' => _t('_adm_form_txt_field_required'),
                'info' => _t('_adm_form_dsc_field_required'),
                'value' => '1',
                'required' => '0',
                'attrs' => array(
                    'id' => 'bx-form-field-required',
                    'onchange' => $this->getJsObject() . ".onCheckRequired(this)"
                ),
                'db' => array (
                    'pass' => 'Int',
                )
            )
        );

        $aFields = array_merge($aFields, $this->getCheckerFields());

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'caption', $aFields);
        unset($this->aForm['inputs']['collapsed']);
    }
}

class BxBaseStudioFormsFieldPassword extends BxBaseStudioFormsFieldText
{
    protected $sType = 'password';
    protected $aCheckFunctions = array('avail', 'length', 'preg');

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);
    }
}

class BxBaseStudioFormsFieldTextarea extends BxBaseStudioFormsFieldText
{
    protected $sType = 'textarea';
    protected $aCheckFunctions = array('avail', 'length', 'preg');

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aParams['table_field_type'] = 'text';

        $this->aForm['inputs']['value']['type'] = $this->sType;

        $aFields = array(
            'html' => array(
                'type' => 'select',
                'name' => 'html',
                'caption' => _t('_adm_form_txt_field_html'),
                'info' => _t('_adm_form_dsc_field_html'),
                'value' => '0',
                'values' => array(
                    array('key' => '0', 'value' => _t('_adm_form_txt_field_html_none')),
                    array('key' => '1', 'value' => _t('_adm_form_txt_field_html_standard')),
                    array('key' => '2', 'value' => _t('_adm_form_txt_field_html_full')),
                    array('key' => '3', 'value' => _t('_adm_form_txt_field_html_mini')),
                ),
                'required' => '0',
                'db' => array (
                    'pass' => 'Int',
                )
            ),
        );

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'info', $aFields);
    }
}

class BxBaseStudioFormsFieldDatepicker extends BxBaseStudioFormsFieldText
{
    protected $sType = 'datepicker';
    protected $aCheckFunctions = array('date');
    protected $sDbPass = 'Date';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aParams['table_field_type'] = 'int(11)';

        $this->aForm['inputs']['value']['type'] = $this->sType;
        $this->aForm['inputs']['value']['db']['pass'] = 'Date';
    }
}

class BxBaseStudioFormsFieldDatetime extends BxBaseStudioFormsFieldDatepicker
{
    protected $sType = 'datetime';
    protected $aCheckFunctions = array('date_time');
    protected $sDbPass = 'DateTime';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aForm['inputs']['value']['db']['pass'] = 'DateTime';
    }
}

class BxBaseStudioFormsFieldCheckbox extends BxBaseStudioFormsFieldText
{
    protected $sType = 'checkbox';
    protected $aCheckFunctions = array('avail', 'length', 'preg');

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aForm['inputs']['value']['type'] = 'hidden';
        $this->aForm['inputs']['value']['caption'] = _t('_adm_form_txt_field_value_checkbox');
        $this->aForm['inputs']['value']['value'] = '1';
        $this->aForm['inputs']['value']['info'] = _t('_adm_form_dsc_field_value_checkbox');

        $aFields = array(
            'checked' => array(
                'type' => 'checkbox',
                'name' => 'checked',
                'caption' => _t('_adm_form_txt_field_checked'),
                'value' => '1',
                'required' => '0',
                'db' => array (
                    'pass' => 'Int',
                )
            )
        );
        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'value', $aFields);
    }
}

class BxBaseStudioFormsFieldSwitcher extends BxBaseStudioFormsFieldCheckbox
{
    protected $sType = 'switcher';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aForm['inputs']['checked']['caption'] = _t('_adm_form_txt_field_checked_switcher');
    }
}

class BxBaseStudioFormsFieldFile extends BxBaseStudioFormsFieldText
{
    protected $sType = 'file';
    protected $aCheckFunctions = array('avail', 'length', 'preg');
    protected $sDbPass = '';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        unset($this->aForm['inputs']['value']);
    }
}

class BxBaseStudioFormsFieldFiles extends BxBaseStudioFormsFieldFile
{
    protected $sType = 'files';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);
    }
}

class BxBaseStudioFormsFieldNumber extends BxBaseStudioFormsFieldText
{
    protected $sType = 'number';
    protected $aCheckFunctions = array('avail', 'length', 'preg');
    protected $sDbPass = 'Int';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aParams['table_field_type'] = 'int(11)';

        $this->aForm['inputs']['value']['db']['pass'] = 'Int';
        $this->aForm['inputs']['value']['checker'] = array (
            'func' => 'preg',
            'params' => array('/^\d*?$/'),
            'error' => _t('_adm_form_err_field_value_number'),
        );
    }
}

class BxBaseStudioFormsFieldSlider extends BxBaseStudioFormsFieldNumber
{
    protected $sType = 'slider';
    protected $aCheckFunctions = array('avail', 'length');

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $aFields = array(
            'attrs' => array(
                'type' => 'hidden',
                'name' => 'attrs',
                'value' => '',
                'db' => array (
                    'pass' => 'Xss',
                ),
            ),
            'attrs_min' => array(
                'type' => 'text',
                'name' => 'attrs_min',
                'caption' => _t('_adm_form_txt_field_attrs_min'),
                'info' => _t('_adm_form_dsc_field_attrs_min'),
                'value' => '1',
                'required' => '0',
                'db' => array (
                    'pass' => 'Int',
                )
            ),
            'attrs_max' => array(
                'type' => 'text',
                'name' => 'attrs_max',
                'caption' => _t('_adm_form_txt_field_attrs_max'),
                'info' => _t('_adm_form_dsc_field_attrs_max'),
                'value' => '100',
                'required' => '0',
                'db' => array (
                    'pass' => 'Int',
                )
            ),
            'attrs_step' => array(
                'type' => 'text',
                'name' => 'attrs_step',
                'caption' => _t('_adm_form_txt_field_attrs_step'),
                'info' => _t('_adm_form_dsc_field_attrs_step'),
                'value' => '1',
                'required' => '0',
                'db' => array (
                    'pass' => 'Int',
                )
            ),
        );
        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'value', $aFields);
    }
}

class BxBaseStudioFormsFieldDoublerange extends BxBaseStudioFormsFieldSlider
{
    protected $sType = 'doublerange';
    protected $aCheckFunctions = array('avail', 'length');
    protected $sDbPass = 'Xss';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aParams['table_field_type'] = 'varchar(255)';

        $this->aForm['inputs']['value']['db']['pass'] = 'Xss';
        $this->aForm['inputs']['value']['checker'] = array (
            'func' => 'preg',
            'params' => array('/^(\d+-\d+)?$/'),
            'error' => _t('_adm_form_err_field_value_doublerange'),
        );
    }
}

class BxBaseStudioFormsFieldHidden extends BxBaseStudioFormsFieldText
{
    protected $sType = 'hidden';
    protected $aCheckFunctions = array('avail', 'length', 'preg', 'date', 'date_time', 'email');
    protected $sDbPass = '';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        unset(
            $this->aForm['inputs']['caption'],
            $this->aForm['inputs']['info'],
            $this->aForm['inputs']['required'],
            $this->aForm['inputs']['checker_func'],
            $this->aForm['inputs']['checker_params'],
            $this->aForm['inputs']['checker_params_length_min'],
            $this->aForm['inputs']['checker_params_length_max'],
            $this->aForm['inputs']['checker_params_preg'],
            $this->aForm['inputs']['checker_error']
        );
    }
}

class BxBaseStudioFormsFieldButton extends BxBaseStudioFormsFieldText
{
    protected $sType = 'button';
    protected $sDbPass = '';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aParams['table_alter'] = false;

        $this->aForm['inputs']['value']['type'] = 'text_translatable';
        $this->aForm['inputs']['value']['caption'] = _t('_adm_form_txt_field_value_button');
        $this->aForm['inputs']['value']['info'] = _t('_adm_form_dsc_field_value_button');
        $this->aForm['inputs']['value']['value'] = '_sys_form_txt_field';

        unset(
            $this->aForm['inputs']['caption'],
            $this->aForm['inputs']['info'],
            $this->aForm['inputs']['required'],
            $this->aForm['inputs']['checker_func'],
            $this->aForm['inputs']['checker_params'],
            $this->aForm['inputs']['checker_params_length_min'],
            $this->aForm['inputs']['checker_params_length_max'],
            $this->aForm['inputs']['checker_params_preg'],
            $this->aForm['inputs']['checker_error']
        );
    }
}

class BxBaseStudioFormsFieldReset extends BxBaseStudioFormsFieldButton
{
    protected $sType = 'reset';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aForm['inputs']['value']['info'] = _t('_adm_form_dsc_field_value_reset');
    }
}

class BxBaseStudioFormsFieldSubmit extends BxBaseStudioFormsFieldButton
{
    protected $sType = 'submit';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aForm['inputs']['value']['info'] = _t('_adm_form_dsc_field_value_submit');
    }
}

class BxBaseStudioFormsFieldImage extends BxBaseStudioFormsFieldButton
{
    protected $sType = 'image';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $aFields = array(
            'attrs' => array(
                'type' => 'hidden',
                'name' => 'attrs',
                'value' => '',
                'db' => array (
                    'pass' => 'Xss',
                ),
            ),
            'attrs_src' => array(
                'type' => 'text',
                'name' => 'attrs_src',
                'caption' => _t('_adm_form_txt_field_attrs_src'),
                'info' => _t('_adm_form_dsc_field_attrs_src'),
                'value' => '',
                'required' => '0',
                'db' => array (
                    'pass' => 'Xss',
                )
            ),
        );
        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'value', $aFields);

        unset(
            $this->aForm['inputs']['value']
        );
    }
}

class BxBaseStudioFormsFieldSelect extends BxBaseStudioFormsFieldText
{
    protected $sType = 'select';
    protected $aCheckFunctions = array('avail', 'length', 'preg');

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aForm['inputs']['value']['type'] = 'select';
        $this->aForm['inputs']['value']['values'] = array(
            array('key' => '', 'value' => _t('_adm_form_txt_field_value_select_value'))
        );
        $this->aForm['inputs']['value']['attrs'] = array(
            'id' => 'adm-form-field-add-value'
        );

        $aFields = array(
            'values' => array(
                'type' => 'select',
                'name' => 'values',
                'caption' => _t('_adm_form_txt_field_values'),
                'info' => '',
                'value' => '',
                'values' => array(
                    array('key' => '', 'value' => _t('_adm_form_txt_field_values_select_list'))
                ),
                'required' => '1',
                'attrs' => array(
                    'onChange' => $this->getJsObject() . ".onChangeValues(0, this)"
                ),
                'db' => array (
                    'pass' => 'Xss',
                ),
                'checker' => array (
                    'func' => 'avail',
                    'params' => array(),
                    'error' => _t('_adm_form_err_field_values'),
                ),
            )
        );

        $aLists = array();
        $this->oDb->getLists(array('type' => 'all'), $aLists, false);
        foreach($aLists as $aList)
            $aFields['values']['values'][] = array('key' => BX_DATA_LISTS_KEY_PREFIX . $aList['key'], 'value' => _t($aList['title']));

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'caption', $aFields);
    }
}

class BxBaseStudioFormsFieldRadioSet extends BxBaseStudioFormsFieldSelect
{
    protected $sType = 'radio_set';
    protected $aCheckFunctions = array('avail', 'length', 'preg');

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);
    }
}

class BxBaseStudioFormsFieldSelectMultiple extends BxBaseStudioFormsFieldSelect
{
    protected $sType = 'select_multiple';
    protected $aCheckFunctions = array('avail', 'length', 'preg');
    protected $sDbPass = 'Set';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aParams['table_field_type'] = 'int(11)';

        $this->aForm['inputs']['value']['type'] = 'select_multiple';
        $this->aForm['inputs']['value']['values'] = array();
        $this->aForm['inputs']['value']['db']['pass'] = 'Set';
        $this->aForm['inputs']['values']['values'] = array(
            array('key' => '', 'value' => _t('_adm_form_txt_field_values_select_list'))
        );
        $this->aForm['inputs']['values']['attrs']['onChange'] = $this->getJsObject() . ".onChangeValues(1, this)";

        $aLists = array();
        $this->oDb->getLists(array('type' => 'all_for_sets'), $aLists, false);
        foreach($aLists as $aList)
            $this->aForm['inputs']['values']['values'][] = array('key' => BX_DATA_LISTS_KEY_PREFIX . $aList['key'], 'value' => _t($aList['title']));
    }
}

class BxBaseStudioFormsFieldCheckboxSet extends BxBaseStudioFormsFieldSelectMultiple
{
    protected $sType = 'checkbox_set';
    protected $aCheckFunctions = array('avail', 'length', 'preg');

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);
    }
}

class BxBaseStudioFormsFieldCustom extends BxBaseStudioFormsFieldText
{
    protected $sType = 'custom';
    protected $sDbPass = '';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        unset(
            $this->aForm['inputs']['required'],
            $this->aForm['inputs']['checker_func'],
            $this->aForm['inputs']['checker_params'],
            $this->aForm['inputs']['checker_params_length_min'],
            $this->aForm['inputs']['checker_params_length_max'],
            $this->aForm['inputs']['checker_params_preg'],
            $this->aForm['inputs']['checker_error']
        );
    }
}

class BxBaseStudioFormsFieldInputSet extends BxBaseStudioFormsFieldCustom
{
    protected $sType = 'input_set';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aParams['table_alter'] = false;

        $aFields = array(
            'values' => array(
                'type' => 'value',
                'name' => 'values',
                'caption' => _t('_adm_form_txt_field_values'),
                'value' => ''
            )
        );
        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'value', $aFields);

        unset(
            $this->aForm['inputs']['value']
        );
    }
}

class BxBaseStudioFormsFieldCaptcha extends BxBaseStudioFormsFieldText
{
    protected $sType = 'captcha';
    protected $aCheckFunctions = array('captcha');
    protected $sDbPass = '';

    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aParams['table_alter'] = false;

        $this->aForm['inputs']['required'] = array(
            'type' => 'hidden',
            'name' => 'required',
            'value' => '1',
            'db' => array (
                'pass' => 'Int',
            )
        );

        $this->aForm['inputs']['checker_func']['type'] = 'hidden';
        $this->aForm['inputs']['checker_func']['value'] = 'captcha';
        $this->aForm['inputs']['checker_error']['info'] = _t('_adm_form_dsc_field_checker_error_captcha');

        unset(
            $this->aForm['inputs']['value'],
            $this->aForm['inputs']['checker_func']['tr_attrs']['style'],
            $this->aForm['inputs']['checker_error']['tr_attrs']['style']
        );
    }
}

/** @} */
