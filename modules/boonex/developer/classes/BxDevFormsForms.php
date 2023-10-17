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

    public function getCode($isDisplayHeader = true)
    {
        return $this->oModule->_oTemplate->getJsCode('main') . parent::getCode($isDisplayHeader);
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
            $sObject = uriGenerate($oForm->getCleanValue('object'), 'sys_objects_form', 'object', ['empty' => 'object']);
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
            $sObject = $oForm->getCleanValue('object');
            if(strcmp($sObject, $aForm['object']) != 0) {
                $sObject = uriGenerate($sObject, 'sys_objects_form', 'object', ['empty' => 'object']);
                BxDolForm::setSubmittedValue('object', $sObject, $oForm->aFormAttrs['method']);
            }

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
                    'code' => 1
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

    public function performActionExportFull()
    {
        echoJson([
            'url' => BX_DOL_URL_ROOT . bx_append_url_params($this->oModule->_oConfig->getBaseUri() . 'download', [
                'type' => 'forms',
            ]),
            'eval' => $this->oModule->_oConfig->getJsObject('main') . '.onExport(oData);'
        ]);
    }
    
    public function performActionImportFull()
    {
        $sAction = 'import_full';

        $aResult = $this->oModule->getPopupCodeImport([
            'form_name' => 'bx-dev-forms-import-full',
            'form_action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction
        ]);
        
        if(!isset($aResult['code']) || (int)$aResult['code'] != 0)
            return echoJson($aResult);

        $aContent = $aResult['content'];
        $bModeFull = isset($aContent['meta']['full']) && (bool)$aContent['meta']['full'] === true;

        $aMfForm = $aMfDisplay = $aMfInput = $aMfDisplayInput = false;
        foreach($aContent['meta']['masks'] as $sMask => $aMask)
            ${'aMf' . bx_gen_method_name($sMask)} = array_flip($aMask);

        if($aResult['disable'] != 0) {
            $this->oDb->updateForms(['active' => 0]);
            $this->oDb->updateDisplayInputs(['active' => 0]);
        }

        $iData = 0;
        foreach($aContent['data'] as $aData) {
            $iData += 1;

            $sObject = $aData['form']['object'];
            if($bModeFull && !$this->oDb->isForm($sObject))
                $this->oDb->addForm($aData['form']);
            else
                $this->oDb->updateFormByObject($sObject, $aData['form']);
            
            foreach($aData['displays'] as $aDisplay)
                if($bModeFull && !$this->oDb->isDisplay($sObject,  $aDisplay['display_name']))
                    $this->oDb->addDisplay($aDisplay);
                else
                    $this->oDb->updateDisplayByObjectAndName($sObject, $aDisplay['display_name'], $aDisplay);

            foreach($aData['inputs'] as $aInput)
                if($bModeFull && !$this->oDb->isInput($sObject,  $aInput['name']))
                    $this->oDb->addInput($aInput);
                else
                    $this->oDb->updateInputByObjectAndName($sObject, $aInput['name'], $aInput);
                
            foreach($aData['display_inputs'] as $aDisplayInput)
                if($bModeFull && !$this->oDb->isDisplayInput($aDisplayInput['display_name'],  $aDisplayInput['input_name']))
                    $this->oDb->addDisplayInput($aDisplayInput);
                else
                    $this->oDb->updateDisplayInputByDisplayAndInput($aDisplayInput['display_name'],  $aDisplayInput['input_name'], $aDisplayInput);
        }

        BxDolCacheUtilities::getInstance()->clear('db');

        echoJson([
            'msg' => _t('_bx_dev_msg_imported', $iData), 
            'eval' => $this->oModule->_oConfig->getJsObject('main') . '.onImport(oData);'
        ]);
    }
}
/** @} */
