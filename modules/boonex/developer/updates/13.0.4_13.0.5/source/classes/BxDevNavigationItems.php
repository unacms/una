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

class BxDevNavigationItems extends BxTemplStudioNavigationItems
{
    protected $oModule;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->oModule = BxDolModule::getInstance('bx_developer');
        $this->sUrlPage = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=navigation&nav_page=items';
        $this->sUrlViewItems = $this->sUrlPage . '&nav_module=%s&nav_set=%s';

        $sModule = bx_get('nav_module');
        if(!empty($sModule)) {
            $this->sModule = bx_process_input($sModule);
            $this->_aQueryAppend['module'] = $this->sModule;
        }

        $sSet = bx_get('nav_set');
        if(!empty($sSet)) {
            $this->sSet = bx_process_input($sSet);
            $this->_aQueryAppend['set'] = $this->sSet;
        }
    }

    public function performActionAdd()
    {
        $sAction = 'add';
        $sFormObject = $this->oModule->_oConfig->getObject('form_nav_item');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_nav_item_add');

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&set=' . $this->sSet;
        $this->fillInSelects($oForm->aInputs);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            if(($iId = (int)$oForm->insert()) != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_nav_err_items_create'));

            echoJson($aRes);
        } else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-nav-item-create-popup', _t('_bx_dev_nav_txt_items_create_popup'), $this->_oTemplate->parseHtmlByName('nav_add_item.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }

    public function performActionEdit($bUpdateGrid = false)
    {
        $sAction = 'edit';
        $sFormObject = $this->oModule->_oConfig->getObject('form_nav_item');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_nav_item_edit');

        $aItem = $this->_getItem('getItems');
        if($aItem === false) {
            echoJson(array());
            exit;
        }

        $this->_prepareServiceEdit('addon', $aItem);
        $this->_prepareServiceEdit('visibility_custom', $aItem);

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&set=' . $this->sSet;
        $oForm->aInputs['controls'][0]['value'] = _t('_bx_dev_nav_btn_items_save');
        $this->fillInSelects($oForm->aInputs);

        $oForm->initChecker($aItem);
        if($oForm->isSubmittedAndValid()) {
            $this->_prepareServiceSave('addon', $oForm);
            $this->_prepareServiceSave('visibility_custom', $oForm);

            if($oForm->update($aItem['id']) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aItem['id']);
            else
                $aRes = array('msg' => _t('_bx_dev_nav_err_items_edit'));

            echoJson($aRes);
        } else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-nav-item-edit-popup', _t('_bx_dev_nav_txt_items_edit_popup', _t($aItem['title_system'])), $this->oModule->_oTemplate->parseHtmlByName('nav_add_item.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }

    protected function _prepareServiceEdit($sField, &$aItem)
    {
        if(empty($aItem[$sField]))
            return;

        $aItem[$sField] = BxDevFunctions::unserializeString($aItem[$sField]);
    }

    protected function _prepareServiceSave($sField, &$oForm)
    {
        $sValue = $oForm->getCleanValue($sField);
        $sValue = BxDevFunctions::serializeString($sValue);
        BxDolForm::setSubmittedValue($sField, $sValue, $oForm->aFormAttrs['method']);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _isEditable(&$aRow)
    {
    	return true;
    }

	protected function _isDeletable(&$aRow)
    {
    	return true;
    }

    private function fillInSelects(&$aInputs)
    {
        $aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_nav_txt_select_module')), BxDolStudioUtils::getModules());
        $aInputs['module']['value'] = $this->sModule;

        $aInputs['set_name']['value'] = $this->sSet;

        $aInputs['hidden_on']['values'] = array(
            BX_DB_HIDDEN_PHONE => _t('_bx_dev_nav_txt_sys_items_hidden_on_phone'),
            BX_DB_HIDDEN_TABLET => _t('_bx_dev_nav_txt_sys_items_hidden_on_tablet'),
            BX_DB_HIDDEN_DESKTOP => _t('_bx_dev_nav_txt_sys_items_hidden_on_desktop'),
            BX_DB_HIDDEN_MOBILE => _t('_bx_dev_nav_txt_sys_items_hidden_on_mobile')
        );

        $aInputs['hidden_on_pt']['values'] = [];
        $aPageTypes = BxDolPageQuery::getPageTypes();
        foreach($aPageTypes as $aPageType) {
            $iPageType = (int)$aPageType['id'];
            if($iPageType == 1)
                continue;

            $aInputs['hidden_on_pt']['values'][$iPageType - 1] = _t($aPageType['title']);
        }

        $aInputs['hidden_on_col']['values'] = [
            1 => _t('_adm_nav_txt_block_hidden_on_col_thin'),
            2 => _t('_adm_nav_txt_block_hidden_on_col_half'),
            3 => _t('_adm_nav_txt_block_hidden_on_col_wide'),
            4 => _t('_adm_nav_txt_block_hidden_on_col_full')
        ];
    }
}
/** @} */
