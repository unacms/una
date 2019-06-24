/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Froala Froala editor integration
 * @ingroup     UnaModules
 * 
 * @{
 */

function bx_editor_insert_html (sEditorId, sImgId, sHtml) 
{
    var oEditor = $('#' + sEditorId).data('froala-instance');
    if (typeof oEditor !== 'object')
        return;

    oEditor.html.insert(sHtml, false);
}

function bx_editor_insert_img (sEditorId, sImgId, sImgUrl, sClasses) 
{   
    if ('undefined' == typeof(sClasses))
        sClasses = '';

    bx_editor_insert_html(sEditorId, sImgId, '<img id="' + sImgId + '" class="' + sClasses + '" src="' + sImgUrl + '" />');
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

function bx_editor_on_init (sEditorId)
{
    if (typeof glOnInitEditor !== 'undefined' && glOnInitEditor instanceof Array) {
        for (var i = 0; i < glOnInitEditor.length; i++)
            if (typeof glOnInitEditor[i] === "function")
                glOnInitEditor[i](sEditorId);
    }
}

function bx_editor_on_space_enter (sEditorId)
{
    if (typeof glBxEditorOnSpaceEnterTimer !== 'undefined')
        clearTimeout(glBxEditorOnSpaceEnterTimer);

    glBxEditorOnSpaceEnterTimer = setTimeout(function () {
        glBxEditorOnSpaceEnterTimer = undefined;
        if (typeof glOnSpaceEnterInEditor !== 'undefined' && glOnSpaceEnterInEditor instanceof Array) {
            for (var i = 0; i < glOnSpaceEnterInEditor.length; i++) {
                if (typeof glOnSpaceEnterInEditor[i] === "function") {
                    var oEditor = $(sEditorId).data('froala-instance');
                    glOnSpaceEnterInEditor[i](oEditor.html.get(), sEditorId);
                }
            }
        }
    }, 800);
}

function bx_editor_get_htmleditable (sEditorSelector)
{
    if (!$(sEditorSelector).size())
        return false;
    var oEditor = $(sEditorSelector).data('froala-instance');
    return oEditor.el;
}

function bx_editor_remove_all (oElement) 
{
}

/** @} */
