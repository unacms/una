/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Donations Donations
 * @ingroup     UnaModules
 *
 * @{
 */

function BxDonationsMain(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDonationsMain' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxDonationsMain.prototype.changeType = function(oLink, sType) {
    var sClsMi = 'bx-menu-item';
    var sClsMa = 'bx-menu-tab-active';
    
    var sClsCi = 'bx-dnt-make-billing-type';
    var sClsCa = 'active';

    $(oLink).parents('.' + sClsMi + ':first').toggleClass(sClsMa, true).siblings('.' + sClsMi + '.' + sClsMa).toggleClass(sClsMa, false);
    $(oLink).parents('.bx-dnt-make:first').find('.' + sClsCi + '.' + sType).toggleClass(sClsCa, true).siblings('.' + sClsCi + '.' + sClsCa).toggleClass(sClsCa, false);
};

BxDonationsMain.prototype.other = function(oLink, sBillingType) {
    var $this = this;

    var fOnOk = function(oPopup) {
        var oParams = jQuery.extend({}, $this._getDefaultParams(), {
            btype: sBillingType,
            amount: $(oPopup).find("input[type = 'text']").val()
        });

        $.get(
            $this._sActionsUrl + 'make_other/',
            oParams,
            function(oData){
                processJsonData(oData);
            },
            'json'
        );
    };

    bx_prompt('', '', fOnOk);
};

BxDonationsMain.prototype._getDefaultParams = function() {
    var oDate = new Date();
    return {_t:oDate.getTime()};
};
/** @} */
