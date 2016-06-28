<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioFormsForms extends BxDolStudioFormsForms
{
    protected $sUrlViewDisplays;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_aOptions['actions_single']['edit']['attr']['title'] = _t('_adm_form_btn_forms_edit');

        $this->sUrlViewDisplays = BX_DOL_URL_STUDIO . 'builder_forms.php?page=displays&module=%s&object=%s';
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

        $aFormData = array();
        $iFormData = $this->oDb->getForms(array('type' => 'by_id', 'value' => $iId), $aFormData);
        if($iFormData != 1 || empty($aFormData)){
            echoJson(array());
            exit;
        }

        bx_import('BxTemplStudioFormView');

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-form-form-edit',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction,
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_objects_form',
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
                'title' => array(
                    'type' => 'text_translatable',
                    'name' => 'title',
                    'caption' => _t('_adm_form_txt_forms_title'),
                    'info' => _t('_adm_form_dsc_forms_title'),
                    'value' => $aFormData['title'],
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'LengthTranslatable',
                        'params' => array(3,100, 'title'),
                        'error' => _t('_adm_form_err_forms_title'),
                    ),
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_form_btn_forms_save'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_form_btn_forms_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_adm_form_err_forms_edit'));

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-form-forms-edit-popup', _t('_adm_form_txt_forms_edit_popup', _t($aFormData['title'])), $this->_oTemplate->parseHtmlByName('form_add_form.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }

    function getJsObject()
    {
        return 'oBxDolStudioFormsForms';
    }

    function getCode($isDisplayHeader = true)
    {
        return $this->_oTemplate->parseHtmlByName('forms_forms.html', array(
            'content' => parent::getCode($isDisplayHeader),
            'js_object' => $this->getJsObject(),
            'grid_object' => $this->_sObject,
            'params_divider' => $this->sParamsDivider
        ));
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oTemplate->addJs(array('jquery.form.min.js', 'forms_forms.js'));

        $oForm = new BxTemplStudioFormView(array());
        $oForm->addCssJs();
    }

    protected function _getCellModule($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_limitMaxLength($this->getModuleTitle($aRow['module']), $sKey, $aField, $aRow, $this->_isDisplayPopupOnTextOverflow);
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellDisplays ($mixedValue, $sKey, $aField, $aRow)
    {
        $aDisplayes = array();
        $this->oDb->getDisplays(array('type' => 'by_object', 'value' => $aRow['object']), $aDisplayes, false);

        $mixedValue = $this->_oTemplate->parseHtmlByName('bx_a.html', array(
            'href' => sprintf($this->sUrlViewDisplays, $aRow['module'], $aRow['object']),
            'title' => _t('_adm_form_txt_forms_manage_displays'),
            'bx_repeat:attrs' => array(),
            'content' => _t('_adm_form_txt_forms_n_displays', count($aDisplayes))
        ));

        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getFilterControls ()
    {
        parent::_getFilterControls();

        $sContent = "";

        $oForm = new BxTemplStudioFormView(array());

        $aInputModules = array(
            'type' => 'select',
            'name' => 'module',
            'attrs' => array(
                'id' => 'bx-grid-module-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->getJsObject() . '.onChangeFilter()'
            ),
            'value' => '',
            'values' => $this->getModules(false)
        );

        $aCounter = array();
        $this->oDb->getForms(array('type' => 'counter_by_modules'), $aCounter, false);
        foreach($aInputModules['values'] as $sKey => $sValue)
            $aInputModules['values'][$sKey] = $aInputModules['values'][$sKey] . " (" . (isset($aCounter[$sKey]) ? $aCounter[$sKey] : "0") . ")";

        $aInputModules['values'] = array_merge(array('' => _t('_adm_form_txt_all_modules')), $aInputModules['values']);

        $sContent .= $oForm->genRow($aInputModules);

        $aInputSearch = array(
            'type' => 'text',
            'name' => 'keyword',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject,
                'onKeyup' => 'javascript:$(this).off(\'keyup\'); ' . $this->getJsObject() . '.onChangeFilter()'
            )
        );
        $sContent .= $oForm->genRow($aInputSearch);

        return  $sContent;
    }
}

/** @} */
