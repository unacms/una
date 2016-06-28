<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     TridentModules
 *
 * @{
 */

class BxDevFormsForms extends BxTemplStudioFormsForms
{
    protected $oModule;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->oModule = BxDolModule::getInstance('bx_developer');
        $this->sUrlViewDisplays = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=forms&form_page=displays&form_module=%s&form_object=%s';

        $this->_aOptions['actions_single']['export']['attr']['title'] = _t('_bx_dev_frm_btn_forms_gl_export');
    }

    public function performActionAdd()
    {
        $sAction = 'add';
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_form');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_form_add');

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_module')), BxDolStudioUtils::getModules());

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sObject = uriGenerate($oForm->getCleanValue('object'), 'sys_objects_form', 'object', 'object');
            BxDolForm::setSubmittedValue('object', $sObject, $oForm->aFormAttrs['method']);

            if(($iId = (int)$oForm->insert()) != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_frm_err_forms_create'));

            echoJson($aRes);
        } else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-form-create-popup', _t('_bx_dev_frm_txt_forms_create_popup'), $this->oModule->_oTemplate->parseHtmlByName('form_add_form.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }

    public function performActionEdit()
    {
        $sAction = 'edit';
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_form');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_form_edit');

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
        $this->oDb->getForms(array('type' => 'by_id', 'value' => $iId), $aForm, false);
        if(empty($aForm) || !is_array($aForm)){
            echoJson(array());
            exit;
        }

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_module')), BxDolStudioUtils::getModules());
        $oForm->aInputs['controls'][0]['value'] = _t('_bx_dev_frm_btn_forms_save');

        $aForm['form_attrs'] = BxDevFunctions::unserializeString($aForm['form_attrs']);
        $aForm['params'] = BxDevFunctions::unserializeString($aForm['params']);
        $oForm->initChecker($aForm);

        if($oForm->isSubmittedAndValid()) {
            $sValue = BxDolForm::getSubmittedValue('form_attrs', $oForm->aFormAttrs['method']);
            $sValue = BxDevFunctions::serializeString($sValue);
            BxDolForm::setSubmittedValue('form_attrs', $sValue, $oForm->aFormAttrs['method']);

            $sValue = BxDolForm::getSubmittedValue('params', $oForm->aFormAttrs['method']);
            $sValue = BxDevFunctions::serializeString($sValue);
            BxDolForm::setSubmittedValue('params', $sValue, $oForm->aFormAttrs['method']);

            if($oForm->update($iId) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_frm_err_forms_edit'));

            echoJson($aRes);
        } else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-form-edit-popup', _t('_bx_dev_frm_txt_forms_edit_popup', _t($aForm['title'])), $this->oModule->_oTemplate->parseHtmlByName('form_add_form.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }

    public function performActionExport()
    {
        $sContentInsert = $sContentDelete = "";

        $aForm = $this->_getItem('getForms');
        if($aForm === false) {
            echoJson(array());
            exit;
        }

        $sContentInsert .= ($this->oModule->_oDb->getQueryInsert('sys_objects_form', array($aForm), "Dumping data for '" . $aForm['object'] . "' form"));
        $sContentDelete .= ($this->oModule->_oDb->getQueryDelete('sys_objects_form', 'object', array($aForm), "Deleting data for '" . $aForm['object'] . "' form"));

        $aDisplays = array();
        $this->oDb->getDisplays(array('type' => 'by_object', 'value' => $aForm['object']), $aDisplays, false);
        $sContentInsert .= $this->oModule->_oDb->getQueryInsert('sys_form_displays', $aDisplays, false, array('id', 'name'));
        $sContentDelete .= $this->oModule->_oDb->getQueryDelete('sys_form_displays', 'object', array($aForm), false);

        $aFields = array();
        $this->oDb->getInputs(array('type' => 'dump_inputs', 'value' => $aForm['object']), $aFields, false);
        $sContentInsert .= $this->oModule->_oDb->getQueryInsert('sys_form_inputs', $aFields);
        $sContentDelete .= $this->oModule->_oDb->getQueryDelete('sys_form_inputs', 'object', array($aForm), false);

        $aConnections = array();
        $this->oDb->getInputs(array('type' => 'dump_connections', 'value' => $aForm['object']), $aConnections, false);
        $sContentInsert .= $this->oModule->_oDb->getQueryInsert('sys_form_display_inputs', $aConnections);
        $sContentDelete .= $this->oModule->_oDb->getQueryDelete('sys_form_display_inputs', 'display_name', $aDisplays);

        $aFormStructure = array(
            'form_attrs' => array(),
            'inputs' => array (
                'insert' => array(
                    'type' => 'textarea',
                    'name' => 'insert',
                    'caption' => _t('_bx_dev_frm_txt_forms_export_insert'),
                    'value' => $sContentInsert,
                ),
                'delete' => array(
                    'type' => 'textarea',
                    'name' => 'delete',
                    'caption' => _t('_bx_dev_frm_txt_forms_export_delete'),
                    'value' => $sContentDelete,
                ),
                'done' => array (
                    'type' => 'button',
                    'name' => 'done',
                    'value' => _t('_bx_dev_frm_btn_forms_done'),
                    'attrs' => array(
                        'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                    ),
                )
            )
        );
        $oForm = new BxTemplStudioFormView($aFormStructure);

        $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-form-export-popup', _t('_bx_dev_frm_txt_forms_export_popup', _t($aForm['title'])), $this->oModule->_oTemplate->parseHtmlByName('form_export.html', array(
            'content' => $oForm->getCode()
        )));

        echoJson(array('popup' => $sContent));
    }
}
/** @} */
