<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */

bx_import('BxDolStudioUtils');
bx_import('BxDolStudioBuilderPage');
bx_import('BxTemplStudioFormView');

class BxBaseStudioBuilderPage extends BxDolStudioBuilderPage
{
    protected $sParamsDivider = '#';
    protected $sSelectKeyPrefix = 'id-';

    protected $sActionPageCreate = 'page_create';
    protected $sActionPageDelete = 'page_delete';
    protected $sActionPageEdit = 'page_edit';
    protected $sActionBlockCreate = 'block_create';
    protected $sActionBlockEdit = 'block_edit';

    protected $sBaseUrl;
    protected $sTypeUrl;
    protected $sPageUrl;

    protected $aHtmlIds = array(
        'add_popup_id' => 'adm-bp-add-popup',
        'edit_popup_id' => 'adm-bp-edit-popup',
        'uri_field_id' => 'adm-bp-field-uri',
    	'url_field_id' => 'adm-bp-field-url',
        'settings_group_id' => 'adm-bp-settings-group-',
        'settings_groups_id' => 'adm-bp-settings-groups',
        'create_block_popup_id' => 'adm-bp-create-block-popup',
        'edit_block_popup_id' => 'adm-bp-edit-block-popup',
        'block_id' => 'adm-bpb-',
        'block_list_id' => 'adm-bpl-',
        'block_lists_id' => 'adm-bp-block-lists',
        'layout_id' => 'adm-bpl-',
    );

	protected $aPageSettings = array(
		array('name' => 'options', 'title' => '_adm_bp_mi_page_options', 'active' => 1),
        array('name' => 'layout', 'title' => '_adm_bp_mi_page_layout', 'active' => 0),
        array('name' => 'visibility', 'title' => '_adm_bp_mi_page_visibility', 'active' => 0),
        array('name' => 'cache', 'title' => '_adm_bp_mi_page_cache', 'active' => 0),
        array('name' => 'seo', 'title' => '_adm_bp_mi_page_seo', 'active' => 0)
	);

