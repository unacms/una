function BxTimelineMain() {
	this.sIdPost = '#bx-timeline-post';

	this.sIdView = '#bx-timeline';
	this.sIdItem = '#bx-timeline-item-';

	this.sSP = 'bx-tl';
	this.sClassMasonry = this.sSP + '-masonry';
	this.sClassItems = this.sSP + '-items';
	this.sClassItem = this.sSP + '-item';
	this.sClassItemComments = this.sSP + '-item-comments-holder';
	this.sClassItemImages = this.sSP + '-item-images';
	this.sClassItemImage = this.sSP + '-item-image';
}

BxTimelineMain.prototype.isMasonry = function() {
	return $(this.sIdView + ' .' + this.sClassItems).hasClass(this.sClassMasonry);
};

BxTimelineMain.prototype.isMasonryEmpty = function() {
	return $(this.sIdView + ' .' + this.sClassItems + ' .' + this.sClassItem).length == 0;
};

BxTimelineMain.prototype.initMasonry = function() {
	var oItems = $(this.sIdView + ' .' + this.sClassItems);

	if(oItems.find('.' + this.sClassItem).length > 0) {
		oItems.addClass(this.sClassMasonry).masonry({
		  itemSelector: '.' + this.sClassItem,
		  columnWidth: '.' + this.sSP + '-grid-sizer'
		});
	}
};

BxTimelineMain.prototype.destroyMasonry = function() {
	$(this.sIdView + ' .' + this.sClassItems).removeClass(this.sClassMasonry).masonry('destroy');
};

BxTimelineMain.prototype.appendMasonry = function(oItems) {
	var $this = this;
	var oItems = $(oItems);
	oItems.find('img.' + this.sSP + '-item-image').load(function() {
		$this.reloadMasonry();
	});
	$(this.sIdView + ' .' + this.sClassItems).append(oItems).masonry('appended', oItems);
};

BxTimelineMain.prototype.prependMasonry = function(oItems) {
	var $this = this;
	var oItems = $(oItems);
	oItems.find('img.' + this.sSP + '-item-image').load(function() {
		$this.reloadMasonry();
	});

	var oHolder = $(this.sIdView + ' .' + this.sClassItems).prepend(oItems);
	if(!this.isMasonry())
		this.initMasonry();
	else
		oHolder.masonry('prepended', oItems);
};

BxTimelineMain.prototype.removeMasonry = function(oItems, onRemove) {
	var $this = this;
	var oItems = $(oItems);

	var oHolder = $(this.sIdView + ' .' + this.sClassItems);
	if(typeof onRemove === 'function')
		oHolder.masonry('once', 'removeComplete', onRemove);

	oHolder.masonry('remove', oItems).masonry('layout');
};

BxTimelineMain.prototype.reloadMasonry = function() {
	$(this.sIdView + ' .' + this.sClassItems).masonry('reloadItems').masonry('layout');
};

BxTimelineMain.prototype.initFlickity = function() {
	var $this = this;

	$(this.sIdView + ' .' + this.sClassItemImages).each(function() {
		if($(this).find('.' + $this.sClassItemImage).length <= 1)
			return;

		$(this).flickity({
			cellSelector: 'div.' + $this.sClassItemImage,
			cellAlign: 'left',
			pageDots: false,
			imagesLoaded: true
		});
	});
};

BxTimelineMain.prototype.loadingInButton = function(e, bShow) {
	if($(e).length)
		bx_loading_btn($(e), bShow);
	else
		bx_loading($('body'), bShow);	
};

BxTimelineMain.prototype.loadingInItem = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('.' + this.sClassItem + ':first') : $('body'); 
	bx_loading(oParent, bShow);
};

BxTimelineMain.prototype.loadingInBlock = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
	bx_loading(oParent, bShow);
};

BxTimelineMain.prototype.loadingInPopup = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('.bx-popup-content:first') : $('body'); 
	bx_loading(oParent, bShow);
};

BxTimelineMain.prototype._loading = function(e, bShow) {
	var oParent = $(e).length ? $(e) : $('body'); 
	bx_loading(oParent, bShow);
};

BxTimelineMain.prototype._getDefaultData = function () {
	var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};
