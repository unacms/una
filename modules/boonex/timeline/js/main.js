function BxTimelineMain() {
	this.sIdView = '#bx-timeline';
	this.sIdItem = '#bx-timeline-item-';

	this.sClassMasonry = "bx-tl-masonry";
}

BxTimelineMain.prototype.isMasonry = function() {
	return $(this.sIdView + ' .bx-tl-items').hasClass(this.sClassMasonry);
};

BxTimelineMain.prototype.isMasonryEmpty = function() {
	return $(this.sIdView + ' .bx-tl-items .bx-tl-item').length == 0;
};

BxTimelineMain.prototype.initMasonry = function() {
	var oItems = $(this.sIdView + ' .bx-tl-items');

	if(oItems.find('.bx-tl-item').length > 0) {
		oItems.addClass(this.sClassMasonry).masonry({
		  itemSelector: '.bx-tl-item',
		  columnWidth: '.bx-tl-grid-sizer'
		}).masonry('on', 'layoutComplete', function() {
			$('img.bx-tl-item-image').each(function() {
				var oImg = $(this);

				/*
				var iImgWrpWidth = oImg.parents('div.bx-tl-item-image:first').width();
				if(iImgWrpWidth < oImg.width())
					oImg.width(iImgWrpWidth);
				*/
			});
		});
	}
};

BxTimelineMain.prototype.destroyMasonry = function() {
	$(this.sIdView + ' .bx-tl-items').removeClass(this.sClassMasonry).masonry('destroy');
};

BxTimelineMain.prototype.appendMasonry = function(oItems) {
	var oItems = $(oItems);        			
	$(this.sIdView + ' .bx-tl-items').append(oItems).masonry('appended', oItems);
};

BxTimelineMain.prototype.prependMasonry = function(oItems) {
	var oItems = $(oItems);        			
	$(this.sIdView + ' .bx-tl-items').prepend(oItems).masonry('prepended', oItems);
};

BxTimelineMain.prototype.reloadMasonry = function() {
	$(this.sIdView + ' .bx-tl-items').masonry('reloadItems').masonry('layout');
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