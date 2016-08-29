function BxPfwOrders(oOptions) {
	this.init(oOptions);
};

BxPfwOrders.prototype = new BxPmtOrders({});

BxPfwOrders.prototype.unsubscribe = function(sType, iId) {
    var $this = this;

    this._getOrderLoading(iId);

    $.post(
        this._sActionsUrl + 'act_cancel_subscription/',
        {
            type: sType,
            id: iId
        },
        function(oData) {
        	$this._getOrderLoading(iId);

        	if(oData.message)
        		alert(oData.message);

            $('#pmt-orders-more').dolPopupHide({});
        },
        'json'
    );
};

BxPfwOrders.prototype._getOrderLoading = function(iId) {
	$('#pfw-order-loading-' + iId).bx_loading();
};