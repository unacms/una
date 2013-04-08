function BxPmtOrders(oOptions) {
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oPmtOrders' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._iTimeout = (oOptions.iTimeout == undefined ? 5 : oOptions.iTimeout) * 1000;
}
BxPmtOrders.prototype.showResultInline = function(oData, onHide) {
    var $this = this;

    if(!onHide)
        onHide = function(){}

    if(parseInt(oData.code) == 0) {
        parent.location.href = parent.location.href;
        return;
    }

    $('#' + oData.parent_id).prepend($(oData.message).hide()).children(':first').bx_anim('toggle', this._sAnimationEffect, this._iAnimationSpeed, function(){
        setTimeout($this._sObjName + ".hideResultInline('" + oData.parent_id + "', " + onHide + ")", $this._iTimeout);
    });
}

BxPmtOrders.prototype.hideResultInline = function(sId, onHide) {
    $('#' + sId + ' > :first.MsgBox').bx_anim('toggle', this._sAnimationEffect, this._iAnimationSpeed, function() {
        $(this).remove();
        onHide();
    });
}
BxPmtOrders.prototype.toggleLoading = function() {
    $('#pmt-orders-loading').bx_loading();
}

/*--- Manual Order function ---*/
BxPmtOrders.prototype.addManually = function(oLink) {
    var $this = this;
    var oParent = $(oLink).parents('.disignBoxFirst');
    var oPopupOptions = {
        fog: {color: '#fff', opacity: .7}
    };


    $('#pmt-manual-order').dolPopup(oPopupOptions);
}
BxPmtOrders.prototype.selectModule = function(oSelect) {
    var $this = this;
    var oDate = new Date();
    var iModuleId = parseInt(oSelect.value);

    var sParentId = 'pmt-mo-items';

    if(!$(oSelect).parents('tr:first').next('tr').find(' > td:first > div').is('#' + sParentId))
        $(oSelect).parents('tr:first').after('<tr><td colspan="2"><div id="' + sParentId + '"></div></td></tr>');
    else
        $(oSelect).parents('tr:first').next('tr').show();

    this.toggleLoading();
    $.get(
        this._sActionsUrl + 'act_get_items/' + iModuleId + '/',
        {
            _t:oDate.getTime()
        },
        function(oData) {
            $this.toggleLoading();

            $('#' + sParentId).html('');

            if(parseInt(oData.code) != 0) {
                $this.showResultInline(
                    {
                        code:oData.code,
                        message:oData.message,
                        parent_id: sParentId
                    },
                    function(){
                        $('#pmt-mo-items').parents('tr:first').hide();
                    }
                );
                return;
            }

            $('#' + sParentId).html(oData.data);
        },
        'json'
    );
}

/*--- View Orders functions ---*/
BxPmtOrders.prototype.more = function(sType, iId) {
    var $this = this;
    var oPopupOptions = {
        fog: {color: '#fff', opacity: .7}
    };

    $('#pmt-orders-more').dolPopup(oPopupOptions);
    this.toggleLoading();

    $.post(
        this._sActionsUrl + 'act_get_order/',
        {
            type: sType,
            id: iId
        },
        function(oData) {
            $this.toggleLoading();

            if(parseInt(oData.code) != 0) {
                $this.showResultInline({code: oData.code, message: oData.message, parent_id: 'pmt-orders-more'});
                return;
            }

            $('#pmt-om-content').hide().html(oData.data).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
        },
        'json'
    );
}
BxPmtOrders.prototype.changePage = function(sType, iStart, iPerPage, iSellerId) {
    this.toggleLoading();
    var oOptions = {
        type: sType,
        start: iStart,
        per_page: iPerPage,
        filter: $('#pmt-filter-enable-' + sType).attr('checked') ? $('#pmt-filter-text-' + sType).val() : ''
    };
    if(iSellerId != 'undefined')
        oOptions['seller_id'] = iSellerId;

    this._getOrders(oOptions);
}
BxPmtOrders.prototype.applyFilter = function(sType, oCheckbox) {
    this.toggleLoading();

    var sFilter = '';
    if(oCheckbox.checked)
        sFilter = $('#pmt-filter-text-' + sType).val();

    this._getOrders({
        type: sType,
        filter: sFilter
    });
}

BxPmtOrders.prototype._getOrders = function(oParams) {
    var $this = this;
    var oDate = new Date();
    oParams['_t'] = oDate.getTime();

    $.post(
        this._sActionsUrl + 'act_get_orders/',
        oParams,
        function(oData) {
            $this.toggleLoading();

            if(parseInt(oData.code) != 0) {
                $this.showResultInline({code: oData.code, message: oData.message, parent_id: 'pmt-form-' + oParams.type});
                return;
            }

            $('#pmt-orders-' + oParams.type + ' .pmt-orders-content').bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                $(this).html();
                $(this).html(oData.data).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
            })
        },
        'json'
    );
}
