/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

function bx_editor_insert_img (sEditorId, sImgId, sImgUrl, sClasses) {

    var eEditor = 'object' == typeof(tinymce) ? tinymce.get(sEditorId) : undefined;
    if ('undefined' == typeof(eEditor))
        return;

    if (eEditor.dom && eEditor.dom.doc && eEditor.dom.doc.getElementById(sImgId))
        return;

    if ('undefined' == typeof(sClasses))
        sClasses = '';

    eEditor.execCommand('mceInsertContent', false, '<img id="' + sImgId + '" class="' + sClasses + '" src="' + sImgUrl + '" />');
}

function bx_editor_remove_img (aEditorIds, aMarkers) {

    if ('object' != typeof(tinymce))
        return;

    var aEditors = [];
    for (var i in aEditorIds)
        aEditors.push(tinymce.get(aEditorIds[i]));

    for (var j in aEditors) {
        var eEditor = aEditors[j];
        if ('object' == typeof(eEditor) && 'object' == typeof(eEditor) && eEditor.dom && eEditor.dom.doc) { 
            // delete images in html editor
            for (var i in aMarkers) {
                var jFiles = $(eEditor.dom.doc).find(aMarkers[i]);
                jFiles.each(function () { 
                    eEditor.execCommand('mceRemoveNode', false, this); 
                });
            }
        }
    }

}

/** @} */
