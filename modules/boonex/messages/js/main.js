
function bx_msg_delete(e, iMsgId) {
    if (!confirm(_t('_are you sure?')))
        return false;

    bx_loading_btn(e, 1);
    $.post(sUrlRoot + 'modules/?r=messages/delete/' + parseInt(iMsgId), function (s) {
        bx_loading_btn(e, 0);
        if (sUrlRoot == s.substring(0,sUrlRoot.length))
            location.href = s;
        else
            alert(s);
    });
}
