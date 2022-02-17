<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Froala Froala editor integration
 * @ingroup     UnaModules
 * 
 * @{
 */

/**
 * Froala editor representation.
 * @see BxDolEditor
 */
class BxFroalaEditor extends BxDolEditor
{
    protected $_sModule;

    /**
     * Common initialization params
     */
    protected static $CONF_COMMON = <<<EOS
        new FroalaEditor('{bx_var_selector}', {
            {bx_var_custom_init}
            {bx_var_custom_conf}
            key:'{bx_var_froala_key}',
            attribution: false,
            embedlyKey: '{bx_var_embedly_key}',
            emoticonsUseImage: false,
            charCounterCount: false,
            toolbarSticky: false,
            quickInsertTags: [],
            pastePlain: true,
            entities: '',
            imageUpload: true,
            imageUploadURL: '{bx_var_image_upload_url}',
            videoUpload: false,
            language: '{bx_var_lang}',
            theme: '{bx_var_skin}',
            iconsTemplate: '{bx_var_icons_template}',
            events: { 
                'initialized': function () {
                    var editor = this;
                    \$('{bx_var_selector}').data('froala-instance', editor);
                    \$(editor.el).atwho({
                        searchKey: 'label',
                        at: "@", 
                        limit: 20,
                        displayTpl: '<li class="bx-mention-row" data-value="\${value}"><span>\${label}</span> <img class="bx-def-round-corners" src="\${thumb}" /></li>',
                        insertTpl: '<a class="bx-mention" data-profile-id="\${value}" href="\${url}">\${label}</a>',
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
                'keydown': function (e) {
                    var editor = this;
                    if (e.which == FroalaEditor.KEYCODE.ENTER || e.which == FroalaEditor.KEYCODE.SPACE)
                        bx_editor_on_space_enter('{bx_var_selector}');
                    if (e.which == FroalaEditor.KEYCODE.ENTER && \$(editor.el).atwho('isSelecting'))
                        return false;
                },
            }
        });
EOS;

    /**
     * Standard view initialization params
     */
    protected static $CONF_STANDARD = "";

    /**
     * Minimal view initialization params
     */
    protected static $CONF_MINI = "";

    /**
     * Full view initialization params
     */
    protected static $CONF_FULL = "";

    /**
     * Available editor languages
     */
    protected static $CONF_LANGS = array('ar' => 1, 'bs' => 1, 'cs' => 1, 'da' => 1, 'de' => 1, 'el' => 1, 'en_ca' => 1, 'en_gb' => 1, 'es' => 1, 'et' => 1, 'fa' => 1, 'fi' => 1, 'fr' => 1, 'he' => 1, 'hr' => 1, 'hu' => 1, 'id' => 1, 'it' => 1, 'ja' => 1, 'ko' => 1, 'ku' => 1, 'me' => 1, 'nb' => 1, 'nl' => 1, 'pl' => 1, 'pt_br' => 1, 'pt_pt' => 1, 'ro' => 1, 'ru' => 1, 'sk' => 1, 'sl' => 1, 'sr' => 1, 'sv' => 1, 'th' => 1, 'tr' => 1, 'uk' => 1, 'vi' => 1, 'zh_cn' => 1, 'zh_tw' => 1);

    protected $_oTemplate;
    protected $_bJsCssAdded = false;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        $this->_sModule = 'bx_froala';

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

        $this->_aSkins = array(
            'dark' => array('name' => 'dark', 'title' => '_bx_froala_txt_skin_dark'),
            'gray' => array('name' => 'gray', 'title' => '_bx_froala_txt_skin_gray'),
            'royal' => array('name' => 'royal', 'title' => '_bx_froala_txt_skin_royal')
        );
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
                $sToolbarItems = $this->_getToolbarItems(getParam('bx_froala_option_toolbar_mini'));
                $sToolbarItemsXC = $this->_getToolbarItems(getParam('bx_froala_option_toolbar_mini_mobile'));
                $sCustomInit = self::$CONF_MINI;
                break;
            case BX_EDITOR_FULL:
                $sToolbarItems = $this->_getToolbarItems(getParam('bx_froala_option_toolbar_full'));
                $sToolbarItemsXC = $this->_getToolbarItems(getParam('bx_froala_option_toolbar_full_mobile'));
                $sCustomInit = self::$CONF_FULL;
            break;
            case BX_EDITOR_STANDARD:
            default:
                $sToolbarItems = $this->_getToolbarItems(getParam('bx_froala_option_toolbar_standard'));
                $sToolbarItemsXC = $this->_getToolbarItems(getParam('bx_froala_option_toolbar_standard_mobile'));
                $sCustomInit = self::$CONF_STANDARD;
        }
        $sCustomInit .= "\ntoolbarButtons: " . $sToolbarItems . ",";
        $sCustomInit .= "\ntoolbarButtonsXS: " . $sToolbarItemsXC . ",";

        // detect language
        $sLang = BxDolLanguages::getInstance()->detectLanguageFromArray (self::$CONF_LANGS, 'en', true);

        $sCss = 'editor.less';
        $aCss = BxDolTemplate::getInstance()->_lessCss(array(
        	'path' => $this->_oTemplate->getCssPath($sCss),
        	'url' => $this->_oTemplate->getCssUrl($sCss)
        ));

        $oModule = BxDolModule::getInstance($this->_sModule);

        // allow insert any tags for admins 
        if(isAdmin())
            $this->_sConfCustom .= 'htmlRemoveTags: [],';
        
        // initialize editor
        $aMarkers = array(
            'bx_var_custom_init' => $sCustomInit,
            'bx_var_custom_conf' => $this->_sConfCustom,
            'bx_var_plugins_path' => bx_js_string(BX_DOL_URL_PLUGINS_PUBLIC, BX_ESCAPE_STR_APOS),
            'bx_var_css_path' => bx_js_string($aCss['url'], BX_ESCAPE_STR_APOS),
            'bx_var_skin' => bx_js_string($this->_sSkin, BX_ESCAPE_STR_APOS),
            'bx_var_lang' => bx_js_string($sLang, BX_ESCAPE_STR_APOS),
            'bx_var_selector' => bx_js_string($sSelector, BX_ESCAPE_STR_APOS),
            'bx_url_root' => bx_js_string(BX_DOL_URL_ROOT, BX_ESCAPE_STR_APOS),
            'bx_var_froala_key' => bx_js_string(getParam('bx_froala_license_key'), BX_ESCAPE_STR_APOS),
            'bx_var_embedly_key' => bx_js_string(getParam('sys_embedly_api_key'), BX_ESCAPE_STR_APOS),
            'bx_var_image_upload_url' => $oModule ? BX_DOL_URL_ROOT . $oModule->_oConfig->getBaseUri() . 'upload' : '',
            'bx_var_icons_template' => getParam('bx_froala_icons_template'),
        );
        $sInitEditor = $this->_replaceMarkers(self::$CONF_COMMON, $aMarkers);

        bx_alert($this->_sModule, 'init_editor', 0, false, array(
            'conf_common' => self::$CONF_COMMON,
            'markers' => $aMarkers,
            'override_result' => &$sInitEditor
        ));

        if ($bDynamicMode) {

            list($aJs, $aCss) = $this->_getJsCss(true);
            
            $sCss = $this->_oTemplate->addCss($aCss, true);
            
            $sScript = $sCss . "<script>
                if ('undefined' == typeof(window.FroalaEditor)) {
                    bx_get_scripts(" . json_encode($aJs) . ", function () {
                        $sInitEditor
                    });
                } else {
                	setTimeout(function () {
                    	$sInitEditor
                    }, 10); // wait while html is rendered in case of dynamic adding html
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

    protected function _getToolbarItems($s)
    {
        if ($this->_sButtonsCustom !== false)
            return $this->_sButtonsCustom && '{' == $this->_sButtonsCustom[0] ? $this->_sButtonsCustom : json_encode(explode(',', $this->_sButtonsCustom));

        return $s && '{' == $s[0] ? $s : json_encode(explode(',', $s));
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

    protected function _getPlugins($b3rdParty = false)
    {
        $a3rdParty = array('embedly', 'image_aviary', 'spell_checker');
        $a = explode(',', $this->_sPluginsCustom !== false ? $this->_sPluginsCustom : getParam('bx_froala_option_plugins'));
        return array_filter($a, function ($s) use ($a3rdParty, $b3rdParty) {
            return !($b3rdParty ^ in_array($s, $a3rdParty));
        });
    }

    protected function _getJsCss($bUseUrlsForJs = false)
    {
        $sLang = BxDolLanguages::getInstance()->detectLanguageFromArray (self::$CONF_LANGS, 'en', true);
        $sJsPrefix = $bUseUrlsForJs ? BX_DOL_URL_MODULES : BX_DIRECTORY_PATH_MODULES;
        $sJsSuffix = $bUseUrlsForJs ? '' : '|';
        
        $aJs = array(
            $sJsPrefix . 'boonex/froala/js/' . $sJsSuffix . 'editor.js',
            $sJsPrefix . 'boonex/froala/plugins/froala/js/' . $sJsSuffix . 'froala_editor.pkgd.min.js',
        );
        if ($sLang && 'en' != $sLang)
            $aJs[] = $sJsPrefix . 'boonex/froala/plugins/froala/js/languages/' . $sJsSuffix . $sLang . '.js';
        
        $aCss = array(
            BX_DIRECTORY_PATH_MODULES . 'boonex/froala/template/css/|editor.css',
            BX_DIRECTORY_PATH_MODULES . 'boonex/froala/plugins/froala/css/|froala_editor.pkgd.min.css', 
            BX_DIRECTORY_PATH_MODULES . 'boonex/froala/plugins/froala/css/|froala_style.min.css',
            BX_DIRECTORY_PATH_MODULES . 'boonex/froala/plugins/froala/css/themes/|' . $this->_sSkin . '.min.css',
        );

        $aPlugins = $this->_getPlugins(true);
        foreach ($aPlugins as $sPlugin) {
            $aJs[] = $sJsPrefix . 'boonex/froala/plugins/froala/js/third_party/' . $sJsSuffix . $sPlugin . '.min.js';
            $aCss[] = BX_DIRECTORY_PATH_MODULES . 'boonex/froala/plugins/froala/css/third_party/|' . $sPlugin . '.min.css';
        }

        $aPlugins = $this->_getPlugins(false);
        foreach ($aPlugins as $sPlugin) {
            $aJs[] = $sJsPrefix . 'boonex/froala/plugins/froala/js/plugins/' . $sJsSuffix . $sPlugin . '.min.js';
            $aCss[] = BX_DIRECTORY_PATH_MODULES . 'boonex/froala/plugins/froala/css/plugins/|' . $sPlugin . '.min.css';
        }
        
        return array($aJs, $aCss);
    }
}

/** @} */
