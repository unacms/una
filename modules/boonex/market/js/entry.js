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
