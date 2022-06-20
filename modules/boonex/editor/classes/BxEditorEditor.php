<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Editor integration
 * @ingroup     UnaModules
 * 
 * @{
 */

/**
 * Editor representation.
 * @see BxDolEditor
 */
class BxEditorEditor extends BxDolEditor
{
    protected $_sModule;
    protected $_oModule;
    protected $_aButtons;

    /**
     * Common initialization params
     */
    protected static $CONF_COMMON = "
        var oParams = {   
			root_url: '{bx_url_root}',
            bx_url_uploader: '{bx_url_uploader}',
            name: '{bx_var_editor_name}',
            selector: '{bx_var_selector}',
			toolbar: {toolbar},
			buttons: {buttons},
			
        }
        {bx_var_editor_name} = bx_ex_editor_init({bx_var_editor_name}, oParams);";

    protected $_oTemplate;
    protected $_bJsCssAdded = false;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
        
        $this->_sModule = 'bx_editor';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
        
        $this->_aButtons = $this->_oModule->_aButtons;
    }

    /**
     * Attach editor to HTML element, in most cases - textarea.
     * @param $sSelector - jQuery selector to attach editor to.
     * @param $iViewMode - editor view mode: BX_EDITOR_STANDARD, BX_EDITOR_MINI, BX_EDITOR_FULL
     * @param $bDynamicMode - is AJAX mode or not, the HTML with editor area is loaded dynamically.
     */
    public function attachEditor ($sSelector, $iViewMode = BX_EDITOR_STANDARD, $bDynamicMode = false, $aAttrs = [])
    {
        // set visual mode
        switch ($iViewMode) {
                
            case BX_EDITOR_MINI:
                $sToolbarItems = $this->_oModule->_oDb->getSettings('mini');
                break;
                
            case BX_EDITOR_FULL:
                $sToolbarItems = $this->_oModule->_oDb->getSettings('full');
            break;
                
            case BX_EDITOR_STANDARD:
            default:
                $sToolbarItems = $this->_oModule->_oDb->getSettings('standard');
        }
        
        if ($this->_sButtonsCustom !== false) {
            $sToolbarItems = $this->_sButtonsCustom;
        }
        
        $sToolbarItems = str_replace(',', "','", $sToolbarItems);
        $sToolbarItems = str_replace(",'separator',", '],[', $sToolbarItems);
        $sToolbarItems = "['" . $sToolbarItems . "']";
        
        $sEditorName = 'editor_' . str_replace(['-', ' '], '_', $aAttrs['form_id'] . '_' . $aAttrs['element_name']);
        
        $this->_oTemplate->addJsTranslation([
            '_bx_editor_embed_popup_link',
            '_bx_editor_embed_popup_embed',
        ]);
        
        // initialize editor
        $sInitEditor = $this->_replaceMarkers(self::$CONF_COMMON, array(
            'bx_var_selector' => bx_js_string($sSelector, BX_ESCAPE_STR_APOS),
            'bx_var_query_params' => isset($aAttrs['query_params']) ? json_encode($aAttrs['query_params']) : "''",
            'bx_var_form_id' => $aAttrs['form_id'],
            'toolbar' => $sToolbarItems ? '[' . $sToolbarItems . ']' : 'false',
			'buttons' => json_encode($this->_aButtons),
            'insert_as_plain_text' => getParam('sys_quill_insert_as_plain_text') == 'on' ? 'true' : 'false',
            'bx_var_css_additional_class' => $sToolbarItems ? '' : 'bx-form-input-html-quill-empty',
            'bx_var_element_name' => str_replace(['-', ' '], '_', $aAttrs['element_name']),
            'bx_var_editor_name' => $sEditorName,
            'bx_var_skin' => bx_js_string($this->_aObject['skin'], BX_ESCAPE_STR_APOS),
            'bx_url_root' => bx_js_string(BX_DOL_URL_ROOT, BX_ESCAPE_STR_APOS),
            'bx_url_uploader' => bx_js_string(BX_DOL_URL_ROOT . "storage.php?o=sys_images_editor&t=sys_images_editor&a=upload", BX_ESCAPE_STR_APOS)
        ));

        $sInitCallBack = "
            bQuillEditorInited = true;
        " . $sInitEditor;

        if ($bDynamicMode) {
            list($aJs, $aCss) = $this->_getJsCss(true);

            $sScript = "var " . $sEditorName . "; " . $this->_oTemplate->addJsPreloaded($aJs, $sInitCallBack, "typeof bQuillEditorInited === 'undefined'", $sInitEditor);
            $sScript = $this->_oTemplate->_wrapInTagJsCode($sScript);
            $sScript = $this->_oTemplate->addCss($aCss, true) . $sScript;

        } else {
            $sScript = "var " . $sEditorName . "; " . $this->_oTemplate->addJsCodeOnLoad($sInitCallBack);
            $sScript = $this->_oTemplate->_wrapInTagJsCode($sScript);
        }

        return $this->_addJsCss($bDynamicMode) . $sScript;
    }
    

    /**
     * Add css/js files which are needed for editor display and functionality.
     */
    protected function _addJsCss($bDynamicMode = false, $sInitEditor = '')
    {
        if ($bDynamicMode)
            return '';
        
        if ($this->_bJsCssAdded)
            return '';
        
        list($aJs, $aCss) = $this->_getJsCss();
		
        $this->_oTemplate->addJs($aJs);
        $this->_oTemplate->addCss($aCss);
        
        $this->_bJsCssAdded = true;
        
        return '';
    }
    
    protected function _getJsCss($bUseUrlsForJs = false)
    {
		$sJsPrefix = $bUseUrlsForJs ? BX_DOL_URL_MODULES : BX_DIRECTORY_PATH_MODULES;
		$sJsSuffix = $bUseUrlsForJs ? '' : '|';
		
		$aCss = [
			 BX_DIRECTORY_PATH_MODULES . 'boonex/editor/template/css/|main.css',  
		];
		$aJs = [
            $sJsPrefix . 'boonex/editor/js/' . $sJsSuffix . 'editor.js',
            'https://unpkg.com/@popperjs/core@2',
            'https://unpkg.com/tippy.js@6'
        ];
		
        return array($aJs, $aCss);
    }
}

/** @} */
