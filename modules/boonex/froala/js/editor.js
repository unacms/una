/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Froala Froala editor integration
 * @ingroup     UnaModules
 * 
 * @{
 */

function bx_editor_insert_img (sEditorId, sImgId, sImgUrl, sClasses) 
{
    if (typeof $('#' + sEditorId).froalaEditor !== 'function')
        return;
    
    if ('undefined' == typeof(sClasses))
        sClasses = '';

    $('#' + sEditorId).froalaEditor('html.insert', '<img id="' + sImgId + '" class="' + sClasses + '" src="' + sImgUrl + '" />', false);
}

function bx_editor_remove_img (aEditorIds, aMarkers) 
{
    for (var i = 0; i < aEditorIds.length; i++) {
        var eEditor = $('#' + aEditorIds[i]).parent();
        
        // delete images in html editor
        for (var k = 0; k < aMarkers.length; k++) {
            var jFiles = eEditor.find(aMarkers[k]);
            jFiles.each(function () {
                $(this).remove(); 
            });
        }
    }
}

function bx_editor_on_space_enter (sEditorId)
{
    if (typeof glBxEditorOnSpaceEnterTimer !== 'undefined')
        clearTimeout(glBxEditorOnSpaceEnterTimer);

    glBxEditorOnSpaceEnterTimer = setTimeout(function () {
        glBxEditorOnSpaceEnterTimer = undefined;
        if (typeof glOnSpaceEnterInEditor !== 'undefined' && glOnSpaceEnterInEditor instanceof Array) {
            for (var i = 0; i < glOnSpaceEnterInEditor.length; i++)
                if (typeof glOnSpaceEnterInEditor[i] === "function")
                    glOnSpaceEnterInEditor[i]($(sEditorId).froalaEditor('html.get'));
        }
    }, 800);
}

function bx_editor_get_htmleditable (sEditorSelector)
{
    if (!$(sEditorSelector).size())
        return false;
    return $(sEditorSelector).data('froala.editor').el;
}

/** @} */
