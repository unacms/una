/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPaymentSubscriptions(oOptions) {
	this.init(oOptions);
}

BxPaymentSubscriptions.prototype = new BxPaymentMain();

BxPaymentSubscriptions.prototype.init = function(oOptions) {
	if($.isEmptyObject(oOptions))
		return;

	this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oPmtSubscriptions' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
};

BxPaymentSubscriptions.prototype.getDetails = function(oLink, iId) {
	this._performRequest(oLink, iId, 'subscription_get_details');
};

BxPaymentSubscriptions.prototype.changeDetails = function(oLink, iId) {
	this._performRequest(oLink, iId, 'subscription_change_details');
};

BxPaymentSubscriptions.prototype.getBilling = function(oLink, iId) {
	this._performRequest(oLink, iId, 'subscription_get_billing');
};

BxPaymentSubscriptions.prototype.changeBilling = function(oLink, iId) {
	this._performRequest(oLink, iId, 'subscription_change_billing');
};

BxPaymentSubscriptions.prototype.requestCancelation = function(oLink, iId) {
	this._performRequest(oLink, iId, 'subscription_cancelation');
};

BxPaymentSubscriptions.prototype._performRequest = function(oLink, iId, sUri) {
	var $this = this;
    var oDate = new Date();

    this.loadingInButton(oLink, true);

    $.post(
        this._sActionsUrl + sUri + '/' + iId + '/',
        {
            _t:oDate.getTime()
        },
        function(oData){
        	$this.loadingInButton(oLink, false);
        	$(".bx-popup-applied:visible").dolPopupHide();

        	$this.processResult(oData);
        },
        'json'
    );
};

/** @} */
