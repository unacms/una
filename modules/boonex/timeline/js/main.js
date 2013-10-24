function BxTimelineMain() {
	this.sIdView = '#bx-timeline';
	this.sIdItem = '#bx-timeline-item-';
}

BxTimelineMain.prototype.initMasonry = function() {
	var oItems = $(this.sIdView + ' .bx-tl-items');

	if(oItems.find('.bx-tl-item').length > 0)
		oItems.masonry({
		  itemSelector: '.bx-tl-item',
		  columnWidth: 183,
		  gutterWidth: 20,
		  isAnimated: false
		});
};

BxTimelineMain.prototype.loadingInButton = function(e, bShow) {
	if($(e).length)
		bx_loading_btn($(e), bShow);
	else
		bx_loading($('body'), bShow);	
};

BxTimelineMain.prototype.loadingInBlock = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
	bx_loading(oParent, bShow);
};

BxTimelineMain.prototype._loading = function(e, bShow) {
	var oParent = $(e).length ? $(e) : $('body'); 
	bx_loading(oParent, bShow);
};

BxTimelineMain.prototype._getDefaultData = function () {
	var oDate = new Date();
	this._oRequestParams._t = oDate.getTime();
    return this._oRequestParams;
};