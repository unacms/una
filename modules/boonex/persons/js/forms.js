
function bx_persons_action_with_ghost (sAction, iFileId, iContentId, oUploader, sFieldName) {
    bx_loading("bx-uploader-file-" + iFileId, true);
    $.post(sUrlRoot + "modules/?r=persons/" + sAction + "/" + iFileId + "/" + iContentId + "/" + sFieldName, function(s) {
        bx_loading("bx-uploader-file-" + iFileId, false);
        if (s.length)
            alert(s);
        else
            oUploader.restoreGhosts();
    });
}

function bx_persons_discard_ghost (iFileId, iContentId, oUploader, sFieldName) {
    bx_persons_action_with_ghost ('discard_ghost', iFileId, iContentId, oUploader, sFieldName);
}

function bx_persons_delete_ghost (iFileId, iContentId, oUploader, sFieldName) {
    bx_persons_action_with_ghost ('delete_ghost', iFileId, iContentId, oUploader, sFieldName);
}

function bx_persons_toggle_draft (iFileId, iCurrentFileId, sFieldName) {
    if (iFileId == iCurrentFileId) {
        $("#bx-form-element-" + sFieldName + " .bx-uploader-ghost-current").show();
        $("#bx-form-element-" + sFieldName + " .bx-uploader-ghost-draft").hide();
    } else {
        $("#bx-form-element-" + sFieldName + " .bx-uploader-ghost-current").hide();
        $("#bx-form-element-" + sFieldName + " .bx-uploader-ghost-draft").show();
    }
}

