
function bx_cnv_delete(e, iConvoId) {
    return bx_cnv_action('delete', e, iConvoId, true);
}

function bx_cnv_mark_unread(e, iConvoId) {
    return bx_cnv_action('mark_unread', e, iConvoId, false);
}

function bx_cnv_action(sAction, e, iConvoId, isConfirm) {
    if (isConfirm && !confirm(_t('_are you sure?')))
        return false;

    bx_loading_btn(e, 1);
    $.post(sUrlRoot + 'modules/?r=convos/' + sAction + '/' + parseInt(iConvoId), function (s) {
        bx_loading_btn(e, 0);
        if (sUrlRoot == s.substring(0,sUrlRoot.length))
            location.href = s;
        else
            alert(s);
    });
}
