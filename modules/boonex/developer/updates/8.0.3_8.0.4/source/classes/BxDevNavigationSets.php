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
bx_import('BxTemplStudioNavigationSets');

class BxDevNavigationSets extends BxTemplStudioNavigationSets
{
    protected $oModule;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->oModule = BxDolModule::getInstance('bx_developer');
        $this->sUrlViewItems = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=navigation&nav_page=items&nav_module=%s&nav_set=%s';
    }

    public function performActionAdd()
    {
        $sAction = 'add';
        $sFormObject = $this->oModule->_oConfig->getObject('form_nav_set');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_nav_set_add');

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_nav_txt_select_module')), BxDolStudioUtils::getModules());

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sName = uriGenerate($oForm->getCleanValue('set_name'), 'sys_menu_sets', 'set_name', 'set');
            BxDolForm::setSubmittedValue('set_name', $sName, $oForm->aFormAttrs['method']);

            if($oForm->insert() !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $sName);
            else
                $aRes = array('msg' => _t('_bx_dev_nav_err_sets_create'));

            $this->_echoResultJson($aRes, true);
        } else {
            bx_import('BxTemplStudioFunctions');
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-nav-set-create-popup', _t('_bx_dev_nav_txt_sets_create_popup'), $this->_oTemplate->parseHtmlByName('nav_add_set.html', array(
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
	    $sFormObject = $this->oModule->_oConfig->getObject('form_nav_set');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_nav_set_edit');

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $sId = bx_get('set_name');
            if(!$sId) {
                $this->_echoResultJson(array());
                exit;
            }

            $aIds = array($sId);
        }

        $sId = bx_process_input($aIds[0]);

        $aSet = array();
        $this->oDb->getSets(array('type' => 'by_name', 'value' => $sId), $aSet, false);
        if(empty($aSet) || !is_array($aSet)) {
            $this->_echoResultJson(array());
            exit;
        }

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_module')), BxDolStudioUtils::getModules());
        $oForm->aInputs['controls'][0]['value'] = _t('_bx_dev_frm_btn_displays_save');

        $oForm->initChecker($aSet);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($sId) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $sId);
            else
                $aRes = array('msg' => _t('_bx_dev_nav_err_sets_edit'));

            $this->_echoResultJson($aRes, true);
        } else {
            bx_import('BxTemplStudioFunctions');
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-nav-set-edit-popup', _t('_bx_dev_nav_txt_sets_edit_popup', _t($aSet['title'])), $this->_oTemplate->parseHtmlByName('nav_add_set.html', array(
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
}
/** @} */
