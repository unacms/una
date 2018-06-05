$(document).ready(function () {
    bx_showcase_view_init();
});

function bx_showcase_view_init() {
	if($('.bx-base-unit-showcase-wrapper').closest('.bx-popup-wrapper').length == 0) {
		var oShowcase = $('.bx-base-unit-showcase-wrapper');
		var oShowcaseOptions = {
			cellSelector: '.bx-base-unit-showcase',
			cellAlign: 'left',
			pageDots: false,
			imagesLoaded: true
		};
		
		var iGroupCells = oShowcase.attr('bx-sc-group-cells');
		if(iGroupCells != undefined)
			oShowcaseOptions.groupCells = parseInt(iGroupCells);

		oShowcase.flickity(oShowcaseOptions);
	}
}