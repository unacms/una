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

BxMarketEntry.prototype.updateName = function(sTitleId, sNameId) {
	var sTitle = jQuery("[name='" + sTitleId + "']").val();
	sTitle = sTitle.replace(/[^A-Za-z0-9_]/g, '-');
	sTitle = sTitle.replace(/[-]{2,}/g, '-');

	jQuery("[name='" + sNameId + "']").val(sTitle.toLowerCase());
};

BxMarketEntry.prototype.checkName = function(sTitleId, sNameId) {
	var oDate = new Date();

	var sTitle = jQuery("[name='" + sTitleId + "']").val();
	if(sTitle.length == 0)
		return;

	jQuery.get(
		this._sActionsUrl + 'check_name',
		{
			title: sTitle,
    		_t: oDate.getTime()
    	},
    	function(oData) {
    		if(!oData || oData.name == undefined)
    			return;

    		jQuery("[name='" + sNameId + "']").val(oData.name);
    	},
    	'json'
	);
};

BxMarketEntry.prototype.changeFileType = function(oSelect) {
	var sValue = jQuery(oSelect).val();

	jQuery(oSelect).parents('.bx-uploader-ghost:first').find('.bx-uploader-ghost-type-rel:visible').bx_anim('hide', 'fade', 'fast', function() {
		jQuery(this).parent().find('.bx-uploader-ghost-type-' + sValue).bx_anim('show', 'fade', 'fast');
	});
};

BxMarketEntry.prototype.initScreenshots = function() {
	var oItems = jQuery(".bx-market-screenshots .bx-market-ss-item");
	if(oItems.length == 0)
		return;

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

	$('.bx-market-screenshots').flickity({
		cellSelector: '.bx-market-ss-item',
		cellAlign: 'left',
		pageDots: false,
		imagesLoaded: true
	});
};
