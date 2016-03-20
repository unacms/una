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

class BxDevNavigationMenus extends BxTemplStudioNavigationMenus
{
    protected $oModule;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_aOptions['actions_single']['export']['attr']['title'] = _t('_bx_dev_nav_btn_menus_gl_export');

        $this->oModule = BxDolModule::getInstance('bx_developer');
        $this->sUrlViewItems = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=navigation&nav_page=items&nav_module=%s&nav_set=%s';
    }

    public function performActionAdd()
    {
        $sAction = 'add';
        $sFormObject = $this->oModule->_oConfig->getObject('form_nav_menu');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_nav_menu_add');

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $this->fillInSelects($oForm->aInputs);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sObject = uriGenerate($oForm->getCleanValue('object'), 'sys_objects_menu', 'object', 'object');
            BxDolForm::setSubmittedValue('object', $sObject, $oForm->aFormAttrs['method']);

            if(($iId = (int)$oForm->insert()) != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_dev_nav_err_menus_create'));

            echoJson($aRes);
        } else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-nav-menu-create-popup', _t('_bx_dev_nav_txt_menus_create_popup'), $this->oModule->_oTemplate->parseHtmlByName('nav_add_menu.html', array(
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
        $sFormObject = $this->oModule->_oConfig->getObject('form_nav_menu');
        $sFormDisplay = $this->oModule->_oConfig->getObject('form_display_nav_menu_edit');

        $aMenu = $this->_getItem('getMenus');
        if($aMenu === false) {
            echoJson(array());
            exit;
        }

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->aInputs['controls'][0]['value'] = _t('_bx_dev_nav_btn_menus_save');
        $this->fillInSelects($oForm->aInputs);

        $oForm->initChecker($aMenu);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aMenu['id']) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aMenu['id']);
            else
                $aRes = array('msg' => _t('_bx_dev_nav_err_menus_edit'));

            echoJson($aRes);
        } else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-nav-menu-edit-popup', _t('_bx_dev_nav_txt_menus_edit_popup', _t($aMenu['title'])), $this->oModule->_oTemplate->parseHtmlByName('nav_add_menu.html', array(
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

        $aMenu = $this->_getItem('getMenus');
        if($aMenu === false) {
            echoJson(array());
            exit;
        }

        $sContentInsert .= $this->oModule->_oDb->getQueryInsert('sys_objects_menu', array($aMenu), "Dumping data for '" . $aMenu['object'] . "' menu");
        $sContentDelete .= $this->oModule->_oDb->getQueryDelete('sys_objects_menu', 'object', array($aMenu), "Deleting data for '" . $aMenu['object'] . "' menu");

        $aSet = array();
        $this->oDb->getSets(array('type' => 'dump_by_name', 'value' => $aMenu['set_name']), $aSet, false);
        $sContentInsert .= $this->oModule->_oDb->getQueryInsert('sys_menu_sets', array($aSet), false, array('name'));
        $sContentDelete .= $this->oModule->_oDb->getQueryDelete('sys_menu_sets', 'set_name', array($aSet), false);

        $aItems = array();
        $this->oDb->getItems(array('type' => 'by_set_name', 'value' => $aMenu['set_name']), $aItems, false);
        $sContentInsert .= $this->oModule->_oDb->getQueryInsert('sys_menu_items', $aItems);
        $sContentDelete .= $this->oModule->_oDb->getQueryDelete('sys_menu_items', 'set_name', array($aSet), false);

        $aFormStructure = array(
            'form_attrs' => array(),
            'inputs' => array (
                'insert' => array(
                    'type' => 'textarea',
                    'name' => 'insert',
                    'caption' => _t('_bx_dev_nav_txt_menus_export_insert'),
                    'value' => $sContentInsert,
                ),
                'delete' => array(
                    'type' => 'textarea',
                    'name' => 'delete',
                    'caption' => _t('_bx_dev_nav_txt_menus_export_delete'),
                    'value' => $sContentDelete,
                ),
                'done' => array (
                    'type' => 'button',
                    'name' => 'done',
                    'value' => _t('_bx_dev_nav_btn_menus_done'),
                    'attrs' => array(
                        'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                    ),
                )
            )
        );
        $oForm = new BxTemplStudioFormView($aFormStructure);

        $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-nav-menu-export-popup', _t('_bx_dev_nav_txt_menus_export_popup', _t($aMenu['title'])), $this->oModule->_oTemplate->parseHtmlByName('nav_export.html', array(
            'content' => $oForm->getCode()
        )));

        echoJson(array('popup' => $sContent));
    }

    private function fillInSelects(&$aInputs)
    {
        $aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_nav_txt_select_module')), BxDolStudioUtils::getModules());
        $aInputs['set_name']['values'] = array(
            array('key' => '', 'value' => _t('_bx_dev_nav_txt_menus_set_name_select'))
        );

        $aSets = array();
        $this->oDb->getSets(array('type' => 'all'), $aSets, false);
        foreach($aSets as $sSet)
            $aInputs['set_name']['values'][] = array('key' => $sSet['name'], 'value' => _t($sSet['title']));

        $aInputs['template_id']['values'] = array(
            array('key' => '', 'value' => _t('_bx_dev_nav_txt_menus_template_id_select'))
        );

        $aTemplates = array();
        $this->oDb->getTemplates(array('type' => 'all'), $aTemplates, false);
        foreach($aTemplates as $aTemplate)
            $aInputs['template_id']['values'][] = array('key' => $aTemplate['id'], 'value' => _t($aTemplate['title']));
    }
}
/** @} */