    function __construct($sType = '', $sPage = '')
    {
        parent::__construct($sType, $sPage);

        $this->sBaseUrl = BX_DOL_URL_STUDIO . 'builder_page.php';
        $this->sTypeUrl = $this->sBaseUrl . '?type=%s';
        $this->sPageUrl = $this->sTypeUrl . '&page=%s';
    }

    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('page_layouts.css', 'builder_page.css'));
    }

    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array(
            'jquery-ui/jquery.ui.core.min.js',
            'jquery-ui/jquery.ui.widget.min.js',
            'jquery-ui/jquery.ui.mouse.min.js',
            'jquery-ui/jquery.ui.sortable.min.js',
            'jquery.ui.touch-punch.min.js',
            'jquery.easing.js',
            'jquery.form.min.js',
            'functions.js',
            'builder_page.js'
        ));
    }

    function getPageJsObject()
    {
        return 'oBxDolStudioBuilderPage';
    }

    function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $aMenuItems = array(
            BX_DOL_STUDIO_MODULE_SYSTEM => array(
                'name' => BX_DOL_STUDIO_MODULE_SYSTEM,
                'icon' => 'cog',
            ),
            BX_DOL_STUDIO_MODULE_CUSTOM => array(
                'name' => BX_DOL_STUDIO_MODULE_CUSTOM,
                'icon' => 'wrench',
                'title' => '_adm_bp_cpt_type_' . BX_DOL_STUDIO_MODULE_CUSTOM,
            )
        );

        bx_import('BxDolModuleQuery');
        $aModulesDb = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules'));
        foreach($aModulesDb as $aModuleDb) {
        	$sName = $aModuleDb['name'];

            if(!empty($aMenuItems[$sName]))
                $aMenuItems[$sName] = array_merge($aMenuItems[$sName], $aModuleDb);
            else
                $aMenuItems[$sName] = $aModuleDb;

			if(empty($aMenuItems[$sName]['icon']))
				$aMenuItems[$sName]['icon'] = BxDolStudioUtils::getModuleIcon($aModuleDb, 'menu', false); 
        }

        $aMenu = array();
        foreach($aMenuItems as $aMenuItem)
            $aMenu[] = array(
                'name' => $aMenuItem['name'],
                'icon' => $aMenuItem['icon'],
                'link' =>  sprintf($this->sTypeUrl, $aMenuItem['name']),
                'title' => $aMenuItem['title'],
                'selected' => $aMenuItem['name'] == $this->sType
            );

        return parent::getPageMenu($aMenu);
    }

    function getPageCode($bHidden = false)
    {
        bx_import('BxTemplPage');
        $oPage = BxTemplPage::getObjectInstance($this->sPage);

        $oTemplate = BxDolStudioTemplate::getInstance();
        $sJsObject = $this->getPageJsObject();

        $sContent = "";
        if(($bPage = $this->sPage != '') === true) {
            $aTmplVars = array();
            for($i = 1; $i <= $this->aPageRebuild['layout_cells_number']; $i++) {
                $aBlocks = array();
                $this->oDb->getBlocks(array('type' => 'by_object_cell', 'object' => $this->aPageRebuild['object'], 'cell' => $i), $aBlocks, false);

                $aTmplVarsCell = array('id' => $i, 'bx_repeat:blocks' => array());
                foreach($aBlocks as $aBlock) {
                	list($sIcon, $sIconUrl) = $this->getBlockIcon($aBlock);

                    $aTmplVarsCell['bx_repeat:blocks'][] = array(
                        'html_id' => $this->aHtmlIds['block_id'] . $aBlock['id'],
                        'bx_if:is_inactive' => array(
                            'condition' => (int)$aBlock['active'] == 0,
                            'content' => array()
                        ),
                        'bx_if:show_link' => array(
                            'condition' => true,
                            'content' => array(
                                'onclick' => $sJsObject . ".performAction('block_edit', {id: " . $aBlock['id'] . "})",
                                'title' => $oPage->getBlockTitle($aBlock),
                            )
                        ),
                        'bx_if:show_text' => array(
                            'condition' => false,
                            'content' => array()
                        ),
                        'bx_if:image' => array (
			                'condition' => (bool)$sIconUrl,
			                'content' => array('icon_url' => $sIconUrl),
			            ),
						'bx_if:icon' => array (
			                'condition' => (bool)$sIcon,
			                'content' => array('icon' => $sIcon),
			            ),
                        'module' => $this->getModuleTitle($aBlock['module']),
                        'visible_for' => _t('_adm_bp_txt_visible_for', BxDolStudioUtils::getVisibilityTitle($aBlock['visible_for_levels'])),
                        'bx_if:show_checkbox' => array(
                            'condition' => false,
                            'content' => array()
                        ),
                        'bx_if:show_drag_handle' => array(
                            'condition' => true,
                            'content' => array()
                        )
                    );
                }

                $aTmplVars['cell_' . $i] = $oTemplate->parseHtmlByName('bp_cell.html', $aTmplVarsCell);
            }

            $sContent = $oTemplate->parseHtmlByName($this->aPageRebuild['layout_template'], $aTmplVars);
        }

        bx_import('BxDolStudioLanguagesUtils');
        $aLanguages = BxDolStudioLanguagesUtils::getInstance()->getLanguages();

        $aTmplVars = array(
            'js_object' => $sJsObject,
            'action_url' => $this->sBaseUrl,
            'page_url' => sprintf($this->sPageUrl, '{0}', '{1}'),
            'type' => $this->sType,
            'page' => $this->sPage,
            'html_ids' => json_encode($this->aHtmlIds),
            'languahes' => json_encode($aLanguages),
            'bx_repeat:blocks' => array(
                array(
                    'caption' => '',
                    'panel_top' => $this->getBlockPanelTop(),
                    'items' => $sContent,
                    'panel_bottom' => ''
                )
            )
        );

        $oTemplate->addJsTranslation('_adm_bp_wrn_page_delete');
        return $oTemplate->parseHtmlByName('builder_page.html', $aTmplVars);
    }

    function getBlockPanelTop($aParams = array())
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        return parent::getBlockPanelTop(
            array('panel_top' => $oTemplate->parseHtmlByName('bp_block_panel_top.html', $this->_getTmplVarsBlockPanelTop()))
        );
    }

    protected function actionPageCreate()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sModule = BX_DOL_STUDIO_MODULE_CUSTOM;

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-bp-page-create',
                'action' => sprintf($this->sPageUrl, $this->sType, $this->sPage) . '&bp_action=' . $this->sActionPageCreate,
                'method' => 'post'
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_objects_page',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                'module' => array(
                    'type' => 'hidden',
                    'name' => 'module',
                    'value' => $sModule,
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'deletable' => array(
                    'type' => 'hidden',
                    'name' => 'deletable',
                    'value' => 1,
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'settings' => array(
                    'type' => 'custom',
                    'name' => 'settings',
                    'content' => $oTemplate->parseHtmlByName('bp_edit_page_form.html', $this->_getTmplVarsPageSettings()),
                ),
			)
		);

		$oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
        	unset($oForm->aInputs['settings']);
            foreach($this->aPageSettings as $aSetting)
                $oForm->aInputs = array_merge($oForm->aInputs, $this->{'getSettings' . $this->getClassName($aSetting['name']) . 'Fields'}());

        	bx_import('BxDolStudioLanguagesUtils');
            $sLanguage = BxDolStudioLanguagesUtils::getInstance()->getCurrentLangName(false);

            $sObject = BxDolForm::getSubmittedValue('title-' . $sLanguage, $aForm['form_attrs']['method']);
            $sObject = uriGenerate($sObject, 'sys_objects_page', 'object', 'object');

            $sUri = $oForm->getCleanValue('uri');

            $iVisibleFor = BxDolStudioUtils::getVisibilityValue($oForm->getCleanValue('visible_for'), $oForm->getCleanValue('visible_for_levels'));
            BxDolForm::setSubmittedValue('visible_for_levels', $iVisibleFor, $aForm['form_attrs']['method']);
            unset($oForm->aInputs['visible_for']);

            $iId = (int)$oForm->insert(array('object' => $sObject, 'url' => $this->sPageBaseUrl . $sUri));
            if($iId != 0)
                return array('eval' => $this->getPageJsObject() . '.onCreatePage(\'' . $sModule . '\', \'' . $sObject . '\')');
            else
                return array('msg' => _t('_adm_bp_err_page_create'));
        }

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->aHtmlIds['add_popup_id'], _t('_adm_bp_txt_create_popup'), $oTemplate->parseHtmlByName('bp_add_page.html', array(
            'form_id' => $aForm['form_attrs']['id'],
            'form' => $oForm->getCode(true)
        )));

        return array('popup' => $sContent);
    }

    protected function actionPageEdit()
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-bp-page-edit',
                'action' => sprintf($this->sPageUrl, $this->sType, $this->sPage) . '&bp_action=' . $this->sActionPageEdit,
                'method' => 'post'
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_objects_page',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                'settings' => array(
                    'type' => 'custom',
                    'name' => 'settings',
                    'content' => $oTemplate->parseHtmlByName('bp_edit_page_form.html', $this->_getTmplVarsPageSettings($this->aPageRebuild, false)),
                ),
            )
        );

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            unset($oForm->aInputs['settings']);
            foreach($this->aPageSettings as $aSetting)
                $oForm->aInputs = array_merge($oForm->aInputs, $this->{'getSettings' . $this->getClassName($aSetting['name']) . 'Fields'}($this->aPageRebuild, false));

            $iVisibleFor = BxDolStudioUtils::getVisibilityValue($oForm->getCleanValue('visible_for'), $oForm->getCleanValue('visible_for_levels'));
            BxDolForm::setSubmittedValue('visible_for_levels', $iVisibleFor, $aForm['form_attrs']['method']);
            unset($oForm->aInputs['visible_for']);

            if($oForm->update($this->aPageRebuild['id'])) {
                $iLevelId = $oForm->getCleanValue('layout_id');
                if(!empty($iLevelId) && $iLevelId != $this->aPageRebuild['layout_id']) {
                    $aLayoutOld = array();
                    $this->oDb->getLayouts(array('type' => 'by_id', 'value' => $this->aPageRebuild['layout_id']), $aLayoutOld, false);

                    $aLayoutNew = array();
                    $this->oDb->getLayouts(array('type' => 'by_id', 'value' => $iLevelId), $aLayoutNew, false);

                    if($aLayoutOld['cells_number'] > $aLayoutNew['cells_number'] && $this->oDb->resetBlocksByPage($this->sPage, $aLayoutNew['cells_number']) === false)
                        return array('msg' => _t('_adm_bp_err_save'));

                    return array('eval' => $sJsObject . '.onSaveSettings()');
                }

                return array();
            } else
                return array('msg' => _t('_adm_bp_err_save'));
        }

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->aHtmlIds['edit_popup_id'], _t('_adm_bp_txt_settings_popup'), $oTemplate->parseHtmlByName('bp_edit_page.html', array(
            'form_id' => $aForm['form_attrs']['id'],
            'form' => $oForm->getCode(true)
        )));

        return array('popup' => $sContent);
    }

    protected function actionPageDelete()
    {
        if(empty($this->sPage) || empty($this->aPageRebuild) || !is_array($this->aPageRebuild))
            return array('msg' => _t('_adm_bp_err_page_delete'));

        bx_import('BxDolStudioLanguagesUtils');
        $oLangauge = BxDolStudioLanguagesUtils::getInstance();

        bx_import('BxDolStorage');
        $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);

        $aBlocks = array();
        $this->oDb->getBlocks(array('type' => 'by_object', 'value' => $this->sPage), $aBlocks, false);
        if(is_array($aBlocks) && !empty($aBlocks)) {
            foreach($aBlocks as $aBlock)
                $this->onBlockDelete($aBlock);

            $this->oDb->deleteBlocks(array('type' => 'by_object', 'value' => $this->sPage));
        }

        if($this->oDb->deletePages(array('type' => 'by_object', 'value' => $this->sPage))) {
            $oLangauge->deleteLanguageString($this->aPageRebuild['title_system']);
            $oLangauge->deleteLanguageString($this->aPageRebuild['title']);
            return array('eval' => 'window.parent.location.href = "' . sprintf($this->sTypeUrl, $this->sType) . '";');
        }

        return array('msg' => _t('_adm_bp_err_page_delete'));
    }

    protected function actionBlockList()
    {
        $sModule = BX_DOL_STUDIO_BP_TYPE_DEFAULT;
        if(bx_get('bp_module') !== false)
            $sModule = bx_process_input(bx_get('bp_module'));

        return array(
            'content' => $this->getBlockList($sModule)
        );
    }

    protected function actionBlockCreate()
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sSelected = BX_DOL_STUDIO_BP_SKELETONS;

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-bp-block-create',
                'action' => sprintf($this->sPageUrl, $this->sType, $this->sPage) .  '&bp_action=' . $this->sActionBlockCreate,
                'method' => 'post'
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_pages_blocks',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                'blocks' => array(
                    'type' => 'custom',
                    'name' => 'blocks',
                    'content' => '',
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
            )
        );

        $aMenu = array(
            BX_DOL_STUDIO_BP_SKELETONS => array(
                'name' => BX_DOL_STUDIO_BP_SKELETONS,
                'icon' => 'qrcode',
                'title' => '_sys_block_types_skeletons',
                'selected' => $sSelected == BX_DOL_STUDIO_BP_SKELETONS,
            ),
            BX_DOL_STUDIO_MODULE_SYSTEM => array(
                'name' => BX_DOL_STUDIO_MODULE_SYSTEM,
                'icon' => 'cog',
                'title' => '_sys_block_types_system',
                'selected' => $sSelected == BX_DOL_STUDIO_MODULE_SYSTEM,
            ),
            BX_DOL_STUDIO_MODULE_CUSTOM => array(
                'name' => BX_DOL_STUDIO_MODULE_CUSTOM,
                'icon' => 'wrench',
                'title' => '_sys_block_types_custom',
                'selected' => $sSelected == BX_DOL_STUDIO_MODULE_CUSTOM,
            )
        );

        bx_import('BxDolModuleQuery');
        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules'));
        $aModulesWithBlocks = $this->oDb->getModulesWithCopyableBlocks();
        foreach($aModules as $aModule) {
        	$sName = $aModule['name'];
        	if(!in_array($sName, $aModulesWithBlocks))
        		continue;

            if(!empty($aMenu[$sName]))
                $aMenu[$sName] = array_merge($aMenu[$sName], $aModule);
            else
                $aMenu[$sName] = $aModule;
                
			if(empty($aMenu[$sName]['icon']))
				$aMenu[$sName]['icon'] = BxDolStudioUtils::getModuleIcon($aModule, 'menu', false); 
        }

        foreach($aMenu as $sKey => $aItem)
            $aMenu[$sKey]['onclick'] =  $sJsObject . '.onChangeModule(\'' . $aItem['name'] . '\', this);';

        bx_import('BxTemplStudioMenu');
        $oMenu = new BxTemplStudioMenu(array('template' => 'menu_side.html', 'menu_items' => $aMenu));

        $aTmplParams = array(
            'menu' => $oMenu->getCode(),
            'html_block_lists_id' => $this->aHtmlIds['block_lists_id'],
            'blocks' => $this->getBlockList($sSelected)
        );

        $aForm['inputs']['blocks']['content'] = $oTemplate->parseHtmlByName('bp_add_block_form.html', $aTmplParams);

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aIds = $oForm->getCleanValue('blocks');

            $aBlocks = array();
            $this->oDb->getBlocks(array('type' => 'by_ids', 'value' => $aIds), $aBlocks, false);

            bx_import('BxDolStudioLanguagesUtils');
            $oLanguage = BxDolStudioLanguagesUtils::getInstance();

            bx_import('BxDolStorage');
            $oStorege = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);

            $bResult = true;
            foreach($aBlocks as $aBlock) {
                $sTitleKey = $this->getSystemName($aBlock['title'] . '_' . time());
                $sTitleValue = _t($aBlock['title']);

                unset($aBlock['id']);
                $aBlock['object'] = $this->sPage;
                $aBlock['cell_id'] = 1;
                $aBlock['module'] = $this->getBlockModule($aBlock);
                $aBlock['title'] = $sTitleKey;
				$aBlock['copyable'] = 0;
                $aBlock['deletable'] = 1;

                //--- Process Lang copy
                $sContentKey = $sContentValue = "";
                if($aBlock['type'] == BX_DOL_STUDIO_BP_BLOCK_LANG && $aBlock['content'] != '') {
                    $sContentKey = $this->getSystemName($aBlock['content'] . '_' . time());
                    $sContentValue = _t($aBlock['content']);

                    $aBlock['content'] = $sContentKey;
                    $oLanguage->addLanguageString($sContentKey, $sContentValue);
                }

                //--- Process Image copy
                $iImageId = $sImageAlign = "";
                if($aBlock['type'] == BX_DOL_STUDIO_BP_BLOCK_IMAGE && $aBlock['content'] != '') {
                    list($iImageId, $sImageAlign) = explode($this->sParamsDivider, $aBlock['content']);

                    $aBlock['content'] = "";
                    if(is_numeric($iImageId) && (int)$iImageId != 0 && ($iImageId = $oStorege->storeFileFromStorage(array('id' => $iImageId))) !== false)
                        $aBlock['content'] = implode($this->sParamsDivider, array($iImageId, $sImageAlign));
                }

                if(!$this->oDb->insertBlock($aBlock)) {
                    if($sContentKey != "")
                        $oLanguage->deleteLanguageString($sContentKey);

                    if($iImageId != "")
                        $oStorege->deleteFile((int)$iImageId, 0);

                    $bResult = false;
                    break;
                }

                $oLanguage->addLanguageString($sTitleKey, $sTitleValue);
            }

            if($bResult)
                return array('eval' => $sJsObject . '.onCreateBlock(oData)');
            else
                return array('msg' => _t('_adm_bp_err_block_added'));
        }

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->aHtmlIds['create_block_popup_id'], _t('_adm_bp_txt_new_block_popup'), $oTemplate->parseHtmlByName('bp_add_block.html', array(
        	'action' => 'create',
            'form_id' => $aForm['form_attrs']['id'],
            'form' => $oForm->getCode(true)
        )));

        return array('popup' => $sContent);
    }

    protected function actionBlockEdit()
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $iId = (int)bx_get('id');
        if(!$iId)
            return array();

        $aBlock = array();
        $this->oDb->getBlocks(array('type' => 'by_id', 'value' => $iId), $aBlock, false);
        if(empty($aBlock) || !is_array($aBlock))
            return array('msg' => _t('_adm_bp_err_block_not_found'));

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-bp-block-edit',
                'action' => sprintf($this->sPageUrl, $this->sType, $this->sPage) . '&bp_action=' . $this->sActionBlockEdit,
                'method' => 'post'
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_pages_blocks',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                'id' => array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'value' => $iId,
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'title' => array(
                    'type' => 'text_translatable',
                    'name' => 'title',
                    'caption' => _t('_adm_bp_txt_block_title'),
                    'info' => _t('_adm_bp_dsc_block_title'),
                    'value' => $aBlock['title'],
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,100),
                        'error' => _t('_adm_bp_err_block_title'),
                    ),
                ),
                'designbox_id' => array(
                    'type' => 'select',
                    'name' => 'designbox_id',
                    'caption' => _t('_adm_bp_txt_block_designbox'),
                    'info' => '',
                    'value' => $this->sSelectKeyPrefix . $aBlock['designbox_id'],
                    'values' => array(
                        array('key' => '', 'value' => _t('_adm_bp_txt_block_designbox_empty')),
                    ),
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'avail',
                        'params' => array(),
                        'error' => _t('_adm_bp_err_block_designbox'),
                    ),
                ),
                'visible_for' => array(
                    'type' => 'select',
                    'name' => 'visible_for',
                    'caption' => _t('_adm_bp_txt_block_visible_for'),
                    'info' => '',
                    'value' => $aBlock['visible_for_levels'] == BX_DOL_INT_MAX ? BX_DOL_STUDIO_VISIBLE_ALL : BX_DOL_STUDIO_VISIBLE_SELECTED,
                    'values' => array(
                        array('key' => BX_DOL_STUDIO_VISIBLE_ALL, 'value' => _t('_adm_bp_txt_block_visible_for_all')),
                        array('key' => BX_DOL_STUDIO_VISIBLE_SELECTED, 'value' => _t('_adm_bp_txt_block_visible_for_selected')),
                    ),
                    'required' => '0',
                    'attrs' => array(
                        'onchange' => $this->getPageJsObject() . '.onChangeVisibleFor(this)'
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    )
                ),
                'visible_for_levels' => array(
                    'type' => 'checkbox_set',
                    'name' => 'visible_for_levels',
                    'caption' => _t('_adm_bp_txt_block_visible_for_levels'),
                    'info' => _t('_adm_bp_dsc_block_visible_for_levels'),
                    'value' => array(),
                    'values' => array(),
                    'tr_attrs' => array(
                        'style' => $aBlock['visible_for_levels'] == BX_DOL_INT_MAX ? 'display:none' : ''
                    ),
                    'db' => array (
                        'pass' => 'Int',
                    )
                ),
                'active' => array(
                    'type' => 'switcher',
                    'name' => 'active',
                    'caption' => _t('_adm_bp_txt_block_active'),
                    'info' => '',
                    'value' => '1',
                    'checked' => $aBlock['active'] == '1',
                    'db' => array (
                        'pass' => 'Int',
                    )
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_bp_btn_block_save'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_bp_btn_block_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );

        $aDBoxes = array();
        $this->oDb->getDesignBoxes(array('type' => 'ordered'), $aDBoxes, false);
        foreach($aDBoxes as $aDBox)
            $aForm['inputs']['designbox_id']['values'][] = array('key' => $this->sSelectKeyPrefix . $aDBox['id'], 'value' => _t($aDBox['title']));

        BxDolStudioUtils::getVisibilityValues($aBlock['visible_for_levels'], $aForm['inputs']['visible_for_levels']['values'], $aForm['inputs']['visible_for_levels']['value']);

        $aForm['inputs'] = $this->addInArray($aForm['inputs'], 'visible_for_levels', $this->getBlockContent($aBlock));

        if((int)$aBlock['deletable'] != 0)
            $aForm['inputs']['controls'][] = array (
                'type' => 'reset',
                'name' => 'close',
                'value' => _t('_adm_bp_btn_block_delete'),
                'attrs' => array(
                    'onclick' => $this->getPageJsObject() . ".deleteBlock(" . $aBlock['id'] . ")",
                    'class' => 'bx-def-margin-sec-left',
                ),
            );

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $this->onSaveBlock($oForm, $aBlock);

            if($oForm->update($iId) !== false)
                return array('eval' => $sJsObject . '.onEditBlock(oData)');
            else
                return array('msg' => _t('_adm_bp_err_block_edit'));
        }

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->aHtmlIds['edit_block_popup_id'], _t('_adm_bp_txt_edit_block_popup', _t($aBlock['title'])), $oTemplate->parseHtmlByName('bp_add_block.html', array(
        	'action' => 'edit',
            'form_id' => $aForm['form_attrs']['id'],
            'form' => $oForm->getCode(true)
        )));

        return array('popup' => $sContent);
    }

    protected function actionBlockDelete()
    {
        $sJsObject = $this->getPageJsObject();
        $iId = (int)bx_get('id');

        $aBlock = array();
        $this->oDb->getBlocks(array('type' => 'by_id', 'value' => $iId), $aBlock, false);
        if(empty($aBlock) || !is_array($aBlock))
            return array('msg' => _t('_adm_bp_err_block_not_found'));

        if((int)$aBlock['deletable'] == 0)
            return array('msg' => _t('_adm_bp_err_block_not_deletable'));

        if(!$this->oDb->deleteBlocks(array('type' => 'by_id', 'value' => $iId)))
            return array('msg' => _t('_adm_bp_err_block_delete'));

        $this->onBlockDelete($aBlock);
        return array('eval' => $sJsObject . '.onDeleteBlock(' . $iId . ', oData)');
    }

    protected function actionImageDelete()
    {
        $sJsObject = $this->getPageJsObject();
        $iId = (int)bx_get('id');

        $aBlock = array();
        $this->oDb->getBlocks(array('type' => 'by_id', 'value' => $iId), $aBlock, false);
        if(empty($aBlock) || !is_array($aBlock))
            return array('msg' => _t('_adm_bp_err_block_not_found'));

        $iImageId = $sImageAlign = '';
        if($aBlock['content'] != '')
            list($iImageId, $sImageAlign) = explode($this->sParamsDivider, $aBlock['content']);

        if(is_numeric($iImageId) && (int)$iImageId != 0) {
            bx_import('BxDolStorage');
            if(!BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES)->deleteFile((int)$iImageId, 0))
                return array('msg' => _t('_adm_bp_err_block_content_image_preview_delete'));

            $this->oDb->updateBlock($iId, array('content' => ''));
        }

        return $this->actionBlockEdit();
    }

    protected function actionUriGet()
    {
        bx_import('BxDolStudioLanguagesUtils');
        $oLanguage = BxDolStudioLanguagesUtils::getInstance();
        $aLanguages = $oLanguage->getLanguages();

        $sLanguageDef = $oLanguage->getDefaultLangName();
        $sLanguageCur = $oLanguage->getCurrentLangName(false);

        $aValues = array();
        foreach($aLanguages as $sName => $sTitle)
            if(($mixedValue = bx_get($sName)) !== false)
                $aValues[$sName] = bx_process_input($mixedValue);

        $sUri = "";
        if(($mixedValue = bx_get('uri')) !== false)
        	$sUri = bx_process_input($mixedValue);
        else if(array_key_exists('en', $aValues) && $aValues['en'] != '')
            $sUri = $aValues['en'];
        else if(array_key_exists($sLanguageDef, $aValues) && $aValues[$sLanguageDef] != '')
            $sUri = $aValues[$sLanguageDef];
        else if(array_key_exists($sLanguageCur, $aValues) && $aValues[$sLanguageCur] != '')
            $sUri = $aValues[$sLanguageCur];
        else if(count($aValues) > 0)
            foreach($aValues as $sValue)
                if(!empty($sValue)) {
                    $sUri = $sValue;
                    break;
                }

        $sUri = $sUri != "" ? uriGenerate($sUri, 'sys_objects_page', 'uri') : "";

        bx_import('BxDolPermalinks');
        $sUrl = BxDolPermalinks::getInstance()->permalink($this->sPageBaseUrl . $sUri);

        return array('eval' => $this->getPageJsObject() . '.onGetUri(oData)', 'uri' => $sUri, 'url' => $sUrl);
    }

    protected function getSettingsOptions($aPage = array(), $bCreate = true, $bInputsOnly = false)
    {
    	bx_import('BxDolPermalinks');

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-bp-settings-seo',
            ),
            'params' => array (
                'remove_form' => '1',
                'csrf' => array(
                    'disable' => true
                )
            ),
            'inputs' => array (
                'title_system' => array(
                    'type' => 'text_translatable',
                    'name' => 'title_system',
                    'caption' => _t('_adm_bp_txt_page_title_system'),
                    'info' => _t('_adm_bp_dsc_page_title_system'),
                    'value' => isset($aPage['title_system']) ? $aPage['title_system'] : '',
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,100),
                        'error' => _t('_adm_bp_err_page_title_system'),
                    ),
                ),
                'title' => array(
                    'type' => 'text_translatable',
                    'name' => 'title',
                    'caption' => _t('_adm_bp_txt_page_title'),
                    'info' => _t('_adm_bp_dsc_page_title'),
                    'value' => isset($aPage['title']) ? $aPage['title'] : '',
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,100),
                        'error' => _t('_adm_bp_err_page_title'),
                    ),
                ),
                'url' => array(
					'type' => 'text',
					'name' => 'url',
					'caption' => _t('_adm_bp_txt_page_url'),
					'info' => _t('_adm_bp_dsc_page_url'),
					'value' => isset($aPage['url']) ? BxDolPermalinks::getInstance()->permalink($aPage['url']) : '',
					'required' => '0',
					'attrs' => array(
                		'id' => $this->aHtmlIds['url_field_id'],
						'disabled' => 'disabled'
					),
				)
            )
        );

        if($bCreate) {
        	$sJsObject = $this->getPageJsObject();

        	$aForm['inputs']['title']['attrs']['onblur'] = $sJsObject . '.getUri(this);';

        	$aForm['inputs'] = bx_array_insert_before(array(
        		'uri' => array(
					'type' => 'text',
					'name' => 'uri',
					'caption' => _t('_adm_bp_txt_page_uri'),
					'info' => _t('_adm_bp_dsc_page_uri'),
					'value' => '',
					'required' => '1',
        			'attrs' => array(
						'id' => $this->aHtmlIds['uri_field_id'],
						'onblur' => $sJsObject . '.getUri(this);'
					),
					'db' => array (
						'pass' => 'Xss',
					),
					'checker' => array (
						'func' => 'length',
						'params' => array(3,100),
					    'error' => _t('_adm_bp_err_page_uri'),
					),
				)
        	), $aForm['inputs'], 'url');
        }

        if($bInputsOnly)
            return $aForm['inputs'];

        $oForm = new BxTemplStudioFormView($aForm);
        return $oForm->getCode();
    }

    protected function getSettingsOptionsFields($aPage = array(), $bCreate = true, $bInputsOnly = false)
    {
    	return $this->getSettingsOptions($aPage, $bCreate, true);
    }

    protected function getSettingsLayout($aPage = array(), $bCreate = true, $bInputsOnly = false)
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $iLayout = isset($aPage['layout_id']) ? (int)$aPage['layout_id'] : 5;

        $aLayouts = array();
        $this->oDb->getLayouts(array('type' => 'all'), $aLayouts, false);

        $aTmplParams = array(
            'bx_repeat:layouts' => array(),
            'form' => ''
        );

        foreach($aLayouts as $aLayout)
            $aTmplParams['bx_repeat:layouts'][] = array(
                'id' => $aLayout['id'],
                'html_id' => $this->aHtmlIds['layout_id'] . $aLayout['id'],
                'js_object' => $sJsObject,
                'bx_if:active' => array(
                    'condition' => (int)$aLayout['id'] == $iLayout,
                    'content' => array()
                ),
                'icon' => $oTemplate->getImageUrl($aLayout['icon']),
                'title' => _t($aLayout['title']),
            );

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-bp-settings-seo',
            ),
            'params' => array (
                'remove_form' => '1',
                'csrf' => array(
                    'disable' => true
                )
            ),
            'inputs' => array (
                'layout_id' => array(
                    'type' => 'hidden',
                    'name' => 'layout_id',
                    'value' => $iLayout,
                    'db' => array (
                        'pass' => 'Int',
                    ),
                )
            )
        );

        if($bInputsOnly)
            return $aForm['inputs'];

        $oForm = new BxTemplStudioFormView($aForm);
        $aTmplParams['form'] = $oForm->getCode();

        return $oTemplate->parseHtmlByName('bp_layouts.html', $aTmplParams);
    }

    protected function getSettingsLayoutFields($aPage = array(), $bCreate = true)
    {
    	return $this->getSettingsLayout($aPage, $bCreate, true);
    }

    protected function getSettingsVisibility($aPage = array(), $bCreate = true, $bInputsOnly = false)
    {
    	$iVisibleForLevels = isset($aPage['visible_for_levels']) ? (int)$aPage['visible_for_levels'] : BX_DOL_INT_MAX;

        if(isset($aPage['visible_for_levels_editable']) && (int)$aPage['visible_for_levels_editable'] == 0)
            $aInputs = array(
                'visible_for_levels' => array(
                    'type' => 'custom',
                    'name' => 'visible_for_levels',
                    'content' => MsgBox(_t('_adm_bp_err_page_visible_for_levels'))
                )
            );
        else
            $aInputs = array(
                'visible_for' => array(
                    'type' => 'select',
                    'name' => 'visible_for',
                    'caption' => _t('_adm_bp_txt_block_visible_for'),
                    'info' => '',
                    'value' => $iVisibleForLevels == BX_DOL_INT_MAX ? BX_DOL_STUDIO_VISIBLE_ALL : BX_DOL_STUDIO_VISIBLE_SELECTED,
                    'values' => array(
                        array('key' => BX_DOL_STUDIO_VISIBLE_ALL, 'value' => _t('_adm_bp_txt_block_visible_for_all')),
                        array('key' => BX_DOL_STUDIO_VISIBLE_SELECTED, 'value' => _t('_adm_bp_txt_block_visible_for_selected')),
                    ),
                    'required' => '0',
                    'attrs' => array(
                        'onchange' => $this->getPageJsObject() . '.onChangeVisibleFor(this)'
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    )
                ),
                'visible_for_levels' => array(
                    'type' => 'checkbox_set',
                    'name' => 'visible_for_levels',
                    'caption' => _t('_adm_bp_txt_page_visible_for_levels'),
                    'info' => _t('_adm_bp_dsc_page_visible_for_levels'),
                    'value' => '',
                    'values' => array(),
                    'tr_attrs' => array(
                        'style' => $iVisibleForLevels == BX_DOL_INT_MAX ? 'display:none' : ''
                    ),
                    'db' => array (
                        'pass' => 'Int',
                    )
                )
            );

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-bp-settings-visibility',
            ),
            'params' => array (
                'remove_form' => '1',
                'csrf' => array(
                    'disable' => true
                )
            ),
            'inputs' => $aInputs
        );

        BxDolStudioUtils::getVisibilityValues($iVisibleForLevels, $aForm['inputs']['visible_for_levels']['values'], $aForm['inputs']['visible_for_levels']['value']);

        if($bInputsOnly)
            return $aForm['inputs'];       	

        $oForm = new BxTemplStudioFormView($aForm);
        return $oForm->getCode();
    }

    protected function getSettingsVisibilityFields($aPage = array(), $bCreate = true)
    {
    	return $this->getSettingsVisibility($aPage = array(), $bCreate, true);
    }

    protected function getSettingsCache($aPage = array(), $bCreate = true, $bInputsOnly = false)
    {
        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-bp-settings-cache',
            ),
            'params' => array (
                'remove_form' => '1',
                'csrf' => array(
                    'disable' => true
                )
            ),
            'inputs' => array (
                'cache_lifetime' => array(
                    'type' => 'text',
                    'name' => 'cache_lifetime',
                    'caption' => _t('_adm_bp_txt_page_cache_lifetime'),
                    'info' => _t('_adm_bp_dsc_page_cache_lifetime'),
                    'value' => isset($aPage['cache_lifetime']) ? $aPage['cache_lifetime'] : 0,
                    'required' => '',
                    'attrs' => array(
                        'disabled' => isset($aPage['cache_editable']) && (int)$aPage['cache_editable'] == 0 ? 'disabled' : ''
                    ),
                    'db' => array (
                        'pass' => 'Int',
                    )
                ),
            )
        );

        if($bInputsOnly)
            return $aForm['inputs'];

        $oForm = new BxTemplStudioFormView($aForm);
        return $oForm->getCode();
    }

    protected function getSettingsCacheFields($aPage = array(), $bCreate = true)
    {
    	return $this->getSettingsCache($aPage, $bCreate, true);
    }

    protected function getSettingsSeo($aPage = array(), $bCreate = true, $bInputsOnly = false)
    {
        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-bp-settings-seo',
            ),
            'params' => array (
                'remove_form' => '1',
                'csrf' => array(
                    'disable' => true
                )
            ),
            'inputs' => array (
                'meta_description' => array(
                    'type' => 'textarea',
                    'name' => 'meta_description',
                    'caption' => _t('_adm_bp_txt_page_meta_description'),
                    'info' => _t('_adm_bp_dsc_page_meta_description'),
                    'value' => isset($aPage['meta_description']) ? $aPage['meta_description'] : '',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'meta_keywords' => array(
                    'type' => 'textarea',
                    'name' => 'meta_keywords',
                    'caption' => _t('_adm_bp_txt_page_meta_keywords'),
                    'info' => _t('_adm_bp_dsc_page_meta_keywords'),
                    'value' => isset($aPage['meta_keywords']) ? $aPage['meta_keywords'] : '',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'meta_robots' => array(
                    'type' => 'text',
                    'name' => 'meta_robots',
                    'caption' => _t('_adm_bp_txt_page_meta_robots'),
                    'info' => _t('_adm_bp_dsc_page_meta_robots'),
                    'value' => isset($aPage['meta_robots']) ? $aPage['meta_robots'] : '',
                    'required' => '0',
                    'db' => array (
                        'pass' => 'Xss',
                    )
                )
            )
        );

        if($bInputsOnly)
            return $aForm['inputs'];

        $oForm = new BxTemplStudioFormView($aForm);
        return $oForm->getCode();
    }

    protected function getSettingsSeoFields($aPage = array(), $bCreate = true)
    {
    	return $this->getSettingsSeo($aPage, $bCreate, true);
    }

    protected function getBlockIcon($aBlock)
    {
        $sIcon = $sIconUrl = "";

        $sResult = '';
        switch($aBlock['type']) {
            case BX_DOL_STUDIO_BP_BLOCK_RAW:
            	$sResult = 'file-text-o';
            	break;

            case BX_DOL_STUDIO_BP_BLOCK_HTML:
            	$sResult = 'code';
            	break;

            case BX_DOL_STUDIO_BP_BLOCK_RSS:
            	$sResult = 'rss';
            	break;

            case BX_DOL_STUDIO_BP_BLOCK_IMAGE:
            	$sResult = 'picture-o';
            	break;

            case BX_DOL_STUDIO_BP_BLOCK_LANG:
            	$sResult = 'globe';
            	break;

            case BX_DOL_STUDIO_BP_BLOCK_MENU:
            	$sResult = 'list-alt';
            	break;

            case BX_DOL_STUDIO_BP_BLOCK_SERVICE:
                $sResult = $this->getModuleIcon($aBlock['module'], 'page');
                break;
        }

        if(strpos($sResult, '.') === false)
			$sIcon = $sResult;
		else
        	$sIconUrl = $sResult;

        return array($sIcon, $sIconUrl);
    }

    protected function getBlockModule($aBlock)
    {
        return  $aBlock['module'] != BX_DOL_STUDIO_BP_SKELETONS ? $aBlock['module'] : BX_DOL_STUDIO_MODULE_CUSTOM;
    }

    protected function getBlockContent($aBlock)
    {
        $aFields = array();

        switch($aBlock['type']) {
            case BX_DOL_STUDIO_BP_BLOCK_RAW:
                $aFields = array(
                    'content' => array(
                        'type' => 'textarea',
                        'name' => 'content',
                        'caption' => _t('_adm_bp_txt_block_content_raw'),
                        'info' => _t('_adm_bp_dsc_block_content_raw'),
                        'value' => $aBlock['content'],
                        'required' => '0',
                        'db' => array (
                            'pass' => 'Xss',
                        ),
                    ),
                );
                break;

            case BX_DOL_STUDIO_BP_BLOCK_HTML:
                $aFields = array(
                    'content' => array(
                        'type' => 'textarea',
                        'name' => 'content',
                        'caption' => _t('_adm_bp_txt_block_content_raw'),
                        'info' => _t('_adm_bp_dsc_block_content_raw'),
                        'value' => $aBlock['content'],
                        'required' => '0',
                        'html' => 1,
                        'db' => array (
                            'pass' => 'XssHtml',
                        ),
                    ),
                );
                break;

            case BX_DOL_STUDIO_BP_BLOCK_LANG:
                $aFields = array(
                    'content' => array(
                        'type' => 'textarea',
                        'name' => 'content',
                        'caption' => _t('_adm_bp_txt_block_content_lang'),
                        'info' => _t('_adm_bp_dsc_block_content_lang'),
                        'value' => $aBlock['content'] != '' ? _t($aBlock['content']) : '',
                        'required' => '0',
                        'html' => 3,
                        'db' => array (
                            'pass' => 'XssHtml',
                        ),
                    ),
                );
                break;

            case BX_DOL_STUDIO_BP_BLOCK_IMAGE:
                $iImageId = $sImageAlign = '';
                if($aBlock['content'] != '')
                    list($iImageId, $sImageAlign) = explode($this->sParamsDivider, $aBlock['content']);

                $aFields = array(
                    'content' => array(
                        'type' => 'hidden',
                        'name' => 'content',
                        'value' => '',
                        'db' => array (
                            'pass' => 'Xss',
                        ),
                    ),
                    'image_file' => array(
                        'type' => 'image_uploader',
                        'name' => 'image_file',
                        'caption' => _t('_adm_bp_txt_block_content_image_file'),
                        'caption_preview' => _t('_adm_bp_txt_block_content_image_preview'),
                        'info' => _t('_adm_bp_dsc_block_content_image_file'),
                        'ajax_action_delete' => $this->getPageJsObject() . '.deleteBlockImage(' . $aBlock['id'] . ')',
                        'value' => (int)$iImageId
                    ),
                    'image_align' => array(
                        'type' => 'select',
                        'name' => 'image_align',
                        'caption' => _t('_adm_bp_txt_block_content_image_align'),
                        'info' => '',
                        'value' => $sImageAlign,
                        'values' => array(
                            array('key' => '', 'value' => _t('_adm_bp_txt_block_content_image_align_empty')),
                            array('key' => 'left', 'value' => _t('_adm_bp_txt_block_content_image_align_left')),
                            array('key' => 'center', 'value' => _t('_adm_bp_txt_block_content_image_align_center')),
                            array('key' => 'right', 'value' => _t('_adm_bp_txt_block_content_image_align_right')),
                        ),
                        'required' => '0',
                        'db' => array (
                            'pass' => 'Xss',
                        ),
                    ),
                );
                break;

            case BX_DOL_STUDIO_BP_BLOCK_RSS:
                $sRssUrl = $sRssLength = '';
                if($aBlock['content'] != '')
                    list($sRssUrl, $sRssLength) = explode($this->sParamsDivider, $aBlock['content']);

                $aFields = array(
                    'content' => array(
                        'type' => 'hidden',
                        'name' => 'content',
                        'value' => '',
                        'db' => array (
                            'pass' => 'Xss',
                        ),
                    ),
                    'rss_url' => array(
                        'type' => 'text',
                        'name' => 'rss_url',
                        'caption' => _t('_adm_bp_txt_block_content_rss_url'),
                        'info' => _t('_adm_bp_dsc_block_content_rss_url'),
                        'value' => $sRssUrl,
                        'required' => '0',
                        'db' => array (
                            'pass' => 'Xss',
                        ),
                    ),
                    'rss_length' => array(
                        'type' => 'text',
                        'name' => 'rss_length',
                        'caption' => _t('_adm_bp_txt_block_content_rss_length'),
                        'info' => _t('_adm_bp_dsc_block_content_rss_length'),
                        'value' => $sRssLength,
                        'required' => '0',
                        'db' => array (
                            'pass' => 'Int',
                        ),
                    ),
                );
                break;

            case BX_DOL_STUDIO_BP_BLOCK_MENU:
                $aFields = array(
                    'content' => array(
                        'type' => 'select',
                        'name' => 'content',
                        'caption' => _t('_adm_bp_txt_block_content_menu'),
                        'info' => '',
                        'value' => $aBlock['content'],
                        'values' => array(),
                        'required' => '0',
                        'db' => array (
                            'pass' => 'Xss',
                        ),
                    )
                );

                $aMenus = $this->oDb->getMenus();
                foreach($aMenus as $sKey => $sValue)
                    $aFields['content']['values'][$sKey] = _t($sValue);

                asort($aFields['content']['values']);
                $aFields['content']['values'] = array_merge(array('' => _t('_adm_bp_txt_block_content_menu_empty')), $aFields['content']['values']);
                break;

            case BX_DOL_STUDIO_BP_BLOCK_SERVICE:
                $aService = array('module' => '', 'method' => '');
                if($aBlock['content'] != '')
                    $aService = unserialize($aBlock['content']);

                $aFields = array(
                    'service_module' => array(
                        'type' => 'value',
                        'name' => 'service_module',
                        'caption' => _t('_adm_bp_txt_block_content_service_module'),
                        'value' => $this->getModuleTitle($aService['module'])
                    ),
                    'service_method' => array(
                        'type' => 'value',
                        'name' => 'service_method',
                        'caption' => _t('_adm_bp_txt_block_content_service_method'),
                        'value' => $aService['method']
                    )
                );
                break;
        }

        return $aFields;
    }

    protected function getBlockList($sModule)
    {
        if(empty($sModule))
            return '';

        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $oForm = new BxTemplStudioFormView(array());

        $aInputCheckbox = array(
            'type' => 'checkbox',
            'name' => 'blocks[]',
            'attrs' => array(
                'onChange' => 'javascript:' . $sJsObject . '.onSelectBlock(this);'
            ),
            'value' => ''
        );

        $aTmplParams = array(
            'html_block_list_id' => $this->aHtmlIds['block_list_id'] . $sModule,
            'bx_repeat:blocks' => array()
        );

        $aBlocks = $this->getBlocks($sModule);
        foreach($aBlocks as $aBlock) {
        	list($sIcon, $sIconUrl) = $this->getBlockIcon($aBlock);

            $aInputCheckbox['value'] = $aBlock['id'];

            $aTmplParams['bx_repeat:blocks'][] = array(
                'js_object' => $sJsObject,
                'html_id' => $this->aHtmlIds['block_id'] . $aBlock['id'],
                'bx_if:is_inactive' => array(
                    'condition' => false,
                    'content' => array()
                ),
                'bx_if:show_link' => array(
                    'condition' => false,
                    'content' => array()
                ),
                'bx_if:show_text' => array(
                    'condition' => true,
                    'content' => array(
                        'title' => _t($aBlock['title']),
                    )
                ),
                'bx_if:image' => array (
	                'condition' => (bool)$sIconUrl,
	                'content' => array('icon_url' => $sIconUrl),
	            ),
				'bx_if:icon' => array (
	                'condition' => (bool)$sIcon,
	                'content' => array('icon' => $sIcon),
	            ),
                'module' => $this->getModuleTitle($aBlock['module']),
                'visible_for' => _t('_adm_bp_txt_visible_for', BxDolStudioUtils::getVisibilityTitle($aBlock['visible_for_levels'])),
                'bx_if:show_checkbox' => array(
                    'condition' => true,
                    'content' => array(
                        'content' => $oForm->genRow($aInputCheckbox)
                    )
                ),
                'bx_if:show_drag_handle' => array(
                    'condition' => false,
                    'content' => array()
                )
            );
        }

        return $oTemplate->parseHtmlByName('bp_blocks_list.html', $aTmplParams);
    }

    protected function onBlockDelete($aBlock)
    {
        bx_import('BxDolStudioLanguagesUtils');
        BxDolStudioLanguagesUtils::getInstance()->deleteLanguageString($aBlock['title']);

        //--- Process Lang block
        if($aBlock['type'] == BX_DOL_STUDIO_BP_BLOCK_LANG && $aBlock['content'] != '')
            BxDolStudioLanguagesUtils::getInstance()->deleteLanguageString($aBlock['content']);

        //--- Process Image block
        if($aBlock['type'] == BX_DOL_STUDIO_BP_BLOCK_IMAGE && $aBlock['content'] != '') {
            $iImageId = $sImageAlign = '';
            list($iImageId, $sImageAlign) = explode($this->sParamsDivider, $aBlock['content']);

            if(is_numeric($iImageId) && (int)$iImageId != 0) {
                bx_import('BxDolStorage');
                BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES)->deleteFile((int)$iImageId, 0);
            }
        }
    }

    protected function getBlocks($sModule)
    {
        $aBlocks = array();
        $this->oDb->getBlocks(array('type' => 'by_module_to_copy', 'value' => $sModule), $aBlocks, false);

        return $aBlocks;
    }

    protected function _getTmplVarsBlockPanelTop()
    {
        $sJsObject = $this->getPageJsObject();

        $oForm = new BxTemplStudioFormView(array());

        $aInputPages = array(
            'type' => 'select',
            'name' => 'page',
            'attrs' => array(
                'onChange' => 'javascript:' . $this->getPageJsObject() . '.onChangePage(this)'
            ),
            'value' => $this->sPage,
            'values' => array(
                array('key' => '', 'value' => _t('_adm_bp_txt_select_page'))
            )
        );

        $aPages = $aCounter = array();
        $this->oDb->getPages(array('type' => 'by_module', 'value' => $this->sType), $aPages, false);
        $this->oDb->getBlocks(array('type' => 'counter_by_pages'), $aCounter, false);
        foreach($aPages as $aPage)
            $aInputPages['values'][] = array('key' => $aPage['object'], 'value' => _t($aPage['title_system']) . " (" . (isset($aCounter[$aPage['object']]) ? $aCounter[$aPage['object']] : "0") . ")");

        $aTmplVarsActions = array();
        if(($this->sPage != '' && !empty($this->aPageRebuild)) !== false)
            $aTmplVarsActions = $this->_getTmplVarsBlockPanelTopActions();

        return array(
            'js_object' => $this->getPageJsObject(),
            'selector' => $oForm->genRow($aInputPages),
            'action_page_create' => $this->sActionPageCreate,
            'bx_if:show_actions' => array(
                'condition' => $this->sPage != '',
                'content' => $aTmplVarsActions
            )
        );
    }

    protected function _getTmplVarsBlockPanelTopActions()
    {
        $sJsObject = $this->getPageJsObject();

        bx_import('BxDolPermalinks');
        $oPermalinks = BxDolPermalinks::getInstance();

        return array(
            'js_object' => $sJsObject,
            'url_view' => BX_DOL_URL_ROOT . $oPermalinks->permalink($this->aPageRebuild['url']),
            'action_page_edit' => $this->sActionPageEdit,
            'bx_if:can_delete' => array(
                'condition' => (int)$this->aPageRebuild['deletable'] == 1,
                'content' => array(
                    'js_object' => $sJsObject
                )
            ),
            'action_block_create' => $this->sActionBlockCreate,
        );
    }

    protected function _getTmplVarsPageSettings($aPage = array(), $bCreate = true)
    {
    	$sJsObject = $this->getPageJsObject();

        $aTmplParams = array(
            'menu' => array(),
            'html_settings_groups_id' => $this->aHtmlIds['settings_groups_id'],
            'bx_repeat:settings_groups' => array(),
        	'submit' => _t($bCreate ? '_adm_bp_btn_page_create' : '_adm_bp_btn_page_apply')
        );
        foreach($this->aPageSettings as $aSetting) {
            //--- get menu items
            $aTmplParams['menu'][$aSetting['name']] = array(
                'name' => $aSetting['name'],
                'icon' => '',
                'onclick' => $sJsObject . '.onChangeSettingGroup(\'' . $aSetting['name'] . '\', this);',
                'title' => $aSetting['title'],
                'selected' => isset($aSetting['active']) && (int)$aSetting['active'] == 1
            );

            //--- get settings
            $aTmplParams['bx_repeat:settings_groups'][] = array(
                'html_settings_group_id' => $this->aHtmlIds['settings_group_id'] .  $aSetting['name'],
                'bx_if:hidden' => array(
                    'condition' => $aSetting['active'] != 1,
                    'content' => array()
                ),
                'content' => $this->{'getSettings' . $this->getClassName($aSetting['name'])}($aPage, $bCreate)
            );
        }

        bx_import('BxTemplStudioMenu');
        $oMenu = new BxTemplStudioMenu(array('template' => 'menu_side.html', 'menu_items' => $aTmplParams['menu']));
        $aTmplParams['menu'] = $oMenu->getCode();

        return $aTmplParams;
    }
}

/** @} */
