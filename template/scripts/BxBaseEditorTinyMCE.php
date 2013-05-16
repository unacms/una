<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolEditor');

/**
 * TinyMCE editor representation.
 * @see BxDolEditor
 */
class BxBaseEditorTinyMCE extends BxDolEditor {

    /**
     * Common initialization params
     */
    protected static $CONF_COMMON = "                    
                    jQuery('{bx_var_selector}').tinymce({
                        {bx_var_custom_init}
                        document_base_url: '{bx_url_root}',
                        script_url: '{bx_var_plugins_path}tiny_mce/tiny_mce_gzip.php',
                        skin: '{bx_var_skin}',
                        language: '{bx_var_lang}',
                        content_css: '{bx_var_css_path}',
                        gecko_spellcheck: true,
                        entity_encoding: 'raw',
                        verify_html: false            
                    });
    ";

    /**
     * Standard view initialization params
     */
    protected static $CONF_STANDARD = "
                        plugins: 'autolink,autosave,lists,inlinepopups,media,paste,fullscreen',
                        width: '100%',
                        height: '270',
                        theme: 'advanced',
                        theme_advanced_buttons1: 'bold,italic,underline,removeformat,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,undo,redo,|,blockquote,formatselect',
                        theme_advanced_buttons2: 'hr,link,unlink,image,media,|,fullscreen,cleanup,pastetext,code',
                        theme_advanced_buttons3: '',
                        theme_advanced_toolbar_location: 'top',
                        theme_advanced_toolbar_align: 'left',
                        theme_advanced_statusbar_location: 'bottom',
                        theme_advanced_resizing: true,
                        theme_advanced_resize_horizontal: false,
                        theme_advanced_resizing_use_cookie: true,
                        theme_advanced_path: false,
    ";

    /**
     * Minimal view initialization params
     */
    protected static $CONF_MINI = "
                        plugins: 'autolink,autosave,lists,inlinepopups,paste,fullscreen',
                        width: '100%',
                        height: '150',
                        theme: 'advanced',
                        theme_advanced_buttons1: 'bold,italic,underline,removeformat,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,blockquote,|,link,unlink,image',
                        theme_advanced_buttons2: '',
                        theme_advanced_buttons3: '',
                        theme_advanced_toolbar_location: 'top',
                        theme_advanced_toolbar_align: 'left',
                        theme_advanced_statusbar_location: 'none',
    ";

    /**
     * Full view initialization params
     */
    protected static $CONF_FULL = "
                        plugins: 'autolink,autosave,lists,table,inlinepopups,media,searchreplace,print,paste,fullscreen',
                        width: '100%',
                        height: '320',
                        theme: 'advanced',
                        theme_advanced_buttons1: 'bold,italic,underline,removeformat,|,sub,sup,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,undo,redo,|,outdent,indent,blockquote,formatselect,|,hr,link,unlink,image,media',
                        theme_advanced_buttons2: 'anchor,|,tablecontrols,|,visualaid,|,search,replace,|,print,|,fullscreen,cleanup,pastetext,code',
                        theme_advanced_buttons3: '',
                        theme_advanced_toolbar_location: 'top',
                        theme_advanced_toolbar_align: 'left',
                        theme_advanced_statusbar_location: 'bottom',
                        theme_advanced_resizing: true,
                        theme_advanced_resize_horizontal: false,
                        theme_advanced_resizing_use_cookie: true,
    ";

    /**
     * Available editor languages
     */
    protected static $CONF_LANGS = array('ar' => 1, 'be' => 1, 'bg' => 1, 'ca' => 1, 'cn' => 1, 'cs' => 1, 'cy' => 1, 'da' => 1, 'de' => 1, 'el' => 1, 'en' => 1, 'es' => 1, 'et' => 1, 'eu' => 1, 'fa' => 1, 'fi' => 1, 'fr' => 1, 'gl' => 1, 'he' => 1, 'hu' => 1, 'id' => 1, 'it' => 1, 'ja' => 1, 'km' => 1, 'ko' => 1, 'lt' => 1, 'lv' => 1, 'mk' => 1, 'nb' => 1, 'nl' => 1, 'no' => 1, 'pl' => 1, 'pt' => 1, 'ro' => 1, 'ru' => 1, 'sk' => 1, 'sl' => 1, 'sq' => 1, 'sv' => 1, 'tr' => 1, 'uk' => 1, 'zh' => 1);

    protected $_oTemplate;
    protected $_bJsCssAdded = false;

    public function __construct ($aObject, $oTemplate) {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    /**
     * Attach editor to HTML element, in most cases - textarea.
     * @param $sSelector - jQuery selector to attach editor to.
     * @param $iViewMode - editor view mode: BX_EDITOR_STANDARD, BX_EDITOR_MINI, BX_EDITOR_FULL
     * @param $bDynamicMode - is AJAX mode or not, the HTML with editor area is loaded dynamically.
     */
    public function attachEditor ($sSelector, $iViewMode = BX_EDITOR_STANDARD, $bDynamicMode = false) {

        // set visual mode
        switch ($iViewMode) {
            case BX_EDITOR_MINI:
                 $sToolsItems = self::$CONF_MINI;
                break;
            case BX_EDITOR_FULL:
                $sToolsItems = self::$CONF_FULL;
            break;
            case BX_EDITOR_STANDARD:
            default:
                 $sToolsItems = self::$CONF_STANDARD;
        }

        // detect language
        bx_import('BxDolLanguages');
        $sLang = BxDolLanguages::getInstance()->detectLanguageFromArray (self::$CONF_LANGS);

                
        // initialize editor
        $sInitEditor = $this->_replaceMarkers(self::$CONF_COMMON, array(            
            'bx_var_custom_init' => $sToolsItems,
            'bx_var_plugins_path' => bx_js_string(BX_DOL_URL_PLUGINS, BX_ESCAPE_STR_APOS),
            'bx_var_css_path' => bx_js_string($this->_oTemplate->getCssUrl('editor.css'), BX_ESCAPE_STR_APOS),
            'bx_var_skin' => bx_js_string($this->_aObject['skin'], BX_ESCAPE_STR_APOS),
            'bx_var_lang' => bx_js_string($sLang, BX_ESCAPE_STR_APOS),
            'bx_var_selector' => bx_js_string($sSelector, BX_ESCAPE_STR_APOS),
            'bx_url_root' => bx_js_string(BX_DOL_URL_ROOT, BX_ESCAPE_STR_APOS),
        ));

        if ($bDynamicMode) {

            $sScript = "<script>
                if ('undefined' == typeof(jQuery(document).tinymce)) {
                    $.getScript('" . bx_js_string(BX_DOL_URL_PLUGINS . 'tiny_mce/jquery.tinymce.js', BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
                        $sInitEditor
                    });
                } else {
                    $sInitEditor
                }
            </script>";

        } else {

            $sScript = "
            <script>
                $(document).ready(function () {
                    $sInitEditor
                });
            </script>";

        }

        return $this->_addJsCss($bDynamicMode) . $sScript;
    }

    /**
     * Add css/js files which are needed for editor display and functionality.
     */
    protected function _addJsCss($bDynamicMode = false, $sInitEditor = '') {
        if ($bDynamicMode)
            return '';
        if ($this->_bJsCssAdded)
            return '';
        $this->_oTemplate->addJs(BX_DOL_URL_PLUGINS . 'tiny_mce/jquery.tinymce.js');
        $this->_bJsCssAdded = true;
        return '';
    }

}

/** @} */
