/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPaymentProviderStripeV3(oOptions) {
    this.init(oOptions);
}

BxPaymentProviderStripeV3.prototype = new BxPaymentMain();

BxPaymentProviderStripeV3.prototype.init = function(oOptions) {
    this._sProvider = oOptions.sProvider;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oPaymentProviderStripeV3' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    //--- For Single payment
    this._sObjNameGrid = oOptions.sObjNameGrid;

    //--- For Recurring payment
    this._sObjNameCart = oOptions.sObjNameCart;
    this._iSellerId  = oOptions.iSellerId;
    this._iModuleId  = oOptions.iModuleId;
    this._iItemId  = oOptions.iItemId;
    this._iItemCount  = oOptions.iItemCount;
    this._sItemAddons  = oOptions.sItemAddons == undefined ? '' : oOptions.sItemAddons;
    this._sRedirect  = oOptions.sRedirect;
    this._sCustom  = oOptions.sCustom;

    var $this = this;
    if(window.Stripe === undefined) {
        $.getScript('https://js.stripe.com/v3/', function() {
            $this._rHandler = Stripe(oOptions.sPublicKey);
        });
    }
    else
        this._rHandler = Stripe(oOptions.sPublicKey);
};

BxPaymentProviderStripeV3.prototype.onCartCheckout = function(oData) {
    this._rHandler.redirectToCheckout({
        sessionId: oData.session_id
    }).then(function (oResult) {
        if(oResult.error)
            bx_alert(oResult.error.message);
    }).catch(function(oError) {
        console.error('Error:', oError);
    });
};

BxPaymentProviderStripeV3.prototype.subscribe = function(oLink) {
    return this.subscribeWithAddons(oLink);
};
    
BxPaymentProviderStripeV3.prototype.subscribeWithAddons = function(oLink, mixedAddons) {
    var oDate = new Date();

    oLink = jQuery(oLink);
    if(oLink.hasClass('bx-btn-disabled'))
        return;

    oLink.addClass('bx-btn-disabled');

    this.loadingInPopup(oLink, true);

    var aParams = {
        seller_id: this._iSellerId,
        seller_provider: this._sProvider,
        module_id: this._iModuleId,
        item_id: this._iItemId,
        item_count: this._iItemCount,
        item_addons: mixedAddons != undefined ? mixedAddons : this._sItemAddons,
        redirect: this._sRedirect,
        custom: this._sCustom,
        _t: oDate.getTime()
    };

    $.post(this._sActionsUrl + 'call/' + this._sProvider + '/get_session_recurring/' + this._iSellerId + '/', aParams, function(oData) {
        if(oData && oData.code != undefined && parseInt(oData.code) == 0)
            oData.params = aParams;

        oLink.removeClass('bx-btn-disabled');

        processJsonData(oData);
    }, 'json');
};

BxPaymentProviderStripeV3.prototype.onSubscribe = function(oData) {
    this._rHandler.redirectToCheckout({
        sessionId: oData.session_id
    }).then(function (oResult) {
        if(oResult.error)
            bx_alert(oResult.error.message);
    }).catch(function(oError) {
        console.error('Error:', oError);
    });
};

/** @} */
