<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * TinyMCE editor representation.
 * @see BxDolEditor
 */
class BxBaseEditorTinyMCE extends BxDolEditor
{
    /**
     * Common initialization params
     */
    protected static $CONF_COMMON = <<<EOS
                    jQuery('{bx_var_selector}').tinymce({
                        {bx_var_custom_init}
                        {bx_var_custom_conf}
                        document_base_url: '{bx_url_root}',
                        theme_url: '{bx_url_tinymce}themes/modern/theme.min.js',
                        skin_url: '{bx_url_tinymce}skins/{bx_var_skin}/',
                        language_url: '{bx_url_tinymce}langs/{bx_var_lang}.js',
                        content_css: '{bx_var_css_path}',
                        entity_encoding: 'raw',
                        browser_spellcheck: true,
                        branding: false,
                        body_class: 'bx-def-color-bg-block',
                        codesample_languages: [
                            {text: 'HTML/XML', value: 'markup'},
                            {text: 'JavaScript', value: 'javascript'},
                            {text: 'CSS', value: 'css'},
                            {text: 'PHP', value: 'php'},
                        ],
                        init_instance_callback: function(editor) {
                            $(editor.contentDocument.activeElement).atwho({
                                searchKey: 'label',
                                at: "@", 
                                limit: 20,
                                displayTpl: '<li class="bx-mention-row" data-value="\${value}"><span>\${label}</span> <img class="bx-def-round-corners" src="\${thumb}" /></li>',
                                insertTpl: '<a data-profile-id="\${value}" href="\${url}">\${label}</a>',
                                callbacks: {
                                    remoteFilter: function(query, callback) {
                                        $.getJSON("{bx_url_root}searchExtended.php?action=get_authors", {term: query}, function(data) {
                                            callback(data);
                                        });
                                    }
                                },

                            });

                            bx_editor_on_init('{bx_var_selector}');
                        },
                        setup: function(editor) {
                            editor.on('keydown', function(e) {
                                if (e.keyCode == 13 && $(editor.contentDocument.activeElement).atwho('isSelecting'))
                                    return false;
                            });
                        }
                    });
EOS;

    /**
     * Standard view initialization params
     */
    protected static $CONF_STANDARD = "
                        external_plugins: {
                            advlist: '{bx_url_tinymce}plugins/advlist/plugin.min.js',
                            autolink: '{bx_url_tinymce}plugins/autolink/plugin.min.js',
                            autosave: '{bx_url_tinymce}plugins/autosave/plugin.min.js',
                            code: '{bx_url_tinymce}plugins/code/plugin.min.js',
                            hr: '{bx_url_tinymce}plugins/hr/plugin.min.js', 
                            image: '{bx_url_tinymce}plugins/image/plugin.min.js',
                            link: '{bx_url_tinymce}plugins/link/plugin.min.js',
                            lists: '{bx_url_tinymce}plugins/lists/plugin.min.js',
                            media: '{bx_url_tinymce}plugins/media/plugin.min.js',
                            paste: '{bx_url_tinymce}plugins/paste/plugin.min.js',
                            fullscreen: '{bx_url_tinymce}plugins/fullscreen/plugin.min.js',
                            codesample: '{bx_url_tinymce}plugins/codesample/plugin.min.js',
                        },
                        width: '100%',
                        height: '270',
                        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fullscreen codesample',
                        statusbar: true,
                        resize: true,
    ";

