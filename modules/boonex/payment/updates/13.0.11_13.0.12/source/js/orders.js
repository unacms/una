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
    this._sObjNameGrid = oOptions.sObjNameGrid == undefined ? '' : oOptions.sObjNameGrid;
    this._sObjName = oOptions.sObjName == undefined ? 'oPaymentOrders' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._iTimeout = (oOptions.iTimeout == undefined ? 5 : oOptions.iTimeout) * 1000;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;
    this._sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput;

    this._iSearchTimeoutId = false;

    var $this = this;
    $(document).ready(function() {
        $('#bx-grid-search-' + $this._sObjNameGrid).unbind('focusout');
    });
};

/*--- Grid Filter function ---*/
BxPaymentOrders.prototype.onChangeFilter = function(oElement) {
    if(!this._sObjNameGrid)
        return;

    var $this = this;

    var oClient = $('#bx-grid-client-' + this._sObjNameGrid);
    var bClient = oClient.length > 0;
    var sClient = bClient ? oClient.val() : '';

    var oAuthor = $('#bx-grid-author-' + this._sObjNameGrid);
    var bAuthor = oAuthor.length > 0;
    var sAuthor = bAuthor ? oAuthor.val() : '';
    
    var oModule = $('#bx-grid-module-' + this._sObjNameGrid);
    var bModule = oModule.length > 0;
    var sModule = bModule ? oModule.val() : '';

    var oItem = $('#bx-grid-item-' + this._sObjNameGrid);
    var bItem = oItem.length > 0;
    var sItem = bItem ? oItem.val() : '';

    var sSearch = $('#bx-grid-search-' + this._sObjNameGrid).val();
    if(sSearch == this._sTextSearchInput)
        sSearch = '';

    clearTimeout($this._iSearchTimeoutId);
    $this._iSearchTimeoutId = setTimeout(function () {
        var sValue = '';
        if(bClient)
            sValue += sClient + $this._sParamsDivider;
        if(bAuthor)
            sValue += sAuthor + $this._sParamsDivider;
        if(bModule)
            sValue += sModule + $this._sParamsDivider;
        if(bItem)
            sValue += sItem + $this._sParamsDivider;
        sValue += sSearch;

    	glGrids[$this._sObjNameGrid].setFilter(sValue, true);
    }, 500);
};

BxPaymentOrders.prototype.onChangeFilterModule = function(oElement, iSeller) {
    var $this = this;
    var oDate = new Date();

    var iModule = parseInt($(oElement).val());
    if(!iModule)
        iModule = 0;

    $.get(
        this._sActionsUrl + 'get_filter_values_item/' + iSeller + '/' + iModule + '/',
        {
            _t:oDate.getTime()
        },
        function(oData) {
            var oFilterItem = $('#bx-grid-item-' + $this._sObjNameGrid);

            if(oData && oData.code != undefined)
                switch(oData.code) {
                    case 0:
                        oFilterItem.html(oData.content).removeAttr('disabled');
                        break;

                    case 1:
                        oFilterItem.html(oData.content).attr('disabled', 'disabled');
                        break;
                }

            $this.onChangeFilter(oElement);
        },
        'json'
    );
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

BxPaymentOrders.prototype.filterItems = function(e, oFilter) {
    var $this = this;
    var oDate = new Date();

    var oPopup = $('#' + this._aHtmlIds['order_processed_add']);
    oPopup.bx_loading(true);

    if ('undefined' != typeof(glBxPaymentOrdersTimeoutHandler) && glBxPaymentOrdersTimeoutHandler)
        clearTimeout(glBxPaymentOrdersTimeoutHandler);

    glBxPaymentOrdersTimeoutHandler = setTimeout(function () {
        $.get(
            $this._sActionsUrl + 'get_items/single/' + parseInt(oPopup.find("[name = 'module_id']").val()) + '/',
            {
                filter: $(oFilter).val(),
                _t:oDate.getTime()
            },
            function(oData) {
                oPopup.bx_loading(false);

                processJsonData(oData);

                oFilter = oPopup.find("[name = 'filter']").focus().get(0);
                oFilter.selectionStart = oFilter.selectionEnd = oFilter.value.length;
            },
            'json'
        );
    }, 500);

    return true;
};

BxPaymentOrders.prototype.onSelectModule = function(oData) {
	if(oData.data != undefined)
		$('#' + this._aHtmlIds['order_processed_items']).replaceWith(oData.data);
};

/** @} */
