/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPaymentCart(oOptions) {
    this.init(oOptions);
}

BxPaymentCart.prototype = new BxPaymentMain();

BxPaymentCart.prototype.init = function(oOptions) {
    if($.isEmptyObject(oOptions))
        return;

    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oPmtCart' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
};

BxPaymentCart.prototype.addToCart = function(iSellerId, iModuleId, iItemId, iItemCount, iRedirect, sCustom) {
    var $this = this;
    var oDate = new Date();

    var sRedirect = '';
    if(typeof iRedirect == 'number' && parseInt(iRedirect) > 0)
        sRedirect = sUrlRoot + 'cart.php?seller_id=' + iSellerId;
    else if(typeof iRedirect == 'string' && iRedirect.length > 0)
        sRedirect = iRedirect;

    $.post(
        this._sActionsUrl + 'add_to_cart/' + iSellerId + '/' + iModuleId + '/' + iItemId + '/' + iItemCount + '/',
        {
            custom: sCustom ? sCustom : '',
            _t: oDate.getTime()
        },
        function(oData) {
            if(oData && oData.code == 0 && sRedirect) {
                oData.message = '';
                oData.redirect = sRedirect;
            }

            processJsonData(oData);
        },
        'json'
    );
};

BxPaymentCart.prototype.onCartContinue = function(oData) {
    if (!oData || oData.link == undefined)
        return;

    location.href = oData.link;
};

BxPaymentCart.prototype.onCartCheckout = function(oData) {
    if (!oData || oData.link == undefined)
        return;

    location.href = oData.link;
};

BxPaymentCart.prototype.subscribe = function(oLink, iSellerId, sSellerProvider, iModuleId, iItemId, iItemCount, sRedirect, sCustom) {
    this.subscribeWithAddons(oLink, iSellerId, sSellerProvider, iModuleId, iItemId, iItemCount, '', sRedirect, sCustom);
};

BxPaymentCart.prototype.subscribeWithAddons = function(oLink, iSellerId, sSellerProvider, iModuleId, iItemId, iItemCount, sItemAddons, sRedirect, sCustom) {
    var $this = this;
    var oDate = new Date();

    var oParams = {
    	seller_id: iSellerId,
    	seller_provider: sSellerProvider,
    	module_id: iModuleId,
    	item_id: iItemId,
    	item_count: 1,
        item_addons: sItemAddons,
    	redirect: sRedirect ? sRedirect : '',
    	custom: sCustom ? sCustom : '',
    	_t: oDate.getTime()
    };

    if(iItemCount != undefined && iItemCount.length > 0)
    	oParams.item_count = parseInt(iItemCount);

    var oLoading = $(oLink).hasClass('bx-btn') ? oLink : null;
    this.loadingInButton(oLoading, true);
    
    $.post(
        this._sActionsUrl + 'subscribe_json/',
        oParams,
        function(oData){
            $this.loadingInButton(oLoading, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxPaymentCart.prototype.onSubscribeSubmit = function(oData) {
	document.location = oData.redirect;
};

/** @} */
