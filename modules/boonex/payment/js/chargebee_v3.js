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

    this._sObjNameCart = oOptions.sObjNameCart;
    this._iClientId = oOptions.iClientId;
    this._iSellerId  = oOptions.iSellerId;
    this._iModuleId  = oOptions.iModuleId;
    this._iItemId  = oOptions.iItemId;
    this._sItemName = oOptions.sItemName;
    this._iItemCount  = oOptions.iItemCount;
    this._sRedirect  = oOptions.sRedirect;
    this._sCustom  = oOptions.sCustom;

	this._rHandler = Chargebee.init({
        site: oOptions.sSite
    });
	this._rHandler = Chargebee.getInstance();
};

BxPaymentProviderChargebeeV3.prototype.subscribe = function(oLink) {
	var $this = this;
    var oDate = new Date();

  	oLink = jQuery(oLink);
	if(oLink.hasClass('bx-btn-disabled'))
		return;

	oLink.addClass('bx-btn-disabled');

	this._rHandler.openCheckout({
	    hostedPage: function() {
	    	return $.post({
	    		url: $this._sActionsUrl + 'call/' + $this._sProvider + '/get_hosted_page/' + $this._iClientId + '/' + $this._iSellerId + '/' + $this._sItemName + '/',
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

/** @} */
