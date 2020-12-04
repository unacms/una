/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 *
 * @{
 */

function BxCreditsSubscribe(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxCreditsSubscribe' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
}

BxCreditsSubscribe.prototype.subscribe = function(oElement) {
    var $this = this;
    var oParams = this._getDefaultData();

    if(oElement)
        this.loadingInBox(oElement, true);

    jQuery.get (
        this._sActionsUrl + 'subscribe',
        oParams,
        function(oData) {
            if(oElement)
                $this.loadingInBox(oElement, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxCreditsSubscribe.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);	
};

BxCreditsSubscribe.prototype.loadingInBox = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-def-box:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxCreditsSubscribe.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxCreditsSubscribe.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

/** @} */
