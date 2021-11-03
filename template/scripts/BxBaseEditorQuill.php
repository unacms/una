<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Quill editor representation.
 * @see BxDolEditor
 */
class BxBaseEditorQuill extends BxDolEditor
{
    /**
     * Common initialization params
     */
    
    protected static $CONF_COMMON = <<<EOS
    
                hljs.configure({   // optionally configure hljs
                  languages: ['javascript', 'php', 'html', 'css']
                });
    
                $( "{bx_var_selector}" ).after( "<div id='editor_{bx_var_editor_name}' class='bx-def-font-inputs bx-form-input-textarea bx-form-input-html'>" + $( "{bx_var_selector}" ).val() + "</div>" );
                $( "{bx_var_selector}" ).hide();
                
                if (typeof bQuillRegistred === 'undefined') {
                    Quill.register("modules/imageUploader", ImageUploader); 
                    bQuillRegistred = true;
                }
                
                var quill_{bx_var_editor_name} = new Quill('#editor_{bx_var_editor_name}', {
                     theme: '{bx_var_skin}',
                     modules: {
                        syntax: true, 
                        toolbar: [{toolbar}],
                        mention: {
                           // allowedChars: /^[A-Za-z\sÅÄÖåäö]*$/,
                            mentionDenotationChars: ["@"],
                            source: function (searchTerm, renderList, mentionChar) {
                              $.getJSON('{bx_url_root}searchExtended.php?action=get_authors&', { term: searchTerm}, function(data){
                                renderList(data, searchTerm);
                              });
                            },
                            renderItem: function(item, searchTerm){
                                item.value = '<a href="' + item.url + '" target="_blank" >' + item.label + '</a>';
                                return '<a href="' + item.url + '" target="_blank" >@' + item.label + '</a>';
                            },
                            onSelect: function(item, insertItem){
                                insertItem(item)
                            }
                        },
                        imageUploader: {
                            upload: file => {
                                return new Promise((resolve, reject) => {
                                    const formData = new FormData();
                                    formData.append("file", file);
                                    fetch("{bx_url_root}storage.php?o=sys_images_editor&t=sys_images_editor&a=upload", {
                                            method: "POST",
                                            body: formData
                                        }
                                    )
                                    .then(response => response.json())
                                    .then(result => {
                                        console.log(result);
                                        resolve(result.link);
                                    })
                                    .catch(error => {
                                        reject("Upload failed");
                                        console.error("Error:", error);
                                    });
                                });
                            }
                        }
                    }
                });
                
                quill_{bx_var_editor_name}.on('text-change', function(delta, oldDelta, source) {
                    $('{bx_var_selector}').val(quill_{bx_var_editor_name}.container.firstChild.innerHTML);
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
    public function attachEditor ($sSelector, $iViewMode = BX_EDITOR_STANDARD, $bDynamicMode = false, $aAttrs = [])
    {
        // set visual mode
        switch ($iViewMode) {
        case BX_EDITOR_MINI:
                $sToolbarItems = getParam('sys_quill_toolbar_mini');
                $sCustomInit = self::$CONF_MINI;
                break;
                
            case BX_EDITOR_FULL:
                $sToolbarItems = getParam('sys_quill_toolbar_full');
                $sCustomInit = self::$CONF_FULL;
            break;
                
            case BX_EDITOR_STANDARD:
            default:
                $sToolbarItems = getParam('sys_quill_toolbar_standard');
                $sCustomInit = self::$CONF_STANDARD;
        }
        
        if ($this->_sButtonsCustom !== false) {
            $sToolbarItems = $this->_sButtonsCustom;
        }
        
        $sCss = 'editor.less';
        $aCss = BxDolTemplate::getInstance()->_lessCss(array(
        	'path' => $this->_oTemplate->getCssPath($sCss),
        	'url' => $this->_oTemplate->getCssUrl($sCss)
        ));
        // initialize editor
        $sInitEditor = $this->_replaceMarkers(self::$CONF_COMMON, array(
            'bx_var_custom_init' => $sCustomInit,
            'bx_var_selector' => bx_js_string($sSelector, BX_ESCAPE_STR_APOS),
            'bx_var_form_id' => $aAttrs['form_id'],
            'toolbar' => $sToolbarItems ? $sToolbarItems : "[]",
            'bx_var_element_name' => str_replace(['-', ' '], '_', $aAttrs['element_name']),
            'bx_var_editor_name' => str_replace(['-', ' '], '_', $aAttrs['form_id'] . '_' . $aAttrs['element_name']),
            'bx_var_skin' => bx_js_string($this->_aObject['skin'], BX_ESCAPE_STR_APOS),
            'bx_url_root' => bx_js_string(BX_DOL_URL_ROOT, BX_ESCAPE_STR_APOS),
            'bx_var_css_path' => bx_js_string($aCss['url'], BX_ESCAPE_STR_APOS),
        ));

        if ($bDynamicMode) {

            list($aJs, $aCss) = $this->_getJsCss(true);
            $sCss = $this->_oTemplate->addCss($aCss, true);
            
            $sCss = $this->_oTemplate->addCss([
                BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'quill/|quill.' . $this->_aObject['skin'] . '.css', 
                BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'quill/|quill.custom.css', 
                BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'quill/|quill.mention.css', 
                BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'highlight/|default.min.css',
                BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'quill/quill.imageUploader.min.css'
            ], true);
            $sScript = $sCss . "<script>
                if (typeof bQuillEditorInited === 'undefined') {
                    bx_get_scripts(" . json_encode($aJs) . ", function () {
                        bQuillEditorInited = true;
                        $sInitEditor
                    });
                } else {
                	setTimeout(function () {
                     console.log(999);
                    	$sInitEditor
                    }, 10); // wait while html is rendered in case of dynamic adding html with tinymce
                } 
                if (typeof bQuillEditorInited === 'undefined') 
                    bQuillEditorInited = true;
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
        
        list($aJs, $aCss) = $this->_getJsCss();

        $this->_oTemplate->addJs($aJs);
        $this->_oTemplate->addCss($aCss);
        
        $this->_bJsCssAdded = true;
        
        return '';
    }
    
    protected function _getJsCss($bUseUrlsForJs = false)
    {
        $sJsPrefix = $bUseUrlsForJs ? BX_DOL_URL_PLUGINS_PUBLIC : BX_DIRECTORY_PATH_PLUGINS_PUBLIC;
        $sJsSuffix = $bUseUrlsForJs ? '' : '|';
        
        $aJs = array(
            $sJsPrefix . 'highlight/' . $sJsSuffix . 'highlight.min.js',
            $sJsPrefix . 'quill/' . $sJsSuffix . 'quill.min.js', 
            $sJsPrefix . 'quill/' . $sJsSuffix . 'quill.mention.js',  
            $sJsPrefix . 'quill/' . $sJsSuffix . 'quill.imageUploader.min.js'
        );

        $aCss = array(
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'quill/|quill.' . $this->_aObject['skin'] . '.css', 
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'quill/|quill.custom.css', 
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'quill/|quill.mention.css', 
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'highlight/|default.min.css',
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'quill/quill.imageUploader.min.css'
        );

        return array($aJs, $aCss);
    }
}

/** @} */
