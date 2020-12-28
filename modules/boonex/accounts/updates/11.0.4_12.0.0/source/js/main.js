function BxAccntMain(oOptions) {
	this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oAccntMain' : oOptions.sObjName;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxAccntMain.prototype.copyToClipboard = function(oElement) {
    var $this = this;

    oClipboard = new ClipboardJS('#' + this._aHtmlIds['password_button'], {
	    target: function(oTrigger) {
	        return $('#' + $this._aHtmlIds['password_text']).get(0);
	    }
	});
	oClipboard.on('success', function(oObject) {
		$this.hidePopup();
	});
};

BxAccntMain.prototype.hidePopup = function() {
	$('.bx-popup-applied:visible').dolPopupHide({onHide: function() {
		$(this).remove();
	}});	
};