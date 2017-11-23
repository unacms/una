/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPaymentProviderStripe(oOptions) {
	this.init(oOptions);
}

BxPaymentProviderStripe.prototype = new BxPaymentMain();

BxPaymentProviderStripe.prototype.init = function(oOptions) {
	this._sProvider = oOptions.sProvider;
	this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oPaymentProviderStripe' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._rHandler = StripeCheckout.configure({
	  key: oOptions.sPublicKey,
	  name: oOptions.sVendorName,
	  amount: oOptions.iAmount == undefined ? '' : oOptions.iAmount,
	  currency: oOptions.sVendorCurrency,
	  image: oOptions.sVendorIcon,
	  locale: 'auto',
	  email: oOptions.sClientEmail,
	  description: oOptions.sItemTitle == undefined ? '' : oOptions.sItemTitle
	});

    //--- For Single payment
    this._sObjNameGrid = oOptions.sObjNameGrid;

    //--- For Recurring payment
    this._sObjNameCart = oOptions.sObjNameCart;
    this._iSellerId  = oOptions.iSellerId;
    this._iModuleId  = oOptions.iModuleId;
    this._iItemId  = oOptions.iItemId;
    this._iItemCount  = oOptions.iItemCount;
    this._sRedirect  = oOptions.sRedirect;
    this._sCustom  = oOptions.sCustom;
};

BxPaymentProviderStripe.prototype.checkout = function(oLink) {
  	oLink = jQuery(oLink);
	if(oLink.hasClass('bx-btn-disabled'))
		return;

	oLink.toggleClass('bx-btn-disabled');

	var $this = this;
	this._rHandler.open({
		token: function(token) {
			glGrids[$this._sObjNameGrid]._oQueryAppend['provider'] = $this._sProvider;
			glGrids[$this._sObjNameGrid]._oQueryAppend['token'] = token.id;
			glGrids[$this._sObjNameGrid].actionWithSelected('', 'checkout', {}, '', false, false);
		},
		closed: function() {
			oLink.toggleClass('bx-btn-disabled');
		}
	});
};

BxPaymentProviderStripe.prototype.subscribe = function(oLink) {
	var $this = this;
    var oDate = new Date();

  	oLink = jQuery(oLink);
	if(oLink.hasClass('bx-btn-disabled'))
		return;

	oLink.addClass('bx-btn-disabled');

	this._rHandler.open({
		token: function(token) {
			$this.loadingInButton(oLink, true);

		    var oParams = {
		    	seller_id: $this._iSellerId,
		    	seller_provider: $this._sProvider,
		    	module_id: $this._iModuleId,
		    	item_id: $this._iItemId,
		    	item_count: $this._iItemCount,
		    	redirect: $this._sRedirect,
		    	custom: $this._sCustom,
		    	token: token.id,
		    	_t: oDate.getTime()
		    };

		    $.post(
		        $this._sActionsUrl + 'subscribe_json/',
		        oParams,
		        function(oData){
		        	$this.loadingInButton(oLink, true);

		        	$this.processResult(oData);
		        },
		        'json'
		    );
		},
		closed: function() {
			oLink.removeClass('bx-btn-disabled');
		}
	});
};

/** @} */
