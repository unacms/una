/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

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
BxPaymentOrders.prototype.showPopup = function(oUi) {
	$('#' + this._aHtmlIds['order_processed_client_id']).val(oUi.item.value);	
};

BxPaymentOrders.prototype.selectModule = function(oSelect) {
    var oDate = new Date();

    var oPopup = $('#' + this._aHtmlIds['order_processed_add']);
    oPopup.bx_loading(true);

    $.get(
        this._sActionsUrl + 'get_items/single/' + parseInt($(oSelect).val()) + '/',
        {
            _t:oDate.getTime()
        },
        function(oData) {
        	oPopup.bx_loading(false);

        	processJsonData(oData);
        },
        'json'
    );
};

BxPaymentOrders.prototype.onSelectModule = function(oData) {
	if(oData.data != undefined)
		$('#' + this._aHtmlIds['order_processed_items']).replaceWith(oData.data);
};

/** @} */
