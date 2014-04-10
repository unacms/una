
function bx_base_text_select_thumb(eCheckbox) {
    var eCont = $(eCheckbox).parents('.bx-form-input-files-result');
    if (!eCont.length)
        return;
    eCont.find('.bx-base-text-use-as-thumb input:checked').each (function () {        
        if ($(this).attr('id') != $(eCheckbox).attr('id'))
            $(this).css('border', '1px solid red').attr('checked', false);
    });
}

function bx_base_text_delete_ghost (iFileId, sFileUrl, sFileIcon, sEditorId, sSummaryId, oUploaderInstance) {
    bx_editor_remove_img (
        [sEditorId, sSummaryId], 
        ['img[src="' + sFileIcon + '"]', 'img[src="' + sFileUrl + '"]', '.bx-base-text-img-' + iFileId, '.bx-base-text-icon-' + iFileId]
    );
    oUploaderInstance.deleteGhost(iFileId);
}

function bx_base_text_insert_to_post (iFileId, sFileUrl, sEditorId) {
    bx_editor_insert_img (sEditorId, 'bx-base-text-img-' + iFileId, sFileUrl, 'bx-base-text-img');
}

