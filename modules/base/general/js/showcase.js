$(document).ready(function () {
    BxShowcaseViewInit();
});

function BxShowcaseViewInit() {
    $('.bx-base-unit-showcase-wrapper').flickity({
        cellSelector: '.bx-base-unit-showcase',
        cellAlign: 'left',
        pageDots: false,
        imagesLoaded: true
    });
}