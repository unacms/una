/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Xero Xero
 * @ingroup     UnaModules
 *
 * @{
 */

function BxXeroMain(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxXeroMain' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxXeroMain.prototype.test = function(oLink) {
    var $this = this;
    var oData = this._getDefaultParams();

    $(oLink).dolPopupAjax({
        id: this._aHtmlIds['by_popup'], 
        url: bx_append_url_params(this._sActionsUrl + 'test', oData)
    });
};

BxXeroMain.prototype._getDefaultParams = function() {
    var oDate = new Date();
    return {_t:oDate.getTime()};
};
/** @} */