    /**
     * Minimal view initialization params
     */
    protected static $CONF_MINI = "
                        menubar: false,
                        external_plugins: {
                            autolink: '{bx_url_tinymce}plugins/autolink/plugin.min.js',
                            image: '{bx_url_tinymce}plugins/image/plugin.min.js',
                            link: '{bx_url_tinymce}plugins/link/plugin.min.js',
                            lists: '{bx_url_tinymce}plugins/lists/plugin.min.js',
                            paste: '{bx_url_tinymce}plugins/paste/plugin.min.js',
                            fullscreen: '{bx_url_tinymce}plugins/fullscreen/plugin.min.js',
                            codesample: '{bx_url_tinymce}plugins/codesample/plugin.min.js',
                        },
                        width: '100%',
                        height: '150',                        
                        toolbar: 'bold italic underline removeformat | bullist numlist | alignleft aligncenter alignright | blockquote | link unlink image | fullscreen codesample',
                        statusbar: true,
                        resize: true,
    ";

    /**
     * Full view initialization params
     */
    protected static $CONF_FULL = "
                        external_plugins: {
                            advlist: '{bx_url_tinymce}plugins/advlist/plugin.min.js',
                            anchor: '{bx_url_tinymce}plugins/anchor/plugin.min.js',
                            autolink: '{bx_url_tinymce}plugins/autolink/plugin.min.js',
                            autoresize: '{bx_url_tinymce}plugins/autoresize/plugin.min.js',
                            autosave: '{bx_url_tinymce}plugins/autosave/plugin.min.js',
                            charmap: '{bx_url_tinymce}plugins/charmap/plugin.min.js',
                            code: '{bx_url_tinymce}plugins/code/plugin.min.js',
                            emoticons: '{bx_url_tinymce}plugins/emoticons/plugin.min.js',
                            hr: '{bx_url_tinymce}plugins/hr/plugin.min.js',
                            image: '{bx_url_tinymce}plugins/image/plugin.min.js',
                            link: '{bx_url_tinymce}plugins/link/plugin.min.js',
                            lists: '{bx_url_tinymce}plugins/lists/plugin.min.js',
                            media: '{bx_url_tinymce}plugins/media/plugin.min.js',
                            nonbreaking: '{bx_url_tinymce}plugins/nonbreaking/plugin.min.js',
                            pagebreak: '{bx_url_tinymce}plugins/pagebreak/plugin.min.js',
                            preview: '{bx_url_tinymce}plugins/preview/plugin.min.js',
                            print: '{bx_url_tinymce}plugins/print/plugin.min.js',
                            paste: '{bx_url_tinymce}plugins/paste/plugin.min.js',
                            save: '{bx_url_tinymce}plugins/save/plugin.min.js',
                            searchreplace: '{bx_url_tinymce}plugins/searchreplace/plugin.min.js',
                            table: '{bx_url_tinymce}plugins/table/plugin.min.js',
                            textcolor: '{bx_url_tinymce}plugins/textcolor/plugin.min.js',
                            visualblocks: '{bx_url_tinymce}plugins/visualblocks/plugin.min.js',
                            fullscreen: '{bx_url_tinymce}plugins/fullscreen/plugin.min.js',
                            codesample: '{bx_url_tinymce}plugins/codesample/plugin.min.js',
                        },
                        width: '100%',
                        height: '320',
                        toolbar: [
                            'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                            'print preview media | forecolor emoticons | fullscreen codesample'
                        ],
                        statusbar: true,
                        resize: true,
                        image_advtab: true,
    ";

    /**
     * Available editor languages
     */
    protected static $CONF_LANGS = array('af_ZA' => 1, 'ar' => 1, 'be' => 1, 'bg_BG' => 1, 'ca' => 1, 'cs' => 1, 'cy' => 1, 'da' => 1, 'de' => 1, 'de_AT' => 1, 'dv' => 1, 'el' => 1, 'en' => 1, 'en_CA' => 1, 'en_GB' => 1, 'es' => 1, 'es_MX' => 1, 'et' => 1, 'fa_IR' => 1, 'fr_FR' => 1, 'ga' => 1, 'gl' => 1, 'he_IL' => 1, 'hr' => 1, 'hu_HU' => 1, 'it' => 1, 'ja' => 1, 'ka_GE' => 1, 'kab' => 1, 'kk' => 1, 'km_KH' => 1, 'ko_KR' => 1, 'lv' => 1, 'nb_NO' => 1, 'nl' => 1, 'pl' => 1, 'pt_BR' => 1, 'pt_PT' => 1, 'ro' => 1, 'ru' => 1, 'sk' => 1, 'sl_SI' => 1, 'sv_SE' => 1, 'ta' => 1, 'ta_IN' => 1, 'th_TH' => 1, 'tr' => 1, 'tr_TR' => 1, 'uk' => 1, 'uk_UA' => 1, 'uz' => 1, 'vi_VN' => 1, 'zh_CN' => 1, 'zh_TW' => 1);

