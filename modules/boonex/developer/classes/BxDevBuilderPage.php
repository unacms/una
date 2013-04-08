<? defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplStudioBuilderPage');

class BxDevBuilderPage extends BxTemplStudioBuilderPage {
    protected $sActionPageExport = 'page_export';

    protected $oModule;
    protected $aParams;

    function __construct($aParams) {
        parent::__construct(isset($aParams['type']) ? $aParams['type'] : '',  isset($aParams['page']) ? $aParams['page'] : '');

        $this->aParams = $aParams;
        $this->sBaseUrl = $this->aParams['url'];
        $this->sTypeUrl = $this->sBaseUrl . '&bp_type=%s';
        $this->sPageUrl = $this->sTypeUrl . '&bp_page=%s';

        bx_import('BxDolModule');
        $this->oModule = BxDolModule::getInstance('bx_developer');

        $this->oModule->_oTemplate->addStudioCss(array('builder_page.css'));
    }

    function getBlockPanelTop($aParams = array()) {
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

        bx_import('BxDolPermalinks');
        $oPermalinks = BxDolPermalinks::getInstance();

        $aTmplVarsActions = array();
        if(($this->sPage != '' && !empty($this->aPageRebuild)) !== false)
            $aTmplVarsActions = array(
                'js_object' => $sJsObject,
        		'url_view' => BX_DOL_URL_ROOT . $oPermalinks->permalink($this->aPageRebuild['url']),
                'action_page_export' => $this->sActionPageExport,
            	'action_page_edit' => $this->sActionPageEdit,
                'action_block_create' => $this->sActionBlockCreate,
            );

        $aTmplVars = array(
        	'js_object' => $this->getPageJsObject(),
            'selector' => $oForm->genRow($aInputPages),
        	'action_page_create' => $this->sActionPageCreate,
            'bx_if:show_actions' => array(
                'condition' => $this->sPage != '',
                'content' => $aTmplVarsActions
            )
        );

        return BxBaseStudioPage::getBlockPanelTop(
            array('panel_top' => $this->oModule->_oTemplate->parseHtmlByName('bp_block_panel_top.html', $aTmplVars))
        );
    }

