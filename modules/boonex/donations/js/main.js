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

/** @} */
