function BxMarketEntry(oOptions) {
	this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxMarketEntry' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

    var $this = this;
    $(document).ready(function() {
    	$this.initScreenshots();
    });
}

BxMarketEntry.prototype.initScreenshots = function() {
	var oItems = jQuery(".bx-market-screenshots .bx-market-ss-item");
	
	var iWidth = oItems.width();
	var iWidthOuter = oItems.outerWidth();
	var bBusy = false;

	oItems.find('a[rel=group]').fancybox({
		transitionIn: 'elastic',
		transitionOut: 'elastic',
		speedIn: 600,
		speedOut: 200
	});

	if(oItems.length <= 2)
		return;
	console.log(iWidth, iWidthOuter);
	jQuery(".bx-market-ss-left").bind('click', function() {
		if(bBusy || parseInt(jQuery(".bx-market-ss-cnt").css('left')) >= 0)
			return;

		bBusy = true;
		jQuery(".bx-market-ss-cnt").animate({left: '+=' + iWidthOuter}, 500, function() {
			bBusy = false;
		});
	});
	jQuery(".bx-market-ss-right").bind('click', function() {
		var iWidthParent = jQuery(".bx-market-ss-cnt").parent().width();

		if(bBusy || parseInt(jQuery(".bx-market-ss-cnt").css('left')) <= iWidthParent - jQuery(".bx-market-ss-cnt").width())
			return;

		bBusy = true;
		jQuery(".bx-market-ss-cnt").animate({left: '-=' + iWidthOuter}, 500, function() {
			bBusy = false;
		});
	});

	jQuery(".bx-market-screenshots").hover(function() {
		jQuery(".bx-market-ss-left, .bx-market-ss-right").bx_anim('show', 'fade', 'fast');
	}, function() {
		jQuery(".bx-market-ss-left, .bx-market-ss-right").bx_anim('hide', 'fade', 'fast');;
	});
};
