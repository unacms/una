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
    
                $( "{bx_var_selector}" ).after( "<div id='{bx_var_editor_name}' class='bx-def-font-inputs bx-form-input-textarea bx-form-input-html bx-form-input-html-quill {bx_var_css_additional_class}'>" + $( "{bx_var_selector}" ).val() + "</div>" );
                //$( "{bx_var_selector}" ).hide();
                
                if (typeof bQuillRegistred === 'undefined' && {toolbar}) {
                    Quill.register("modules/imageUploader", ImageUploader); 
                    bQuillRegistred = true; 
                }
                var Embed = Quill.import('blots/embed');
                class ProcLink extends Embed {
                    static create(value) {
                        let node = super.create(value);
                        // give it some margin
                        node.setAttribute('href', value.id);
                        node.innerHTML = '@' + value.value;
                        return node;
                    }
                }
                ProcLink.blotName = 'proc-link'; 
                ProcLink.className = 'proc-link';
                ProcLink.tagName = 'a';                
                Quill.register({
                    'formats/proc-link': ProcLink
                });
                
                var oConfig = {              
                     theme: '{bx_var_skin}',
                     modules: {
                        syntax: true, 
                        imageResize: {},
                        toolbar: {toolbar},
                        mention: {
                            allowedChars: /^[A-Za-z\sÅÄÖåäö]*$/,
                            mentionDenotationChars: ["@"],
                            showDenotationChar: false,
                            blotName: 'proc-link',
                            source: function (searchTerm, renderList, mentionChar) {
                              $.getJSON('{bx_url_root}searchExtended.php?action=get_authors&', { term: searchTerm}, function(data){
                                renderList(data, searchTerm);
                              });
                            },
                            renderItem: function(item, searchTerm){
                              item.id = item.url;
                              item.value = item.label;
                              return '@' + item.value;
                            },
                            onSelect: function(item, insertItem){
                                insertItem(item, false)
                            }
                        },
                    }
                };
                if ({toolbar}){
                    oConfig.modules.imageUploader = {
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
                
                {bx_var_editor_name} = new Quill('#{bx_var_editor_name}', oConfig);
                {bx_var_editor_name}.keyboard.addBinding({
                    key: ' ',
                    handler: function(range, context) {
                        bx_editor_on_space_enter ({bx_var_editor_name}, '{bx_var_selector}');
                        return true;
                    }
                });
                {bx_var_editor_name}.keyboard.bindings[13].unshift({
                    key: 13,
                    handler: (range, context) => {
                        bx_editor_on_space_enter ({bx_var_editor_name}, '{bx_var_selector}')
                        return true;
                    }
                });
                {bx_var_editor_name}.on('text-change', function(delta, oldDelta, source) {
                    $('{bx_var_selector}').val({bx_var_editor_name}.container.firstChild.innerHTML);
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
        
        $sEditorName = 'quill_' . str_replace(['-', ' '], '_', $aAttrs['form_id'] . '_' . $aAttrs['element_name']);
        
        // initialize editor
        $sInitEditor = $this->_replaceMarkers(self::$CONF_COMMON, array(
            'bx_var_custom_init' => $sCustomInit,
            'bx_var_selector' => bx_js_string($sSelector, BX_ESCAPE_STR_APOS),
            'bx_var_form_id' => $aAttrs['form_id'],
            'toolbar' => $sToolbarItems ? '[' . $sToolbarItems . ']' : 'false',
            'bx_var_css_additional_class' => $sToolbarItems ? '' : 'bx-form-input-html-quill-empty',
            'bx_var_element_name' => str_replace(['-', ' '], '_', $aAttrs['element_name']),
            'bx_var_editor_name' => $sEditorName,
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
                    	$sInitEditor
                    }, 10); // wait while html is rendered in case of dynamic adding html with tinymce
                } 
                if (typeof bQuillEditorInited === 'undefined') 
                    bQuillEditorInited = true;
            </script>";

        } else {

            $sScript = "
            <script>
                var " . $sEditorName . ";
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
            $sJsPrefix . 'quill/' . $sJsSuffix . 'quill.imageUploader.min.js',
            $sJsPrefix . 'quill/' . $sJsSuffix . 'quill.custom.js', 
            $sJsPrefix . 'quill/' . $sJsSuffix . 'image-resize.min.js', 
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
