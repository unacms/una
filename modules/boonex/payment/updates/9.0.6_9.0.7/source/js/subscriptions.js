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

BxPaymentSubscriptions.prototype.cancel = function(oLink, iId, sGrid) {
	var $this = this;

	var oParams = {};
	if(sGrid != undefined)
		oParams.grid = sGrid;

	bx_confirm('', function() {
		$this._performRequest(oLink, iId, 'subscription_cancel', oParams);
	});
};

BxPaymentSubscriptions.prototype._performRequest = function(oLink, iId, sUri, oParams) {
	var $this = this;
    var oDate = new Date();

    this.loadingInPopup(oLink, true);

    $.post(
        this._sActionsUrl + sUri + '/' + iId,
        $.extend({}, {_t:oDate.getTime()}, oParams),
        function(oData){
        	$this.loadingInPopup(oLink, false);

        	$(".bx-popup-applied:visible").dolPopupHide();

        	processJsonData(oData);
        },
        'json'
    );
};

/** @} */
