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

class BxDevFormsPreLists extends BxTemplStudioFormsPreLists
{
    protected $oModule;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->oModule = BxDolModule::getInstance('bx_developer');
        $this->sUrlViewValues = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=forms&form_page=pre_values&form_module=%s&form_list=%s';
    }

    public function performActionAdd()
    {
        $sAction = 'add';
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_prelist');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_prelist_add');

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_module')), BxDolStudioUtils::getModules());

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sObject = uriGenerate($oForm->getCleanValue('key'), 'sys_form_pre_lists', 'key', 'key');
            BxDolForm::setSubmittedValue('key', $sObject, $oForm->aFormAttrs['method']);

            if(($iId = (int)$oForm->insert()) != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_frm_err_prelists_create'));

            $this->_echoResultJson($aRes, true);
        } else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-prelist-create-popup', _t('_bx_dev_frm_txt_prelists_create_popup'), $this->oModule->_oTemplate->parseHtmlByName('form_add_list.html', array(
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
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_prelist');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_prelist_edit');

        $aList = $this->_getItem('getLists');
        if($aList === false) {
            $this->_echoResultJson(array());
            exit;
        }

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_module')), BxDolStudioUtils::getModules());
        $oForm->aInputs['controls'][0]['value'] = _t('_bx_dev_frm_btn_prelists_save');

        $oForm->initChecker($aList);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aList['id']) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aList['id']);
            else
                $aRes = array('msg' => _t('_bx_dev_frm_err_prelists_edit'));

            $this->_echoResultJson($aRes, true);
        } else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-prelist-edit-popup', _t('_bx_dev_frm_txt_prelists_edit_popup', _t($aList['title'])), $this->oModule->_oTemplate->parseHtmlByName('form_add_list.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            $this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
        }
    }

    public function performActionExport()
    {
        $sContentInsert = $sContentDelete = "";

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

        $aList = array();
        $this->oDb->getLists(array('type' => 'by_id', 'value' => $iId), $aList, false);
        if(!is_array($aList) || empty($aList)){
            $this->_echoResultJson(array());
            exit;
        }

        $sContentInsert .= ($this->oModule->_oDb->getQueryInsert('sys_form_pre_lists', array($aList), "Dumping data for '" . $aList['key'] . "' data list"));
        $sContentDelete .= ($this->oModule->_oDb->getQueryDelete('sys_form_pre_lists', 'key', array($aList), "Deleting data for '" . $aList['key'] . "' data list"));

        $aValues = array();
        $this->oDb->getValues(array('type' => 'by_key', 'value' => $aList['key']), $aValues, false);
        $sContentInsert .= $this->oModule->_oDb->getQueryInsert('sys_form_pre_values', $aValues, false, array('id', 'key', 'value', 'lkey', 'lkey2', 'order'));
        $sContentDelete .= $this->oModule->_oDb->getQueryDelete('sys_form_pre_values', 'Key', array(array('Key' => $aList['key'])), false);

        $aFormStructure = array(
            'form_attrs' => array(),
            'inputs' => array (
                'insert' => array(
                    'type' => 'textarea',
                    'name' => 'insert',
                    'caption' => _t('_bx_dev_frm_txt_prelists_export_insert'),
                    'value' => $sContentInsert,
                ),
                'delete' => array(
                    'type' => 'textarea',
                    'name' => 'delete',
                    'caption' => _t('_bx_dev_frm_txt_prelists_export_delete'),
                    'value' => $sContentDelete,
                ),
                'done' => array (
                    'type' => 'button',
                    'name' => 'done',
                    'value' => _t('_bx_dev_frm_btn_prelists_done'),
                    'attrs' => array(
                        'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                    ),
                )
            )
        );
        $oForm = new BxTemplStudioFormView($aFormStructure);

        $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-prelist-export-popup', _t('_bx_dev_frm_txt_prelists_export_popup', _t($aList['title'])), $this->oModule->_oTemplate->parseHtmlByName('form_export.html', array(
            'content' => $oForm->getCode()
        )));

        $this->_echoResultJson(array('popup' => $sContent), true);
    }

    protected function _canDelete($aList)
    {
        return true;
    }
}

/** @} */
