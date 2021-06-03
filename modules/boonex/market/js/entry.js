/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

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

BxMarketEntry.prototype.perform = function(oLink, sAction, iId) {
	var oDate = new Date();
    var oData = {
    	action: sAction,
    	id: iId,
		_t:oDate.getTime()
    };

    $(oLink).parents('.bx-popup-applied:first:visible').dolPopupHide({});

    $.post(
        this._sActionsUrl + 'perform/',
        oData,
        function(oData) {
        	processJsonData(oData);
        },
        'json'
    );
};

BxMarketEntry.prototype.checkName = function(sTitleId, sNameId, iId) {
	var oDate = new Date();

	var oName = jQuery("[name='" + sNameId + "']");
	var sName = oName.val();
	var bName = sName.length != 0;
	
	var oTitle = jQuery("[name='" + sTitleId + "']");
	var sTitle = oTitle.val();
	var bTitle = sTitle.length != 0;

	if(!bName && !bTitle)
		return;

	var sTitleCheck = '';
	if(bName)
		sTitleCheck = sName;
	else if(bTitle) {
		sTitleCheck = sTitle;

		sTitle = sTitle.replace(/[^A-Za-z0-9_]/g, '-');
		sTitle = sTitle.replace(/[-]{2,}/g, '-');
		oName.val(sTitle.toLowerCase());
	}

	jQuery.get(
		this._sActionsUrl + 'check_name',
		{
			title: sTitleCheck,
			id: iId && parseInt(iId) > 0 ? iId : 0,
    		_t: oDate.getTime()
    	},
    	function(oData) {
    		if(!oData || oData.name == undefined)
    			return;

    		oName.val(oData.name);
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
            type: 'image',
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
            imagesLoaded: true,
            wrapAround: true,
            pageDots: false
	});
};

/**
 * Is used on Downloads page to show old versions. 
 */
BxMarketEntry.prototype.showMore = function() {
	$('.bx-market-attachment:not(.bx-market-attachment-main)').bx_anim('toggle', this._sAnimationEffect, this._sAnimationSpeed);
};

/** @} */
