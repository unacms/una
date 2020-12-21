$(document).ready(function () {
    bx_showcase_view_init();

    $(window).on('resize', function() {
        bx_showcase_view_init();
    });
});

function bx_showcase_view_init() {
    if($('.bx-base-unit-showcase-wrapper').closest('.bx-popup-wrapper').length != 0)
        return;

    $('.bx-base-unit-showcase-wrapper').each(function() {
        var sClassCell = 'bx-base-unit-showcase';
        var oShowcase = $(this);
        var oCells = oShowcase.find('.' + sClassCell);

        if(oCells.width() * oCells.length <= oShowcase.parent().width()) {
            if(oShowcase.hasClass('flickity-enabled'))
                oShowcase.flickity('destroy');

            return;
        }

        var oShowcaseOptions = {
            cellSelector: '.' + sClassCell,
            cellAlign: 'left',
            imagesLoaded: true,
            wrapAround: true,
            pageDots: false
        };

        var iGroupCells = oShowcase.attr('bx-sc-group-cells');
        if(iGroupCells != undefined)
            oShowcaseOptions.groupCells = parseInt(iGroupCells);

        oShowcase.flickity(oShowcaseOptions);
    });
}