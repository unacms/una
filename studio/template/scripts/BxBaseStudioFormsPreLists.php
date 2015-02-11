<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioFormsPreLists extends BxDolStudioFormsPreLists
{
    protected $sUrlViewValues;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_aOptions['actions_single']['edit']['attr']['title'] = _t('_adm_form_btn_pre_lists_edit');
        $this->_aOptions['actions_single']['delete']['attr']['title'] = _t('_adm_form_btn_pre_lists_delete');

        $this->sUrlViewValues = BX_DOL_URL_STUDIO . 'builder_forms.php?page=pre_values&module=%s&list=%s';
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-form-pre-list-create',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction,
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_form_pre_lists',
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
                    'value' => BX_DOL_STUDIO_MODULE_CUSTOM,
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'use_for_sets' => array(
                    'type' => 'hidden',
                    'name' => 'use_for_sets',
                    'value' => 1,
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'title' => array(
                    'type' => 'text_translatable',
                    'name' => 'title',
                    'caption' => _t('_adm_form_txt_pre_lists_title'),
                    'info' => _t('_adm_form_dsc_pre_lists_title'),
                    'value' => '_adm_form_txt_pre_lists',
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'LengthTranslatable',
                        'params' => array(3,100, 'title'),
                        'error' => _t('_adm_form_err_pre_lists_title'),
                    ),
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_form_btn_pre_lists_add'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_form_btn_pre_lists_cancel'),
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
            $oLanguage = BxDolStudioLanguagesUtils::getInstance();
            $sLanguage = $oLanguage->getCurrentLangName(false);

            $sKey = BxDolForm::getSubmittedValue('title-' . $sLanguage, $aForm['form_attrs']['method']);
            $sKey = uriGenerate($sKey, 'sys_form_pre_lists', 'key', 'key');

            $iId = (int)$oForm->insert(array('key' => $sKey));
            if($iId != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_adm_form_err_pre_lists_create'));

            $this->_echoResultJson($aRes, true);
        } 
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-form-pre-list-create-popup', _t('_adm_form_txt_pre_lists_create_popup'), $this->_oTemplate->parseHtmlByName('form_add_list.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            $this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
        }
    }

    public function performActionEdit()
    {
        $sAction = 'edit';

        $aList = $this->_getItem('getLists');
        if($aList === false) {
            $this->_echoResultJson(array());
            exit;
        }

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-form-list-edit',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction,
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_form_pre_lists',
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
                    'value' => $aList['id'],
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'title' => array(
                    'type' => 'text_translatable',
                    'name' => 'title',
                    'caption' => _t('_adm_form_txt_pre_lists_title'),
                    'info' => _t('_adm_form_dsc_pre_lists_title'),
                    'value' => $aList['title'],
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'LengthTranslatable',
                        'params' => array(3,100, 'title'),
                        'error' => _t('_adm_form_err_pre_lists_title'),
                    ),
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_form_btn_pre_lists_save'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_form_btn_pre_lists_cancel'),
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
            if($oForm->update($aList['id']) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aList['id']);
            else
                $aRes = array('msg' => _t('_adm_form_err_pre_lists_edit'));

            $this->_echoResultJson($aRes, true);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-form-pre-list-edit-popup', _t('_adm_form_txt_pre_lists_edit_popup', _t($aList['title'])), $this->_oTemplate->parseHtmlByName('form_add_list.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            $this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
        }
    }

    public function performActionDelete()
    {
        $sAction = 'delete';

        $iAffected = 0;
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            exit;
        }

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
            $aList = array();
            $this->oDb->getLists(array('type' => 'by_id', 'value' => (int)$iId), $aList, false);
            if(!is_array($aList) || empty($aList))
                continue;

            if(!$this->_canDelete($aList))
                continue;

            if(!$this->oDb->deleteValues(array('type' => 'by_key', 'value' => $aList['key'])) || (int)$this->_delete($iId) <= 0)
                continue;

            BxDolStudioLanguagesUtils::getInstance()->deleteLanguageString($aList['title']);

            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        $this->_echoResultJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t('_adm_from_err_pre_lists_delete')));
    }

    function getJsObject()
    {
        return 'oBxDolStudioFormsPreLists';
    }

    function getCode($isDisplayHeader = true)
    {
        return $this->_oTemplate->parseHtmlByName('forms_lists.html', array(
            'content' => parent::getCode($isDisplayHeader),
            'js_object' => $this->getJsObject(),
            'grid_object' => $this->_sObject,
            'params_divider' => $this->sParamsDivider
        ));
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oTemplate->addJs(array('jquery.form.min.js', 'forms_lists.js'));

        $oForm = new BxTemplStudioFormView(array());
        $oForm->addCssJs();
    }

    protected function _getCellValues ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oTemplate->parseHtmlByName('bx_a.html', array(
            'href' => sprintf($this->sUrlViewValues, $aRow['module'], $aRow['key']),
            'title' => _t('_adm_form_txt_pre_lists_manage_values'),
            'bx_repeat:attrs' => array(),
            'content' => _t('_adm_form_txt_pre_lists_n_values', $aRow['values_count'])
        ));

        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellModule($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_limitMaxLength($this->getModuleTitle($aRow['module']), $sKey, $aField, $aRow, $this->_isDisplayPopupOnTextOverflow);
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellUseForSets ($mixedValue, $sKey, $aField, $aRow)
    {
        $aChanger = array(
            '0' => '_adm_form_txt_pre_lists_no',
            '1' => '_adm_form_txt_pre_lists_yes'
        );
        $mixedValue = $this->_limitMaxLength(_t($aChanger[$mixedValue]), $sKey, $aField, $aRow, $this->_isDisplayPopupOnTextOverflow);
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_canDelete($aRow))
            return '';

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }
    protected function _getFilterControls ()
    {
        $sContent = parent::_getFilterControls();

        $oForm = new BxTemplStudioFormView(array());

        $aInputModules = array(
            'type' => 'select',
            'name' => 'module',
            'attrs' => array(
                'id' => 'bx-grid-module-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->getJsObject() . '.onChangeFilter()'
            ),
            'value' => '',
            'values' => $this->getModules()
        );

        $aCounter = array();
        $this->oDb->getLists(array('type' => 'counter_by_modules'), $aCounter, false);
        foreach($aInputModules['values'] as $sKey => $sValue)
                $aInputModules['values'][$sKey] = $aInputModules['values'][$sKey] . " (" . (isset($aCounter[$sKey]) ? $aCounter[$sKey] : "0") . ")";

        $aInputModules['values'] = array_merge(array('' => _t('_adm_form_txt_all_modules')), $aInputModules['values']);

        return  $oForm->genRow($aInputModules) . $sContent;
    }

    protected function _canDelete($aList)
    {
        return $aList['module'] == BX_DOL_STUDIO_MODULE_CUSTOM;
    }
}

/** @} */
