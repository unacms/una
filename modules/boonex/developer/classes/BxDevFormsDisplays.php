<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModule');
bx_import('BxTemplStudioFormsDisplays');

class BxDevFormsDisplays extends BxTemplStudioFormsDisplays
{
    protected $oModule;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->oModule = BxDolModule::getInstance('bx_developer');
        $this->sUrlPage = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=forms&form_page=displays';
        $this->sUrlViewFields = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=forms&form_page=fields&form_module=%s&form_object=%s&form_display=%s';

        $sModule = bx_get('form_module');
        if(!empty($sModule)) {
            $this->sModule = bx_process_input($sModule);
            $this->_aQueryAppend['module'] = $this->sModule;
        }

        $sObject = bx_get('form_object');
        if(!empty($sObject)) {
            $this->sObject = bx_process_input($sObject);
            $this->_aQueryAppend['object'] = $this->sObject;
        }
    }

    public function performActionAdd()
    {
        $sAction = 'add';
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_display');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_display_add');

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $this->_fillDisplayForm($oForm, $sAction);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sName = uriGenerate($oForm->getCleanValue('display_name'), 'sys_form_displays', 'display_name', 'display');
            BxDolForm::setSubmittedValue('display_name', $sName, $oForm->aFormAttrs['method']);

            if(($iId = (int)$oForm->insert()) != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_frm_err_displays_create'));

            $this->_echoResultJson($aRes, true);
        } else {
            bx_import('BxTemplStudioFunctions');
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-display-create-popup', _t('_bx_dev_frm_txt_displays_create_popup'), $this->_oTemplate->parseHtmlByName('form_add_display.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
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
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_display');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_display_edit');

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) {
                $this->_echoResultJson(array());
                exit;
            }

            $aIds = array($iId);
        }

        $iId = $aIds[0];

        $aDisplay = array();
        $iDisplay = $this->oDb->getDisplays(array('type' => 'by_id', 'value' => $iId), $aDisplay);
        if($iDisplay != 1 || empty($aDisplay)){
            $this->_echoResultJson(array());
            exit;
        }

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);

        $this->_fillDisplayForm($oForm, $sAction);
        $oForm->aInputs['controls'][0]['value'] = _t('_bx_dev_frm_btn_displays_save');

        $oForm->initChecker($aDisplay);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_frm_err_displays_edit'));

            $this->_echoResultJson($aRes, true);
        } else {
            bx_import('BxTemplStudioFunctions');
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-display-edit-popup', _t('_bx_dev_frm_txt_displays_edit_popup', _t($aDisplay['title'])), $this->_oTemplate->parseHtmlByName('form_add_display.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            $this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
        }
    }

    protected function _getActionAdd($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if($this->sObject == '')
            $isDisabled = true;

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _fillDisplayForm(&$oForm, $sAction)
    {
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&object=' . $this->sObject;
        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_module')), BxDolStudioUtils::getModules());
        $oForm->aInputs['module']['value'] = $this->sModule;

        $aForms = array();
        $this->oDb->getForms(array('type' => 'by_module', 'value' => $this->sModule), $aForms, false);
        foreach($aForms as $aForm)
            $oForm->aInputs['object']['values'][$aForm['object']] = _t($aForm['title']);

        asort($oForm->aInputs['object']['values']);
        $oForm->aInputs['object']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_object')), $oForm->aInputs['object']['values']);
        $oForm->aInputs['object']['value'] = $this->sObject;
    }
}
/** @} */
