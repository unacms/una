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
bx_import('BxTemplStudioNavigationItems');

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

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('mod_dev_nav_item', 'mod_dev_nav_item_add');
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&set=' . $this->sSet;
        $this->fillInSelects($oForm->aInputs);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            if(($iId = (int)$oForm->insert()) != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_nav_err_items_create'));

            $this->_echoResultJson($aRes, true);
        } else {
            bx_import('BxTemplStudioFunctions');
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-nav-item-create-popup', _t('_bx_dev_nav_txt_items_create_popup'), $this->_oTemplate->parseHtmlByName('nav_add_item.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            $this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
        }
    }

    public function performActionEdit($bUpdateGrid = false)
    {
        $sAction = 'edit';

        $aItem = $this->_getItem('getItems');
        if($aItem === false) {
            $this->_echoResultJson(array());
            exit;
        }

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('mod_dev_nav_item', 'mod_dev_nav_item_edit');
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&set=' . $this->sSet;
        $oForm->aInputs['controls'][0]['value'] = _t('_bx_dev_nav_btn_items_save');
        $this->fillInSelects($oForm->aInputs);

        $oForm->initChecker($aItem);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aItem['id']) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aItem['id']);
            else
                $aRes = array('msg' => _t('_bx_dev_nav_err_items_edit'));

            $this->_echoResultJson($aRes, true);
        } else {
            bx_import('BxTemplStudioFunctions');
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-nav-item-edit-popup', _t('_bx_dev_nav_txt_items_edit_popup', _t($aItem['title_system'])), $this->oModule->_oTemplate->parseHtmlByName('nav_add_item.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            $this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
        }
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    private function fillInSelects(&$aInputs)
    {
        $aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_nav_txt_select_module')), BxDolStudioUtils::getModules());
        $aInputs['module']['value'] = $this->sModule;

        $aInputs['set_name']['value'] = $this->sSet;
    }
}
/** @} */
