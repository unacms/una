<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioFormsSearchFields extends BxDolStudioFormsSearchFields
{
    protected $sUrlPage;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_aOptions['actions_single']['edit']['attr']['title'] = _t('_adm_form_btn_search_forms_fields_edit');

        $this->sUrlPage = BX_DOL_URL_STUDIO . 'builder_forms.php?page=search_fields';
    }

    public function performActionEdit()
    {
        $sAction = 'edit';

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) {
                echoJson(array());
                exit;
            }

            $aIds = array($iId);
        }

        $iId = $aIds[0];

        $aField = array();
        $this->oDb->getSearchFields(array('type' => 'by_id', 'id' => (int)$iId), $aField, false);
        if(empty($aField) || !is_array($aField)) {
            echoJson(array());
            exit;
        }

        bx_import('BxTemplStudioFormView');

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-form-search-field-edit',
                'action' => bx_append_url_params(BX_DOL_URL_ROOT . 'grid.php', array(
                	'o' => $this->_sObject, 
                	'a' => $sAction,
                    'module' => $this->sModule,
                    'form' => $this->sForm
                )),
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_search_extended_fields',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                'id' => array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'value' => $iId,
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'caption' => array(
                    'type' => 'text_translatable',
                    'name' => 'caption',
                    'caption' => _t('_adm_form_txt_search_forms_fields_caption'),
                    'value' => $aField['caption'],
                    'required' => '0',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'info' => array(
                    'type' => 'text_translatable',
                    'name' => 'info',
                    'caption' => _t('_adm_form_txt_search_forms_fields_info'),
                    'value' => $aField['info'],
                    'required' => '0',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_form_btn_search_forms_save'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_form_btn_search_forms_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );

        $aSearchTypes = BxDolSearchExtended::$TYPE_TO_TYPE_SEARCH[$aField['type']];
        if(is_array($aSearchTypes) && count($aSearchTypes) >= 2) {
            $aForm['inputs'] = bx_array_insert_before(array(
            	'search_type' => array(
                    'type' => 'select',
                    'name' => 'search_type',
                    'caption' => _t('_adm_form_txt_search_forms_fields_search_type'),
                    'info' => _t('_adm_form_dsc_search_forms_fields_search_type'),
                    'values' => array(),
                    'value' => $aField['search_type'],
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'Avail',
                        'params' => array(),
                        'error' => _t('_adm_form_err_search_forms_essential'),
                    ),
                )), $aForm['inputs'], 'controls');

            foreach($aSearchTypes as $sType)
                $aForm['inputs']['search_type']['values'][] = array('key' => $sType, 'value' => _t('_adm_form_txt_field_type_' . $sType));
        }

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_adm_form_err_search_forms_fields_edit'));

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-form-search-forms-fields-edit-popup', _t('_adm_form_txt_search_forms_fields_edit_popup', _t($aField['caption'])), $this->_oTemplate->parseHtmlByName('form_add_search_field.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }

    public function performActionReset()
    {
        $mixedResult = BxDolSearchExtended::getObjectInstance($this->sForm)->resetFields();
        if($mixedResult === false)
            return echoJson(array('msg' => _t('_adm_from_err_search_forms_fields_reset')));

        echoJson(array('grid' => $this->getCode(false)));
    }

    function getJsObject()
    {
        return 'oBxDolStudioFormsSearchFields';
    }

    function getFormsSelector($sModule = '')
    {
        $oForm = new BxTemplStudioFormView(array());

        $aInputForms = array(
            'type' => 'select',
            'name' => 'form',
            'attrs' => array(
                'id' => 'bx-grid-form-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->getJsObject() . '.onChangeForm()'
            ),
            'value' => $this->sForm,
            'values' => array(
                '' => _t('_adm_form_txt_search_forms_fields_select_object'),
            )
        );

        if(!empty($sModule)) {
            $aForms = array();
            $this->oDb->getSearchForms(array('type' => 'by_module', 'module' => $sModule), $aForms, false);

            foreach($aForms as $aForm)
                 $aInputForms['values'][] = array(
                 	'key' => $aForm['object'], 
                 	'value' => _t($aForm['title'])
                 );
        }
        else
            $aInputForms['attrs']['disabled'] = 'disabled';

        return $oForm->genRow($aInputForms);
    }

    function getCode($isDisplayHeader = true)
    {
        return $this->_oTemplate->parseHtmlByName('forms_search_fields.html', array(
            'content' => parent::getCode($isDisplayHeader),
            'js_object' => $this->getJsObject(),
            'page_url' => $this->sUrlPage,
            'grid_object' => $this->_sObject,
            'params_divider' => $this->sParamsDivider
        ));
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oTemplate->addCss(array('menu.css'));
        $this->_oTemplate->addJs(array('jquery.form.min.js', 'forms_search_fields.js'));

        $oForm = new BxTemplStudioFormView(array());
        $oForm->addCssJs();
    }

    protected function _getCellSwitcher ($mixedValue, $sKey, $aField, $aRow)
    {
        if(!$this->_isEditable($aRow))
            return parent::_getCellDefault('', $sKey, $aField, $aRow);

        return parent::_getCellSwitcher($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellType ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oTemplate->getIcon('ui-' . $aRow['type'] . '.png', array('alt' => _t('_adm_form_txt_field_type_' . $aRow['type'])));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellSearchType ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oTemplate->getIcon('ui-' . $aRow['search_type'] . '.png', array('alt' => _t('_adm_form_txt_field_type_' . $aRow['search_type'])));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionReset ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_isResetable($aRow))
            return '';

        if($this->sForm == '')
            $isDisabled = true;

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getFilterControls ()
    {
        parent::_getFilterControls();

        $sContent = $this->getModulesSelectOne('getSearchForms') . $this->getFormsSelector($this->sModule);

        $oForm = new BxTemplStudioFormView(array());

        $aInputSearch = array(
            'type' => 'text',
            'name' => 'keyword',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject,
            ),
            'tr_attrs' => array(
                'style' => empty($this->sModule) || empty($this->sForm) ? 'display:none;' : ''
            )
        );
        $sContent .= $oForm->genRow($aInputSearch);

        return  $sContent;
    }

    protected function _isEditable(&$aRow)
    {
    	return true;
    }

    protected function _isResetable(&$aRow)
    {
    	return true;
    }
}

/** @} */
