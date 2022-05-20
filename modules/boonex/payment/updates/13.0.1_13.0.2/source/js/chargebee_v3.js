/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPaymentProviderChargebeeV3(oOptions) {
    this.init(oOptions);
}

BxPaymentProviderChargebeeV3.prototype = new BxPaymentMain();

BxPaymentProviderChargebeeV3.prototype.init = function(oOptions) {
    this._sProvider = oOptions.sProvider;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oPaymentProviderChargebeeV3' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._sObjNameCart = oOptions.sObjNameCart == undefined ? '' : oOptions.sObjNameCart;
    this._iClientId = oOptions.iClientId == undefined ? 0 : oOptions.iClientId;
    this._iSellerId = oOptions.iSellerId == undefined ? 0 : oOptions.iSellerId;

    //-- For Single payments only.
    this._aItems = oOptions.aItems == undefined ? {} : oOptions.aItems;

    //-- For Recurring payments only.
    this._iModuleId  = oOptions.iModuleId == undefined ? 0 : oOptions.iModuleId;
    this._iItemId  = oOptions.iItemId == undefined ? 0 : oOptions.iItemId;
    this._sItemName = oOptions.sItemName == undefined ? '' : oOptions.sItemName;
    this._iItemCount  = oOptions.iItemCount == undefined ? 0 : oOptions.iItemCount;
    this._sItemAddons  = oOptions.sItemAddons == undefined ? '' : oOptions.sItemAddons;
    this._sRedirect  = oOptions.sRedirect == undefined ? '' : oOptions.sRedirect;
    this._sCustom  = oOptions.sCustom == undefined ? '' : oOptions.sCustom;

    this._rHandler = Chargebee.init({
        site: oOptions.sSite
    });
    this._rHandler = Chargebee.getInstance();
};

BxPaymentProviderChargebeeV3.prototype.checkout = function(oLink) {
    return this.checkoutWithAddons(oLink);
};

BxPaymentProviderChargebeeV3.prototype.checkoutWithAddons = function(oLink, mixedAddons) {
    var $this = this;
    var oDate = new Date();

    oLink = jQuery(oLink);
    if(oLink.hasClass('bx-btn-disabled'))
        return;

    oLink.addClass('bx-btn-disabled');

    this._rHandler.openCheckout({
        hostedPage: function() {
            var oParams = {
                url: $this._sActionsUrl + 'call/' + $this._sProvider + '/get_hosted_page_single/' + $this._iClientId + '/' + $this._iSellerId + '/',
                dataType: 'json'
            };
            if(mixedAddons != undefined)
                oParams.data = {
                    addons: mixedAddons
                };

            return $.post(oParams);
        },
        success: function(sHostedPageId) {
            $this.loadingInPopup(oLink, true);

            var oParams = {
                seller_id: $this._iSellerId,
                provider: $this._sProvider,
                items: $this._aItems,
                page_id: sHostedPageId,
                _t: oDate.getTime()
            };

            $.post(
                $this._sActionsUrl + 'initialize_checkout_json/',
                oParams,
                function(oData){
                    $this.loadingInPopup(oLink, true);

                    processJsonData(oData);
                },
                'json'
            );
        },
        close: function() {
            oLink.removeClass('bx-btn-disabled');
        }
    });

    return false;
};


BxPaymentProviderChargebeeV3.prototype.subscribe = function(oLink) {
    return this.subscribeWithAddons(oLink);
};

BxPaymentProviderChargebeeV3.prototype.subscribeWithAddons = function(oLink, mixedAddons) {
    var $this = this;
    var oDate = new Date();

    oLink = jQuery(oLink);
    if(oLink.hasClass('bx-btn-disabled'))
        return;

    oLink.addClass('bx-btn-disabled');

    this._rHandler.openCheckout({
        hostedPage: function() {
            return $.post({
                url: $this._sActionsUrl + 'call/' + $this._sProvider + '/get_hosted_page_recurring/' + $this._iClientId + '/' + $this._iSellerId + '/' + $this._sItemName + '/',
                data: {
                    addons: mixedAddons != undefined ? mixedAddons : $this._sItemAddons
                }, 
                dataType: 'json'
            });
        },
        success: function(sHostedPageId) {
            $this.loadingInPopup(oLink, true);

            var oParams = {
                seller_id: $this._iSellerId,
                seller_provider: $this._sProvider,
                module_id: $this._iModuleId,
                item_id: $this._iItemId,
                item_count: $this._iItemCount,
                item_addons: $this._sItemAddons,
                redirect: $this._sRedirect,
                custom: $this._sCustom,
                page_id: sHostedPageId,
                _t: oDate.getTime()
            };

            $.post(
                $this._sActionsUrl + 'subscribe_json/',
                oParams,
                function(oData){
                    $this.loadingInPopup(oLink, true);

                    processJsonData(oData);
                },
                'json'
            );
        },
        close: function() {
            oLink.removeClass('bx-btn-disabled');
        }
    });

    return false;
};

BxPaymentProviderChargebeeV3.prototype.manage = function(oLink, iPendingId) {
    var $this = this;
    var oDate = new Date();

    $(".bx-popup-applied:visible").dolPopupHide();

    this._rHandler.setPortalSession(function() {
    	return $.post({
            url: $this._sActionsUrl + 'call/' + $this._sProvider + '/get_portal/' + iPendingId + '/',
            dataType: 'json'
    	});
    });

    var cbPortal = this._rHandler.createChargebeePortal();
    cbPortal.open({
        close: function() {
            $this._rHandler.logout();
        }
    });

    return false;
};

/** @} */
