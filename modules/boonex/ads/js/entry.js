/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAdsEntry(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxAdsEntry' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxAdsEntry.prototype.interested = function(oElement, iContentId) {
    this._performAction(oElement, 'interested', iContentId);
};

BxAdsEntry.prototype.show = function(oElement, iContentId) {
    this._performAction(oElement, 'show', iContentId);
};

BxAdsEntry.prototype.hide = function(oElement, iContentId) {
    this._performAction(oElement, 'hide', iContentId);
};

BxAdsEntry.prototype.makeOffer = function(oElement, iContentId) {
    this._performAction(oElement, 'make_offer', iContentId, 'content_id');
};

BxAdsEntry.prototype.onMakeOffer = function(oData) {};

BxAdsEntry.prototype.viewOffer = function(oElement, iOfferId) {
    this._performAction(oElement, 'view_offer', iOfferId);
};

BxAdsEntry.prototype.acceptOffer = function(oElement, iOfferId) {
    var $this = this;

    bx_confirm('', function() {
        $this._performAction(oElement, 'accept_offer', iOfferId);
    });
};

BxAdsEntry.prototype.declineOffer = function(oElement, iOfferId) {
    var $this = this;

    bx_confirm('', function() {
        $this._performAction(oElement, 'decline_offer', iOfferId);
    });
};

BxAdsEntry.prototype.cancelOffer = function(oElement, iOfferId) {
    var $this = this;

    bx_confirm('', function() {
        $this._performAction(oElement, 'cancel_offer', iOfferId);
    });
};

BxAdsEntry.prototype.payOffer = function(oElement, iOfferId) {
    this._performAction(oElement, 'pay_offer', iOfferId);
};

BxAdsEntry.prototype.shipped = function(oElement, iContentId) {
    this._performAction(oElement, 'shipped', iContentId);
};

BxAdsEntry.prototype.received = function(oElement, iContentId) {
    this._performAction(oElement, 'received', iContentId);
};

BxAdsEntry.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);	
};

BxAdsEntry.prototype.loadingInBox = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-base-text-poll:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxAdsEntry.prototype._performAction = function(oElement, sAction, iContentId, sContentIdKey) {
    var $this = this;
    var oParams = this._getDefaultData();
    oParams[sContentIdKey ? sContentIdKey : 'id'] = iContentId;

    if(oElement)
        this.loadingInButton(oElement, true);

    jQuery.get (
        this._sActionsUrl + sAction,
        oParams,
        function(oData) {
            if(oElement)
                $this.loadingInButton(oElement, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxAdsEntry.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

/** @} */
