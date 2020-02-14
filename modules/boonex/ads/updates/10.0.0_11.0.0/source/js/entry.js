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
}

BxAdsEntry.prototype.interested = function(oElement, iContentId) {
    var $this = this;
    var oParams = this._getDefaultData();
    oParams['id'] = iContentId;

    if(oElement)
        this.loadingInButton(oElement, true);

    jQuery.get (
        this._sActionsUrl + 'interested',
        oParams,
        function(oData) {
            if(oElement)
                $this.loadingInButton(oElement, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxAdsEntry.prototype.onChangeCategory = function(oElement) {
    var $this = this;
    var oParams = this._getDefaultData();
    oParams['category'] = $(oElement).val();

    this.loadingInBlock(oElement, true);

    jQuery.get (
        this._sActionsUrl + 'get_category_form',
        oParams,
        function(oData) {
            if(oElement)
                $this.loadingInBlock(oElement, false);

            if(!oData || !oData.content && oData.content.length == 0) 
                return;

            var oContent = $(oData.content);
            var sFormId = oContent.filter('form').attr('id');
            if(!sFormId)
                return;

            $('form#' + sFormId).replaceWith(oContent);
        },
        'json'
    );
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

BxAdsEntry.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxAdsEntry.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

/** @} */