    protected $_oTemplate;
    protected $_bJsCssAdded = false;

    public function __construct ($aObject, $oTemplate)
    {
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
    public function attachEditor ($sSelector, $iViewMode = BX_EDITOR_STANDARD, $bDynamicMode = false)
    {
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
        $sLang = BxDolLanguages::getInstance()->detectLanguageFromArray (self::$CONF_LANGS);

        $sCss = 'editor.less';
        $aCss = BxDolTemplate::getInstance()->_lessCss(array(
        	'path' => $this->_oTemplate->getCssPath($sCss),
        	'url' => $this->_oTemplate->getCssUrl($sCss)
        ));

        // initialize editor
        $sInitEditor = $this->_replaceMarkers(self::$CONF_COMMON, array(
            'bx_var_custom_init' => $sToolsItems,
            'bx_var_custom_conf' => $this->_sConfCustom,
            'bx_var_plugins_path' => bx_js_string(BX_DOL_URL_PLUGINS_PUBLIC, BX_ESCAPE_STR_APOS),
            'bx_var_css_path' => bx_js_string($aCss['url'], BX_ESCAPE_STR_APOS),
            'bx_var_skin' => bx_js_string($this->_aObject['skin'], BX_ESCAPE_STR_APOS),
            'bx_var_lang' => bx_js_string($sLang, BX_ESCAPE_STR_APOS),
            'bx_var_selector' => bx_js_string($sSelector, BX_ESCAPE_STR_APOS),
            'bx_url_root' => bx_js_string(BX_DOL_URL_ROOT, BX_ESCAPE_STR_APOS),
            'bx_url_tinymce' => bx_js_string(BX_DOL_URL_PLUGINS_PUBLIC . 'tinymce/', BX_ESCAPE_STR_APOS),
        ));

        if ($bDynamicMode) {

            $sScript = "<script>
                if ('undefined' == typeof(jQuery(document).tinymce)) {
                    window.tinyMCEPreInit = {base : '" . bx_js_string(BX_DOL_URL_PLUGINS_PUBLIC . 'tinymce', BX_ESCAPE_STR_APOS) . "', suffix : '.min', query : ''};
                    $.getScript('" . bx_js_string(BX_DOL_URL_ROOT . 'inc/js/editor.tinymce.js', BX_ESCAPE_STR_APOS) . "');
                    $.getScript('" . bx_js_string(BX_DOL_URL_PLUGINS_PUBLIC . 'tinymce/tinymce.min.js', BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
                        $.getScript('" . bx_js_string(BX_DOL_URL_PLUGINS_PUBLIC . 'tinymce/jquery.tinymce.min.js', BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
                            $sInitEditor
                        });
                    });
                } else {
                	setTimeout(function () {
                    	$sInitEditor
                    }, 10); // wait while html is rendered in case of dynamic adding html with tinymce
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
    protected function _addJsCss($bDynamicMode = false, $sInitEditor = '')
    {
        if ($bDynamicMode)
            return '';
        if ($this->_bJsCssAdded)
            return '';
        $this->_oTemplate->addJs(array('tinymce/tinymce.min.js', 'tinymce/jquery.tinymce.min.js', 'editor.tinymce.js'));
        $this->_bJsCssAdded = true;
        return '';
    }

}

/** @} */
