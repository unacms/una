function BxPaymentOrders(oOptions) {
	this.init(oOptions);
}

BxPaymentOrders.prototype.init = function(oOptions) {
	if($.isEmptyObject(oOptions))
		return;

	this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oPaymentOrders' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._iTimeout = (oOptions.iTimeout == undefined ? 5 : oOptions.iTimeout) * 1000;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
};

/*--- Manual Order function ---*/
BxPaymentOrders.prototype.paOnShowPopup = function(oSelect) {
	var $this = this;

	$('#' + this._aHtmlIds['order_processed_client']).autocomplete({
        source: this._sActionsUrl + 'get_clients/',
        select: function(oEvent, oUi) {
        	$('#' + $this._aHtmlIds['order_processed_client_id']).val(oUi.item.value);
            $(this).val(oUi.item.label);

            oEvent.preventDefault();
        }
    });
};

BxPaymentOrders.prototype.paOnSelectModule = function(oSelect) {
    var oDate = new Date();

    var oPopup = $('#' + this._aHtmlIds['order_processed_add']); 
    var oItems = $('#' + this._aHtmlIds['order_processed_items']);

    oPopup.bx_loading(true);

    $.get(
        this._sActionsUrl + 'get_items/single/' + parseInt($(oSelect).val()) + '/',
        {
            _t:oDate.getTime()
        },
        function(oData) {
        	oPopup.bx_loading(false);

        	if(oData.message != undefined)
        		alert(oData.message);

            if(oData.data != undefined)
            	oItems.replaceWith(oData.data);
        },
        'json'
    );
};