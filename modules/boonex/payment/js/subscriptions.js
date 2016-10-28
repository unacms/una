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
	var $this = this;
    var oDate = new Date();

    this.loadingInButton(oLink, true);

    $.post(
        this._sActionsUrl + 'subscription_get_details/' + iId + '/',
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

BxPaymentSubscriptions.prototype.getBilling = function(oLink, iId) {
	var $this = this;
    var oDate = new Date();

    this.loadingInButton(oLink, true);

    $.post(
        this._sActionsUrl + 'subscription_get_billing/' + iId + '/',
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

BxPaymentSubscriptions.prototype.changeBilling = function(oLink, iId) {
	var $this = this;
    var oDate = new Date();

    this.loadingInButton(oLink, true);

    $.post(
        this._sActionsUrl + 'subscription_change_billing/' + iId + '/',
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

BxPaymentSubscriptions.prototype.requestCancelation = function(oLink, iId) {
	var $this = this;
    var oDate = new Date();

    this.loadingInButton(oLink, true);

    $.post(
        this._sActionsUrl + 'subscription_cancelation/' + iId + '/',
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