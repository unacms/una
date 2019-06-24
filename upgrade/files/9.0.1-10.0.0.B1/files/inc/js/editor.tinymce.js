/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function bx_editor_insert_html (sEditorId, sImgId, sHtml) {
    
    var eEditor = 'object' == typeof(tinymce) ? tinymce.get(sEditorId) : undefined;
    if ('undefined' == typeof(eEditor))
        return;

    if (eEditor.dom && eEditor.dom.doc && eEditor.dom.doc.getElementById(sImgId))
        return;

    eEditor.execCommand('mceInsertContent', false, sHtml);
}

function bx_editor_insert_img (sEditorId, sImgId, sImgUrl, sClasses) {

    if ('undefined' == typeof(sClasses))
        sClasses = '';
    
    bx_editor_insert_html(sEditorId, sImgId, '<img id="' + sImgId + '" class="' + sClasses + '" src="' + sImgUrl + '" />')
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
    // TODO:
}

function bx_editor_remove_img (aEditorIds, aMarkers) {
    if ('object' != typeof(tinymce))
        return;

    var aEditors = [];
    for (var i = 0; i < aEditorIds.length; i++)
        aEditors.push(tinymce.get(aEditorIds[i]));

    for (var j = 0; j < aEditors.length; j++) {
        var eEditor = aEditors[j];
        if (typeof(eEditor) != 'object' || null === eEditor || typeof(eEditor.dom) != 'object' || typeof(eEditor.dom.doc) != 'object') 
        	continue;

        // delete images in html editor
        for (var k = 0; k < aMarkers.length; k++) {
            var jFiles = $(eEditor.dom.doc).find(aMarkers[k]);
            jFiles.each(function () { 
                eEditor.execCommand('mceRemoveNode', false, this); 
            });
        }
    }
}

function bx_editor_get_htmleditable (sEditorSelector)
{
    if (!$(sEditorSelector).size())
        return false;

    var sId = $(sEditorSelector).attr('id');
    
    var eEditor = 'object' == typeof(tinymce) ? tinymce.get(sId) : undefined;
    if ('undefined' == typeof(eEditor) || !eEditor.dom)
        return false;

    return $(eEditor.dom.doc).find('body').get(0);
}

function bx_editor_remove_all (oElement) 
{
    if ('undefined' == typeof(oElement))
        oElement = window;
    oElement = $(oElement);

    if ('undefined' == typeof(tinymce) || !tinymce.editors.length)
        return;
    $.each(tinymce.editors, function () {
        if ($(this.getElement()).parents().filter(oElement).length)
            this.remove();
    });
}

/** @} */
