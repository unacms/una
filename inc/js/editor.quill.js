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
