/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPostsMain(oOptions) {
	this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxPostsMain' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

    var $this = this;
    $(document).ready(function() {
    	$this.init();
    });
}

BxPostsMain.prototype.init = function() {
	$('.bx-base-text-unit-showcase-wrapper').flickity({
		cellSelector: '.bx-base-text-unit-gallery',
		cellAlign: 'left',
		pageDots: false,
		imagesLoaded: true
	});
};

/** @} */
