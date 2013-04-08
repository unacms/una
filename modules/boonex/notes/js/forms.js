
function bx_notes_select_thumb(eCheckbox) {
    var eCont = $(eCheckbox).parents('.bx-form-input-files-result');
    if (!eCont.length)
        return;
    eCont.find('.bx-notes-use-as-thumb input:checked').each (function () {        
        if ($(this).attr('id') != $(eCheckbox).attr('id'))
            $(this).css('border', '1px solid red').attr('checked', false);
    });
}

function bx_notes_delete_ghost (iFileId, sFileUrl, sFileIcon, sEditorId, sSummaryId, oUploaderInstance) {

    if ('object' == typeof(tinymce)) {
        var aEditors = [tinymce.get(sEditorId), tinymce.get(sSummaryId)];
        for (var j in aEditors) {
            var eEditor = aEditors[j];
            if ('object' == typeof(eEditor) && 'object' == typeof(eEditor) && eEditor.dom && eEditor.dom.doc) { 
                // delete images in html editor
                var aMarkers = [
                    'img[src="' + sFileIcon + '"]',
                    'img[src="' + sFileUrl + '"]',
                    '.bx-notes-img-' + iFileId,
                    '.bx-notes-icon-' + iFileId
                ];
                for (var i in aMarkers) {
                    var jFiles = $(eEditor.dom.doc).find(aMarkers[i]);
                    jFiles.each(function () { eEditor.execCommand('mceRemoveNode', false, this); });
                }
            }
        }
    }

    oUploaderInstance.deleteGhost(iFileId);
}

function bx_notes_insert_to_post (iFileId, sFileUrl, sEditorId) {

    var eEditor = 'object' == typeof(tinymce) ? tinymce.get(sEditorId) : undefined;
    if ('undefined' == typeof(eEditor))
        return;

    if (eEditor.dom && eEditor.dom.doc && eEditor.dom.doc.getElementById('note-img-' + iFileId))
        return;

    eEditor.execCommand('mceInsertContent', false, '<img class="bx-notes-img bx-notes-img-' + iFileId + '" src="' + sFileUrl + '" />');
}

