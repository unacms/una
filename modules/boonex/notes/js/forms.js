
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
    bx_editor_remove_img (
        [sEditorId, sSummaryId], 
        ['img[src="' + sFileIcon + '"]', 'img[src="' + sFileUrl + '"]', '.bx-notes-img-' + iFileId, '.bx-notes-icon-' + iFileId]
    );
    oUploaderInstance.deleteGhost(iFileId);
}

function bx_notes_insert_to_post (iFileId, sFileUrl, sEditorId) {
    bx_editor_insert_img (sEditorId, 'bx-notes-img-' + iFileId, sFileUrl, 'bx-notes-img');
}

