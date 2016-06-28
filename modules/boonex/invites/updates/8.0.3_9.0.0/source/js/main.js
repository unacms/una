function BxInvMain(oOptions) {
	this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oInvMain' : oOptions.sObjName;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxInvMain.prototype.showLinkPopup = function(oElement) {
    var $this = this;

    this.loadingInButton(oElement, true);

    jQuery.get(
        this._sActionsUrl + 'get_link/',
        this._getDefaultData(),
        function(oData) {
        	$this.loadingInButton(oElement, false);

        	if(oData && oData.popup != undefined) {
        		var oPopup = $(oData.popup);
        		var sPopupId = oPopup.attr('id');
        		var oClipboard = null;

            	$('#' + sPopupId).remove();
                oPopup.hide().prependTo('body').dolPopup({
                    fog: {
        				color: '#fff',
        				opacity: .7
                    },
                    onShow: function () {
                    	oClipboard = new Clipboard('#' + $this._aHtmlIds['link_popup'] + ' .bx-btn[name = "clipboard"]', {
                    	    target: function(oTrigger) {
                    	        return $('#' + sPopupId).find('[name = "link"]').get(0);
                    	    }
                    	});
                    	oClipboard.on('success', function(oObject) {
                    		$this.hideLinkPopup();
                    	});
                    },
                    onHide: function () {
                    	if(oClipboard)
                    		oClipboard.destroy();
                    }
                });
        	}

        	if(oData && oData.message != undefined)
        		alert(oData.message);
        },
        'json'
    );
};

BxInvMain.prototype.hideLinkPopup = function() {
	$('#' + this._aHtmlIds['link_popup']).dolPopupHide();	
};

BxInvMain.prototype.loadingInButton = function(e, bShow) {
	if($(e).length)
		bx_loading_btn($(e), bShow);
	else
		bx_loading($('body'), bShow);	
};

BxInvMain.prototype._loading = function(e, bShow) {
	var oParent = $(e).length ? $(e) : $('body'); 
	bx_loading(oParent, bShow);
};

BxInvMain.prototype._getDefaultData = function () {
	var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};
