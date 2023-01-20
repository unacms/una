/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAdsFormOffer(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'BxAdsFormOffer' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxAdsFormOffer.prototype.updateTotal = function(oSource, sAmountId, sQuantityId, sTotalId) {
    var oForm = jQuery(oSource).parents('.bx-form-advanced:first');

    var oAmount = oForm.find("[name='" + sAmountId + "']");
    var fAmount = parseFloat(oAmount.val());
    if(!fAmount)
        fAmount = 0;

    var oQuantity = oForm.find("[name='" + sQuantityId + "']");
    var iQuantity = parseInt(oQuantity.val());
    if(!iQuantity)
        iQuantity = 0;

    oForm.find("[name='" + sTotalId + "']").val(fAmount * iQuantity);
};

BxAdsFormOffer.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxAdsFormOffer.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

/** @} */
