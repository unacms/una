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
    /**
     * Common initialization params
     */
    protected static $CONF_COMMON = <<<EOS
                    jQuery('{bx_var_selector}').froalaEditor();
EOS;

    /**
     * Standard view initialization params
     */
    protected static $CONF_STANDARD = "
    ";

    /**
     * Minimal view initialization params
     */
    protected static $CONF_MINI = "
    ";

    /**
     * Full view initialization params
     */
    protected static $CONF_FULL = "
    ";

    protected $_sConfCustom = '';

    /**
     * Available editor languages
     */
    protected static $CONF_LANGS = array('en' => 1);

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
     * Set custom configuration option
     */
    public function setCustomConf ($s)
    {
        $this->_sConfCustom = $s;
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
        ));

        if ($bDynamicMode) {

            $sCss = $this->_oTemplate->addCss(array(
                BX_DIRECTORY_PATH_MODULES . 'boonex/froala/plugins/froala/css/|froala_editor.pkgd.min.css', 
                BX_DIRECTORY_PATH_MODULES . 'boonex/froala/plugins/froala/css/|froala_style.min.css',
            ), true);
            $sScript = $sCss . "<script>
                if ('undefined' == typeof(jQuery(document).froalaEditor)) {
                    $.getScript('" . bx_js_string(BX_DOL_URL_MODULES . 'boonex/froala/js/editor.js', BX_ESCAPE_STR_APOS) . "');
                    $.getScript('" . bx_js_string(BX_DOL_URL_MODULES . 'boonex/froala/plugins/froala/js/froala_editor.pkgd.min.js', BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
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

    /**
     * Add css/js files which are needed for editor display and functionality.
     */
    protected function _addJsCss($bDynamicMode = false, $sInitEditor = '')
    {
        if ($bDynamicMode)
            return '';
        if ($this->_bJsCssAdded)
            return '';
        $this->_oTemplate->addJs(array(
            BX_DIRECTORY_PATH_MODULES . 'boonex/froala/plugins/froala/js/|froala_editor.pkgd.min.js',
        ));
        $this->_oTemplate->addCss(array(
            BX_DIRECTORY_PATH_MODULES . 'boonex/froala/plugins/froala/css/|froala_editor.pkgd.min.css', 
            BX_DIRECTORY_PATH_MODULES . 'boonex/froala/plugins/froala/css/|froala_style.min.css',
        ));
        $this->_bJsCssAdded = true;
        return '';
    }

}

/** @} */
