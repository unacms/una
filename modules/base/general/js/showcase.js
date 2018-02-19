$(document).ready(function () {
    bx_showcase_view_init();
});

function bx_showcase_view_init() {
    $('.bx-base-unit-showcase-wrapper').flickity({
        cellSelector: '.bx-base-unit-showcase',
        cellAlign: 'left',
        pageDots: false,
        imagesLoaded: true
    });
}