function BxNtfsMain() {}

BxNtfsMain.prototype.loadingInButton = function(e, bShow) {
	if($(e).length)
		bx_loading_btn($(e), bShow);
	else
		bx_loading($('body'), bShow);	
};

BxNtfsMain.prototype.loadingInItem = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('.bx-ntfs-item:first') : $('body'); 
	bx_loading(oParent, bShow);
};

BxNtfsMain.prototype.loadingInBlock = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
	bx_loading(oParent, bShow);
};

BxNtfsMain.prototype._loading = function(e, bShow) {
	var oParent = $(e).length ? $(e) : $('body'); 
	bx_loading(oParent, bShow);
};

BxNtfsMain.prototype._getDefaultData = function () {
	var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};
