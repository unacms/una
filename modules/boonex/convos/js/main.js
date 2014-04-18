
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

$(document).ready(function () {
    var ePreviews = $('.bx-cnv-grid-preview');
    if (!ePreviews.length)
        return;

    var f = function () {

        // calculate width of all table rows, except the one which contains messages preview
        var iInnerWidth = $('.bx-grid-table').parent().innerWidth();
        var iCalcWidth = 0;
        $('.bx-grid-table tbody tr:first-child td').each(function () {
            if (!$(this).find('.bx-cnv-grid-preview').length)
                iCalcWidth += $(this).outerWidth();
        });

        // set width for messages previews
        $('.bx-cnv-grid-preview').each(function () {
            var eWrapper = $(this).parent();
            var w = iInnerWidth - iCalcWidth - parseInt(eWrapper.css('padding-left')) - parseInt(eWrapper.css('padding-right'));
            $(this).width(w + 'px');
        }); 
    };

    $(window).resize(function() {
        f();
    });

    BxDolGrid.prototype.onDataReloaded = function (isSkipSearchInput) {
        f();
    };

    f();
});


