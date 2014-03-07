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
 * CLEditor editor representation.
 * @see BxDolEditor
 */
class BxBaseEditorCLEditor extends BxDolEditor {

    /**
     * Common initialization params
     */
    protected static $CONF_COMMON = "
                    jQuery('{bx_var_selector}').cleditor({
                        {bx_var_custom_init}
                        docCSSFile: '{bx_var_css_path}',
                        bodyStyle: 'cursor:text',
                        width: '100%'
                    });
    ";

    /**
     * Standard view initialization params
     */
    protected static $CONF_STANDARD = "
                        height: 250,
                        controls: 'bold italic underline removeformat | style | bullets numbering | outdent indent | alignleft center alignright | undo redo | rule image link unlink | pastetext | source',
    ";

    /**
     * Minimal view initialization params
     */
    protected static $CONF_MINI = "
                        height: 150,
                        controls: 'bold italic underline removeformat | bullets numbering | alignleft center alignright | link unlink image',
    ";

    /**
     * Full view initialization params
     */
    protected static $CONF_FULL = "
                        height: 300,
                        controls: 'bold italic underline removeformat | subscript superscript | style | bullets numbering | outdent indent | alignleft center alignright justify | undo redo | rule image link unlink | pastetext | print source',
    ";

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
     * @param $bDynamicMode - is AJAX mode or not, the HTML with editor area is loaded synamically.
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
                
        // initialize editor
        $sInitEditor = $this->_replaceMarkers(self::$CONF_COMMON, array(            
            'bx_var_custom_init' => $sToolsItems,
            'bx_var_selector' => bx_js_string($sSelector, BX_ESCAPE_STR_APOS),
            'bx_var_css_path' => bx_js_string($this->_oTemplate->getCssUrl('editor.css'), BX_ESCAPE_STR_APOS),
        ));

        if ($bDynamicMode) {

            $sScript = $this->_oTemplate->addCss(BX_DOL_URL_PLUGINS . 'cleditor/jquery.cleditor.css', true);
            $sScript .= "<script>
                if ('undefined' == typeof(jQuery(document).cleditor)) {
                    $.getScript('" . bx_js_string(BX_DOL_URL_BASE . 'inc/js/editor.cleditor.js', BX_ESCAPE_STR_APOS) . "');
                    $.getScript('" . bx_js_string(BX_DOL_URL_PLUGINS . 'cleditor/jquery.cleditor.min.js', BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
                        $sInitEditor
                    });
                } else {
                    setTimeout(function () {
                        $sInitEditor
                    }, 100);
                }
            </script>";            

        } else {

            $sScript = "<script>
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
        $this->_oTemplate->addCss(BX_DOL_URL_PLUGINS . 'cleditor/jquery.cleditor.css');
        $this->_oTemplate->addJs(array('editor.cleditor.js', BX_DOL_URL_PLUGINS . 'cleditor/jquery.cleditor.min.js'));
        $this->_bJsCssAdded = true;
        return '';
    }

}

/** @} */
