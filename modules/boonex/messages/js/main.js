
function bx_msg_delete(e, iMsgId) {
    return bx_msg_action('delete', e, iMsgId, true);
}

function bx_msg_mark_unread(e, iMsgId) {
    return bx_msg_action('mark_unread', e, iMsgId, false);
}

function bx_msg_action(sAction, e, iMsgId, isConfirm) {
    if (isConfirm && !confirm(_t('_are you sure?')))
        return false;

    bx_loading_btn(e, 1);
    $.post(sUrlRoot + 'modules/?r=messages/' + sAction + '/' + parseInt(iMsgId), function (s) {
        bx_loading_btn(e, 0);
        if (sUrlRoot == s.substring(0,sUrlRoot.length))
            location.href = s;
        else
            alert(s);
    });
}
