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
    
    $(oParams.selector).after("<div id='" + oParams.name + "' class='bx-def-font-inputs bx-form-input-textarea bx-form-input-html bx-form-input-html-quill " + oParams.css_class + "'>" + $(oParams.selector).val() + "</div>" );
    $(oParams.selector).hide();
    
    $(oParams.selector).attr('object_editor', oParams.name);
                
    if (typeof bQuillRegistred === 'undefined' && oParams.toolbar) {
        Quill.register("modules/imageUploader", ImageUploader); 
        bQuillRegistred = true; 
    }

    var Embed = Quill.import('blots/embed');
    class EmbedLink extends Embed {
        static create(value) {
            let node = super.create(value);
            node.setAttribute('href', value.id);
            node.innerHTML = '@' + value.value;
            return node;
        }
    }
    EmbedLink.blotName = 'embed-link'; 
    EmbedLink.className = 'embed-link';
    EmbedLink.tagName = 'a';                
    Quill.register({
        'formats/embed-link': EmbedLink
    });

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
                blotName: 'embed-link',
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
                        console.error("Error:", error);
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
            oEditor.clipboard.dangerouslyPasteHTML(oEditor.getSelection().index,sHtml, 'api');
        else
             oEditor.clipboard.dangerouslyPasteHTML(0,sHtml, 'api');
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
                    console.log(oEditor.container.firstChild.innerHTML);                                                  
                    glOnSpaceEnterInEditor[i](oEditor.container.firstChild.innerHTML, sEditorId);
                }
            }
        }
    }, 800);
}

/** @} */
