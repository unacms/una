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

class BxDevFormsSearchForms extends BxTemplStudioFormsSearchForms
{
    protected $oModule;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->oModule = BxDolModule::getInstance('bx_developer');
        $this->sUrlViewFields = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=forms&form_page=search_fields&form_module=%s&form_form=%s';

        $this->_aOptions['actions_single']['edit']['attr']['title'] = _t('_bx_dev_frm_btn_search_forms_gl_edit');
    }

    public function performActionAdd()
    {
        $sAction = 'add';
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_search_form');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_search_form_add');

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;

        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_module')), BxDolStudioUtils::getModules());
        $oForm->aInputs['object_content_info']['values'] = array('' => _t('_bx_dev_frm_txt_select_object_content_info'));
        $aCiObjects = BxDolContentInfo::getSystems();
        foreach($aCiObjects as $aCiObject)
            $oForm->aInputs['object_content_info']['values'][$aCiObject['name']] = _t($aCiObject['title']);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sObject = uriGenerate($oForm->getCleanValue('object'), 'sys_objects_search_extended', 'object', 'object');
            BxDolForm::setSubmittedValue('object', $sObject, $oForm->aFormAttrs['method']);

            if(($iId = (int)$oForm->insert()) != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_frm_err_search_forms_create'));

            return echoJson($aRes);
        }

        $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-search-form-create-popup', _t('_bx_dev_frm_txt_search_forms_create_popup'), $this->oModule->_oTemplate->parseHtmlByName('form_add_search_form.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        )));

        return echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
    }

    public function performActionEdit()
    {
        $sAction = 'edit';
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_search_form');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_search_form_edit');

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

        $aForm = array();
        $this->oDb->getSearchForms(array('type' => 'by_id', 'id' => $iId), $aForm, false);
        if(empty($aForm) || !is_array($aForm)){
            echoJson(array());
            exit;
        }

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_module')), BxDolStudioUtils::getModules());
        $oForm->aInputs['object_content_info']['values'] = array('' => _t('_bx_dev_frm_txt_select_object_content_info'));
        $aCiObjects = BxDolContentInfo::getSystems();
        foreach($aCiObjects as $aCiObject)
            $oForm->aInputs['object_content_info']['values'][$aCiObject['name']] = _t($aCiObject['title']);
        $oForm->aInputs['controls'][0]['value'] = _t('_bx_dev_frm_btn_search_forms_save');

        $oForm->initChecker($aForm);
        if($oForm->isSubmittedAndValid()) {
            $sObject = $oForm->getCleanValue('object');
            if(strcmp($sObject, $aForm['object']) != 0) {
                $sObject = uriGenerate($sObject, 'sys_objects_search_extended', 'object', 'object');
                BxDolForm::setSubmittedValue('object', $sObject, $oForm->aFormAttrs['method']);
            }

            if($oForm->update($iId) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_frm_err_forms_edit'));

            return echoJson($aRes);
        }

        $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-search-form-edit-popup', _t('_bx_dev_frm_txt_search_forms_edit_popup', _t($aForm['title'])), $this->oModule->_oTemplate->parseHtmlByName('form_add_search_form.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        )));

        return echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
    }
}
/** @} */
