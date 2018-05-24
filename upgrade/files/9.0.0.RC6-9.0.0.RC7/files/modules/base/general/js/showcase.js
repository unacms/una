$(document).ready(function () {
    bx_showcase_view_init();
});

function bx_showcase_view_init() {
	if($('.bx-base-unit-showcase-wrapper').closest('.bx-popup-wrapper').length == 0){
		$('.bx-base-unit-showcase-wrapper').flickity({
			cellSelector: '.bx-base-unit-showcase',
			cellAlign: 'left',
			pageDots: false,
			imagesLoaded: true
		});
	}
}