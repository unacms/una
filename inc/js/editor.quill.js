/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */
function bx_editor_init(oEditor, oParams){
    hljs.configure({   
        languages: ['javascript', 'php', 'html', 'css']
    });
    if (oParams.toolbar){
        oParams.toolbar = {
            container: oParams.toolbar,
                handlers: {
                'embed': function(value) {
                    bx_prompt(_t('_sys_txt_quill_tooltip_embed_popup_header'), '', function(oPopup){
                        sLink = $(oPopup).find("input[type = 'text']").val()
                        $.getJSON(oParams.root_url + 'embed.php?', {a: 'get_link', l: sLink}, function(aData){
                            oEditor.insertEmbed(100, 'embed-link', {source: sLink, inlinecode : aData.code});
                            if ($(oParams.selector).next().next().find('.bx-embed-link a[href="' + aData.link + '"]').length > 0)
                                bx_embed_link($(oParams.selector).next().next().find('.bx-embed-link a[href="' + aData.link + '"]')[0]);
                            else
                                bx_embed_link();
                        });
                    });
                },
                'link': function(value) {
                    if (value) {
                        bx_prompt(_t('_sys_txt_quill_tooltip_link_popup_header'), '', function(oPopup){
                            oEditor.format('link', $(oPopup).find("input[type = 'text']").val());
                        });
                    } else {
                        oEditor.format('link', false);
                    }
                }
            }
        }
    }
    $(oParams.selector).after("<div id='" + oParams.name + "' class='bx-def-font-inputs bx-form-input-textarea bx-form-input-html bx-form-input-html-quill " + oParams.css_class + "'>" + $(oParams.selector).val() + "</div>" );
    $(oParams.selector).hide();
    
    $(oParams.selector).attr('object_editor', oParams.name);
                
    if (typeof bQuillRegistred === 'undefined' && oParams.toolbar) {
        
        var Embed = Quill.import('blots/embed');
        
        class MenthionLink extends Embed {
            static create(value) {
                let node = super.create(value);
                if (value.id && value.value){
                    node.setAttribute('href', value.id);
                    node.innerHTML = '@' + value.value;
                    node.setAttribute('title', '@' + value.value);
                }
                return node;
            }
            
            format(name, value) {
                if (name === 'href' || name === 'title') {
                    if (value) {
                        this.domNode.setAttribute(name, value);
                        if (name === 'title'){
                            this.domNode.innerHTML = value;
                        }
                    } else {
                        this.domNode.removeAttribute(name, value);
                    }
                } 
                else {
                    super.format(name, value);
                }
            };
                
            static formats(node) {
                let format = {};
                if (node.hasAttribute('href')) {
                    format.href = node.getAttribute('href');
                }
                if (node.hasAttribute('title')) {
                    format.title = node.getAttribute('title');
                }
                return format;
            }  
            
        }
        MenthionLink.blotName = 'menthion-link'; 
        MenthionLink.className = 'bx-menthion-link';
        MenthionLink.tagName = 'a';                
        Quill.register({
            'formats/menthion-link': MenthionLink
        });
        
        class EmbedCode extends Embed {
            static create(value) {
                let node = super.create(value);
                if (value.source && value.inlinecode){
                    node.setAttribute('source', value.source);
                    node.innerHTML = value.inlinecode;
                    return node;
                }
                return node;
            };
           
            format(name, value) {
                if (name === 'source') {
                    if (value) {
                        this.domNode.setAttribute(name, value);
                    } else {
                        this.domNode.removeAttribute(name, value);
                    }
                } 
                else {
                    super.format(name, value);
                }
            };
                
            static formats(node) {
                let format = {};
                if (node.hasAttribute('source')) {
                  format.source = node.getAttribute('source');
                }
                return format;
            }    
        }
        EmbedCode.className = 'bx-embed-link';
        EmbedCode.blotName = 'embed-link'; 
        EmbedCode.tagName = 'div';                
        Quill.register({
            'formats/embed-link': EmbedCode
        });
        
        
        
        bQuillRegistred = true; 
        
        var icons = Quill.import('ui/icons');
        icons['embed'] = '<svg class="fr-svg" focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path class="ql-fill" d="M20.73889,15.45929a3.4768,3.4768,0,0,0-5.45965-.28662L9.5661,12.50861a3.49811,3.49811,0,0,0-.00873-1.01331l5.72174-2.66809a3.55783,3.55783,0,1,0-.84527-1.81262L8.70966,9.6839a3.50851,3.50851,0,1,0,.0111,4.63727l5.7132,2.66412a3.49763,3.49763,0,1,0,6.30493-1.526ZM18.00745,5.01056A1.49993,1.49993,0,1,1,16.39551,6.3894,1.49994,1.49994,0,0,1,18.00745,5.01056ZM5.99237,13.49536a1.49989,1.49989,0,1,1,1.61194-1.37878A1.49982,1.49982,0,0,1,5.99237,13.49536Zm11.78211,5.494a1.49993,1.49993,0,1,1,1.61193-1.37885A1.49987,1.49987,0,0,1,17.77448,18.98932Z"></path></svg>';
    }
    
    var oConfig = {              
         theme: oParams.skin,
         modules: {
            syntax: true, 
            imageResize: {},
            toolbar: oParams.toolbar,
            mention: {
                allowedChars: /^[A-Za-z\sÅÄÖåäö]*$/,
                mentionDenotationChars: ["@"],
                showDenotationChar: false,
                blotName: 'menthion-link',
                source: function (searchTerm, renderList, mentionChar) {
                  $.getJSON(oParams.root_url + 'searchExtended.php?action=get_authors&', { term: searchTerm}, function(data){
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
    
    if (oParams.toolbar){
        oConfig.modules.imageUploader = {
            upload: file => {
                return new Promise((resolve, reject) => {
                    const formData = new FormData();
                    formData.append("file", file);
                    fetch(oParams.root_url + "storage.php?o=sys_images_editor&t=sys_images_editor&a=upload", {
                            method: "POST",
                            body: formData
                        }
                    )
                    .then(response => response.json())
                    .then(result => {
                        resolve(result.link);
                    })
                    .catch(error => {
                        reject("Upload failed");
                    });
                });
            }
        }
    }

    oEditor = new Quill('#' + oParams.name, oConfig)
    $(oParams.selector).next().find('button.ql-bold').attr('title', _t('_sys_txt_quill_tooltip_bold'));
    $(oParams.selector).next().find('button.ql-italic').attr('title', _t('_sys_txt_quill_tooltip_italic'));
    $(oParams.selector).next().find('button.ql-underline').attr('title', _t('_sys_txt_quill_tooltip_underline'));
    $(oParams.selector).next().find('button.ql-clean').attr('title', _t('_sys_txt_quill_tooltip_clean'));
    $(oParams.selector).next().find('button.ql-list[value = "ordered"]').attr('title', _t('_sys_txt_quill_tooltip_list_ordered'));
    $(oParams.selector).next().find('button.ql-list[value = "bullet"]').attr('title', _t('_sys_txt_quill_tooltip_list_bullet'));
    $(oParams.selector).next().find('button.ql-indent[value = "-1"]').attr('title', _t('_sys_txt_quill_tooltip_indent_1'));
    $(oParams.selector).next().find('button.ql-indent[value = "+1"]').attr('title', _t('_sys_txt_quill_tooltip_indent_2'));
    $(oParams.selector).next().find('button.ql-blockquote').attr('title', _t('_sys_txt_quill_tooltip_blockquote'));
    $(oParams.selector).next().find('button.ql-direction').attr('title', _t('_sys_txt_quill_tooltip_direction'));
    $(oParams.selector).next().find('button.ql-script[value="sub"]').attr('title', _t('_sys_txt_quill_tooltip_script_sub'));
    $(oParams.selector).next().find('button.ql-script[value="super"]').attr('title', _t('_sys_txt_quill_tooltip_script_super'));
    $(oParams.selector).next().find('button.ql-link').attr('title', _t('_sys_txt_quill_tooltip_link'));
    $(oParams.selector).next().find('button.ql-image').attr('title', _t('_sys_txt_quill_tooltip_image'));
    $(oParams.selector).next().find('button.ql-code-block').attr('title', _t('_sys_txt_quill_tooltip_code_block'));
    $(oParams.selector).next().find('span.ql-color').attr('title', _t('_sys_txt_quill_tooltip_color'));
    $(oParams.selector).next().find('span.ql-background').attr('title', _t('_sys_txt_quill_tooltip_background'));
    $(oParams.selector).next().find('span.ql-align').attr('title', _t('_sys_txt_quill_tooltip_align'));
    $(oParams.selector).next().find('span.ql-header').attr('title', _t('_sys_txt_quill_tooltip_header'));
     
    
    oEditor.keyboard.addBinding({
        key: ' ',
        handler: function(range, context) {
            bx_editor_on_space_enter (oEditor, oParams.selector);
            return true;
        }
    });
    
    oEditor.keyboard.bindings[13].unshift({
        key: 13,
        handler: (range, context) => {
            bx_editor_on_space_enter (oEditor, oParams.selector)
            return true;
        }
    });
    
    oEditor.on('text-change', function(delta, oldDelta, source) {
        $(oParams.selector).val(oEditor.container.firstChild.innerHTML);
    });
    
    return oEditor
}
    
function bx_editor_insert_html (sEditorId, sImgId, sHtml) 
{
    if ($('#' + sEditorId) &&  $('#' + sEditorId).attr('object_editor')){
        eval ('oEditor = ' + $('#' + sEditorId).attr('object_editor'));
        if (oEditor.getSelection())
            oEditor.clipboard.dangerouslyPasteHTML(oEditor.getSelection().index, sHtml, 'api');
        else
             oEditor.clipboard.dangerouslyPasteHTML(0, sHtml, 'api');
    }
}

function bx_editor_insert_img (sEditorId, sImgId, sImgUrl, sClasses) {

    if ('undefined' == typeof(sClasses))
        sClasses = '';
    
    bx_editor_insert_html(sEditorId, sImgId, '<img id="' + sImgId + '" class="' + sClasses + '" src="' + sImgUrl + '" />')
}

function bx_editor_on_space_enter (oEditor, sEditorId)
{
    if (typeof glBxEditorOnSpaceEnterTimer !== 'undefined')
        clearTimeout(glBxEditorOnSpaceEnterTimer);
    
    glBxEditorOnSpaceEnterTimer = setTimeout(function () {
        glBxEditorOnSpaceEnterTimer = undefined;
        if (typeof glOnSpaceEnterInEditor !== 'undefined' && glOnSpaceEnterInEditor instanceof Array) {
            for (var i = 0; i < glOnSpaceEnterInEditor.length; i++) {
                if (typeof glOnSpaceEnterInEditor[i] === "function") {;                                             
                    glOnSpaceEnterInEditor[i](oEditor.container.firstChild.innerHTML, sEditorId);
                }
            }
        }
    }, 800);
}

/** @} */