    protected function actionPageCreate() {
        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('mod_dev_bp_page', 'mod_dev_bp_page_add');
        $oForm->aFormAttrs['action'] = sprintf($this->sPageUrl, $this->sType, $this->sPage) . '&bp_action=' . $this->sActionPageCreate;
        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_bp_txt_select_module')), BxDolStudioUtils::getModules());

        $oForm->aInputs['layout_id']['values'] = array(
            array('key' => '', 'value' => _t('_bx_dev_bp_txt_page_layout_id_select'))
        );

        $aLayouts = array();
        $this->oDb->getLayouts(array('type' => 'all'), $aLayouts, false);
        foreach($aLayouts as $aLayout)
            $oForm->aInputs['layout_id']['values'][] = array('key' => $aLayout['id'], 'value' => _t($aLayout['title']));

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sObject = $oForm->getCleanValue('object');
            $sModule = $oForm->getCleanValue('module');

            if(($iId = (int)$oForm->insert()) != 0)
                return array('eval' => $this->getPageJsObject() . '.onCreatePage(\'' . $sModule . '\', \'' . $sObject . '\')');
            else
                return array('msg' => _t('_bx_dev_bp_err_page_create'));
        }

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->aHtmlIds['create_popup_id'], _t('_bx_dev_bp_txt_page_create_popup'), $this->oModule->_oTemplate->parseHtmlByName('bp_add_page.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true)
        )));

        return array('popup' => $sContent);
    }

    protected function actionPageExport() {
        $sContentInsert = $sContentDelete = "";

        $aPage = $this->aPageRebuild;

        $sContentInsert .= ($this->oModule->_oDb->getQueryInsert('sys_objects_page', array($aPage), "Dumping data for '" . $aPage['object'] . "' page"));
        $sContentDelete .= ($this->oModule->_oDb->getQueryDelete('sys_objects_page', 'object', array($aPage), "Deleting data for '" . $aPage['object'] . "' page"));

        $aBlocks = array();
        $this->oDb->getBlocks(array('type' => 'by_object', 'value' => $aPage['object']), $aBlocks, false);
        $sContentInsert .= $this->oModule->_oDb->getQueryInsert('sys_pages_blocks', $aBlocks, false, array('id', 'name'));
        $sContentDelete .= $this->oModule->_oDb->getQueryDelete('sys_pages_blocks', 'object', array($aPage), false);

        $aForm = array(
            'form_attrs' => array(),
            'inputs' => array (
            	'insert' => array(
                    'type' => 'textarea',
                    'name' => 'insert',
                    'caption' => _t('_bx_dev_bp_txt_page_export_insert'),
                    'value' => $sContentInsert,
                ),
                'delete' => array(
                    'type' => 'textarea',
                    'name' => 'delete',
                    'caption' => _t('_bx_dev_bp_txt_page_export_delete'),
                    'value' => $sContentDelete,
                ),
                'done' => array (
                    'type' => 'button',
                    'name' => 'done',
                    'value' => _t('_bx_dev_bp_btn_page_done'),
                    'attrs' => array(
                        'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                    ),
                )
            )
        );
        $oForm = new BxTemplStudioFormView($aForm);

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-dev-bp-page-export-popup', _t('_bx_dev_bp_txt_page_export_popup', _t($aPage['title'])), $this->oModule->_oTemplate->parseHtmlByName('bp_export.html', array(
            'content' => $oForm->getCode()
        )));

        return array('popup' => $sContent);
    }   

    protected function actionBlockEdit() {
        $iId = (int)bx_get('id');
        if(!$iId)
            return array();

        $aBlock = array();
        $this->oDb->getBlocks(array('type' => 'by_id', 'value' => $iId), $aBlock, false);
        if(empty($aBlock) || !is_array($aBlock))
            return array('msg' => _t('_bx_dev_bp_err_block_not_found'));

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('mod_dev_bp_block', 'mod_dev_bp_block_edit');

        $this->onLoadBlock($oForm, $aBlock);

        $oForm->initChecker($aBlock);
        if($oForm->isSubmittedAndValid()) {
            $this->onSaveBlock($oForm, $aBlock);

            if($oForm->update($iId) !== false)
                return array('eval' => $this->getPageJsObject() . '.onEditBlock(oData)');
            else
                return array('msg' => _t('_bx_dev_bp_err_block_edit'));
        }

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->aHtmlIds['edit_block_popup_id'], _t('_bx_dev_bp_txt_block_edit_popup', _t($aBlock['title'])), $this->oModule->_oTemplate->parseHtmlByName('bp_add_block.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true)
        )));

        return array('popup' => $sContent);
    }

    protected function getSettingsOptions($bInputsOnly = false) {
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
			'inputs' => parent::getSettingsOptions(true)
        ); 

        $aForm['inputs']['title_system']['type'] = 'text';
        $aForm['inputs']['title']['type'] = 'text';
        $aForm['inputs']['deletable'] = array(
            'type' => 'checkbox',
            'name' => 'deletable',
            'caption' => _t('_bx_dev_bp_txt_page_deletable'),
            'info' => '',
            'value' => '1',
            'checked' => (int)$this->aPageRebuild['deletable'] == 1,
        	'required' => '',
        	'db' => array (
                'pass' => 'Int',
            )
        );
        $aForm['inputs']['override_class_name'] = array(
            'type' => 'text',
            'name' => 'override_class_name',
            'caption' => _t('_bx_dev_bp_txt_page_override_class_name'),
            'info' => '',
            'value' => $this->aPageRebuild['override_class_name'],
            'required' => '0',
            'db' => array (
                'pass' => 'Xss',
            )
        );
        $aForm['inputs']['override_class_file'] = array(
            'type' => 'text',
            'name' => 'override_class_file',
            'caption' => _t('_bx_dev_bp_txt_page_override_class_file'),
            'info' => '',
            'value' => $this->aPageRebuild['override_class_file'],
            'required' => '0',
            'db' => array (
                'pass' => 'Xss',
            )
        );

        if($bInputsOnly)
            return $aForm['inputs'];

        $oForm = new BxTemplStudioFormView($aForm);
        return $oForm->getCode();
    }

    protected function getSettingsCache($bInputsOnly = false) {
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
            'inputs' => parent::getSettingsCache(true)
        );

        unset($aForm['inputs']['cache_lifetime']['attrs']);

        $aForm['inputs']['cache_editable'] = array(
            'type' => 'checkbox',
            'name' => 'cache_editable',
            'caption' => _t('_bx_dev_bp_txt_page_cache_editable'),
            'info' => '',
            'value' => '1',
            'checked' => (int)$this->aPageRebuild['cache_editable'] == 1,
            'required' => '',
            'db' => array (
                'pass' => 'Int',
            )
        );

        if($bInputsOnly)
            return $aForm['inputs'];

        $oForm = new BxTemplStudioFormView($aForm);
        return $oForm->getCode();
    }

    protected function getBlockModule($aBlock) {
        return $this->aPageRebuild['module'];
    }

    protected function getBlockContent($aBlock) {
        $aFields = array();

        switch($aBlock['type']) {
            case BX_DOL_STUDIO_BP_BLOCK_SERVICE:
                $aFields = array(
                    'content' => array(
                        'type' => 'textarea',
                        'name' => 'content',
                        'caption' => _t('_bx_dev_bp_txt_block_content_service'),
                        'info' => '',
                        'value' => $aBlock['content'],
                        'required' => '0',
                        'db' => array (
                            'pass' => 'Xss',
                        ),
                    )
                );
                break;

            default:
                $aFields = parent::getBlockContent($aBlock);
                break;
        }

        return $aFields;
    }

    protected function getBlocks($sModule) {
        $aBlocks = parent::getBlocks($sModule);

        if($sModule == BX_DOL_STUDIO_BP_SKELETONS) {
            $aBlock = array();
    	    $this->oDb->getBlocks(array('type' => 'skeleton_by_type', 'value' => 'service'), $aBlock, false);
    	    if(!empty($aBlock) && is_array($aBlock))
    	        $aBlocks[] = $aBlock;
        }

    	return $aBlocks;
    }

    protected function onLoadBlock(&$oForm, &$aBlock) {
        $oForm->aFormAttrs['action'] = sprintf($this->sPageUrl, $this->sType, $this->sPage) . '&bp_action=' . $this->sActionBlockEdit;
        $oForm->aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_bp_txt_select_module')), BxDolStudioUtils::getModules());
        
        $aDBoxes = array();
        $this->oDb->getDesignBoxes(array('type' => 'ordered'), $aDBoxes, false);
        foreach($aDBoxes as $aDBox)
            $oForm->aInputs['designbox_id']['values'][] = array('key' => $this->sSelectKeyPrefix . $aDBox['id'], 'value' => _t($aDBox['title']));

        $oForm->aInputs['visible_for']['value'] = $aBlock['visible_for_levels'] == BX_DOL_INT_MAX ? BX_DOL_STUDIO_VISIBLE_ALL : BX_DOL_STUDIO_VISIBLE_SELECTED;
        $oForm->aInputs['visible_for']['values'] = array(
			array('key' => BX_DOL_STUDIO_VISIBLE_ALL, 'value' => _t('_bx_dev_bp_txt_block_visible_for_all')),
			array('key' => BX_DOL_STUDIO_VISIBLE_SELECTED, 'value' => _t('_bx_dev_bp_txt_block_visible_for_selected')),
        );
        $oForm->aInputs['visible_for']['attrs']['onchange'] = $this->getPageJsObject() . '.onChangeVisibleFor(this)';

        $oForm->aInputs['visible_for_levels']['tr_attrs']['style'] = $aBlock['visible_for_levels'] == BX_DOL_INT_MAX ? 'display:none' : '';
        BxDolStudioUtils::getVisibilityValues($aBlock['visible_for_levels'], $oForm->aInputs['visible_for_levels']['values'], $oForm->aInputs['visible_for_levels']['value']);
        $aBlock['visible_for_levels'] = $oForm->aInputs['visible_for_levels']['value'];

        $oForm->aInputs = $this->addInArray($oForm->aInputs, 'visible_for_levels', $this->getBlockContent($aBlock));
        $oForm->aInputs['controls'][0]['value'] = _t('_bx_dev_bp_btn_block_save');

        if($aBlock['type'] == BX_DOL_STUDIO_BP_BLOCK_SERVICE)
            $aBlock['content'] = BxDevFunctions::unserializeString($aBlock['content']);

        if((int)$aBlock['designbox_id'] != 0)
            $aBlock['designbox_id'] = $this->sSelectKeyPrefix . $aBlock['designbox_id']; 
    }

    protected function onSaveBlock(&$oForm, &$aBlock) {
        parent::onSaveBlock($oForm, $aBlock);

        if($aBlock['type'] == BX_DOL_STUDIO_BP_BLOCK_SERVICE && isset($oForm->aInputs['content'])) {
            $sValue = BxDolForm::getSubmittedValue('content', $oForm->aFormAttrs['method']);
            $sValue = BxDevFunctions::serializeString($sValue);
            BxDolForm::setSubmittedValue('content', $sValue, $oForm->aFormAttrs['method']);
        }
    }
}
/** @} */