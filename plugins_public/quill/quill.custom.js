/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function bx_editor_insert_html (sEditorId, sImgId, sHtml) {
    quill_bx_posts_text.container.firstChild.innerHTML += sHtml;
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
        console.log(glOnSpaceEnterInEditor);
        if (typeof glOnSpaceEnterInEditor !== 'undefined' && glOnSpaceEnterInEditor instanceof Array) {
            for (var i = 0; i < glOnSpaceEnterInEditor.length; i++) {
                if (typeof glOnSpaceEnterInEditor[i] === "function") {;
                    console.log(10);     
                    console.log(oEditor.container.firstChild.innerHTML);     
                    glOnSpaceEnterInEditor[i](oEditor.container.firstChild.innerHTML, sEditorId);
                }
            }
        }
    }, 800);
}

/** @} */
