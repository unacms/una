$(document).ready(function () {
    var ePreviews = $('.bx-forum-grid-preview');
    if (!ePreviews.length)
        return;

    var f = function () {

        // calculate width of all table rows, except the one which contains messages preview
        var iInnerWidth = $('.bx-grid-table').parent().innerWidth();
        var iCalcWidth = 0;
        $('.bx-grid-table tbody tr:first-child td').each(function () {
            if (!$(this).find('.bx-forum-grid-preview').length)
                iCalcWidth += $(this).outerWidth();
        });

        // set width for messages previews
        $('.bx-forum-grid-preview').each(function () {
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


