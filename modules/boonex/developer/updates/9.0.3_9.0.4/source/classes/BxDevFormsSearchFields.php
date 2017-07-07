<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Developer Developer
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDevFormsSearchFields extends BxTemplStudioFormsSearchFields
{
    protected $oModule;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->oModule = BxDolModule::getInstance('bx_developer');
        $this->sUrlPage = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=forms&form_page=search_fields';

        $this->_aOptions['actions_single']['edit']['attr']['title'] = _t('_bx_dev_frm_btn_search_forms_fields_edit');

        $sModule = bx_get('form_module');
        if(!empty($sModule)) {
            $this->sModule = bx_process_input($sModule);
            $this->_aQueryAppend['module'] = $this->sModule;
        }

        $sForm = bx_get('form_form');
        if(!empty($sForm)) {
            $this->sForm = bx_process_input($sForm);
            $this->_aQueryAppend['form'] = $this->sForm;
        }
    }

    public function performActionEdit()
    {
        $sAction = 'edit';
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_search_fields');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_search_fields_edit');

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
        $iField = $this->oDb->getSearchFields(array('type' => 'by_id', 'id' => $iId), $aField);
        if($iField != 1 || empty($aField)){
            echoJson(array());
            exit;
        }

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = bx_append_url_params(BX_DOL_URL_ROOT . 'grid.php?', array(
        	'o' => $this->_sObject,
            'a' => $sAction,
            'module' => $this->sModule,
            'form' => $this->sForm
        ));

        $aForms = array();
        $this->oDb->getSearchForms(array('type' => 'by_module', 'module' => $this->sModule), $aForms, false);

        $oForm->aInputs['object']['value'] = $this->sForm;
        $oForm->aInputs['object']['values'] = array();
        foreach($aForms as $aForm)
            $oForm->aInputs['object']['values'][$aForm['object']] = _t($aForm['title']);
        asort($oForm->aInputs['object']['values']);
        $oForm->aInputs['object']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_object')), $oForm->aInputs['object']['values']);

        foreach(BxDolSearchExtended::$SEARCHABLE_TYPES as $sType)
            $oForm->aInputs['type']['values'][] = array('key' => $sType, 'value' => _t('_adm_form_txt_field_type_' . $sType));

        foreach(BxDolSearchExtended::$TYPE_TO_TYPE_SEARCH[$aField['type']] as $sType)
            $oForm->aInputs['search_type']['values'][] = array('key' => $sType, 'value' => _t('_adm_form_txt_field_type_' . $sType));

        foreach(BxDolSearchExtended::$TYPE_TO_OPERATOR[$aField['type']] as $sOperator)
            $oForm->aInputs['search_operator']['values'][] = array('key' => $sOperator, 'value' => $sOperator);

        $oForm->initChecker($aField);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_frm_err_search_fields_edit'));

            return echoJson($aRes);
        }

        $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-search-fields-edit-popup', _t('_bx_dev_frm_txt_search_fields_edit_popup', _t($aField['caption'])), $this->_oTemplate->parseHtmlByName('form_add_search_field.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        )));

        return echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
    }

    protected function _getActionEdit ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if($sType == 'single' && !$this->_isEditable($aRow))
            return '';

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }
}
/** @} */
