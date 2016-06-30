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

class BxDevFormsPreValues extends BxTemplStudioFormsPreValues
{
    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->oModule = BxDolModule::getInstance('bx_developer');
        $this->sUrlPage = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=forms&form_page=pre_values';

        $sModule = bx_get('form_module');
        if(!empty($sModule)) {
            $this->sModule = bx_process_input($sModule);
            $this->_aQueryAppend['module'] = $this->sModule;
        }

        $sList = bx_get('form_list');
        if(!empty($sList)) {
            $this->sList = bx_process_input($sList);
            $this->_aQueryAppend['list'] = $this->sList;
        }
    }

    public function performActionAdd()
    {
        $sAction = 'add';
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_prevalue');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_prevalue_add');

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&list=' . $this->sList;

        $this->onLoad($oForm->aInputs);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            if(!$this->onAdd($oForm))
                return;

            if(($iId = (int)$oForm->insert()) != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_frm_err_prevalues_create'));

            echoJson($aRes);
        } else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-prevalue-create-popup', _t('_bx_dev_frm_txt_prevalues_create_popup'), $this->oModule->_oTemplate->parseHtmlByName('form_add_value.html', array(
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
        $sFormObject = $this->oModule->_oConfig->getObject('form_forms_prevalue');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_forms_prevalue_edit');

        $aValue = $this->_getItem('getValues');
        if($aValue === false) {
            echoJson(array());
            exit;
        }

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&list=' . $this->sList;

        $this->onLoad($oForm->aInputs);

        $oForm->initChecker($aValue);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aValue['id']) !== false) {
                $this->onSave($oForm);
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aValue['id']);
            } else
                $aRes = array('msg' => _t('_bx_dev_frm_err_prevalues_edit'));

            echoJson($aRes);
        } else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-frm-prevalue-edit-popup', _t('_bx_dev_frm_txt_prevalues_edit_popup', _t($aValue['lkey'])), $this->oModule->_oTemplate->parseHtmlByName('form_add_value.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }

    private function onLoad(&$aInputs)
    {
        $aLists = array();
        $this->oDb->getLists(array('type' => 'all'), $aLists, false);
        foreach($aLists as $aList)
            $aInputs['Key']['values'][$aList['key']] = _t($aList['title']);

        asort($aInputs['Key']['values']);
        $aInputs['Key']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_prevalues_key_select')), $aInputs['Key']['values']);
        $aInputs['Key']['value'] = $this->sList;

        $aInputs['Value']['value'] = $this->_getAvailableSetValue($this->sList);
    }

    private function onAdd(&$oForm)
    {
        $aList = array();
        $this->oDb->getLists(array('type' => 'by_key', 'value' => $oForm->getCleanValue('Key')), $aList, false);

        if((int)$aList['use_for_sets'] == 1) {
            $mixedValue = $oForm->getCleanValue('Value');
            if(!$this->canUseForSet($mixedValue)) {
                if($this->oDb->isListUsedInSet($this->sList)) {
                    echoJson(array('msg' => _t('_bx_dev_frm_err_prevalues_create_forbidden', BX_DOL_STUDIO_FIELD_PRE_VALUE_INT_MAX)));
                    return false;
                }

                $this->oDb->updateList($aList['id'], array('use_for_sets' => '0'));
            }
        }

        return true;
    }

    private function onSave(&$oForm)
    {
        $aList = array();
        $this->oDb->getLists(array('type' => 'by_key', 'value' => $oForm->getCleanValue('Key')), $aList, false);

        $iUseInSets = 1;
        $aValues = BxDolForm::getDataItems($aList['key']);
        foreach($aValues as $mixedValue => $sTitle)
            if(!$this->canUseForSet($mixedValue)) {
                $iUseInSets = 0;
                break;
            }

        if($iUseInSets != (int)$aList['use_for_sets'])
            $this->oDb->updateList($aList['id'], array('use_for_sets' => $iUseInSets));
    }
}
/** @} */
