/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

function BxTimelineMain() {
    this.sIdPost = '#bx-timeline-post';
    this.sIdPostForm = 'form#bx_timeline_post';

    this.sId = '#bx-timeline';

    this.sSP = 'bx-tl';
    this.sClassView = this.sSP + '-view';
    this.sClassMasonry = this.sSP + '-masonry';
    this.sClassItems = this.sSP + '-items';
    this.sClassItem = this.sSP + '-item';
    this.sClassItemCnt = this.sSP + '-item-cnt';
    this.sClassDividerToday = this.sSP + '-divider-today';
    this.sClassItemContent = this.sSP + '-item-content';
    this.sClassItemComments = this.sSP + '-item-comments-holder';
    this.sClassItemImages = this.sSP + '-item-images';
    this.sClassItemImage = this.sSP + '-item-image';
    this.sClassItemVideos = this.sSP + '-item-videos';
    this.sClassItemVideo = this.sSP + '-item-video';
    this.sClassItemAttachments = this.sSP + '-item-attachments';
    this.sClassItemAttachment = this.sSP + '-item-attachment';
    this.sClassSample = this.sSP + '-sample';
    this.sClassBlink = this.sSP + '-blink';
    this.sClassJumpTo = this.sSP + '-jump-to';

    this.oView = null;
    this.bViewTimeline = false;
    this.bViewOutline = false;
    this.bViewItem = false;
}

BxTimelineMain.prototype.initView = function() 
{
    //Do some basic initialization here.
};

BxTimelineMain.prototype.initVideos = function(oParent) {
    oParent.find('iframe').load(function() {
        $(this).height(($(this).contents().find('video').height()) + 'px');
    });
};

BxTimelineMain.prototype.isMasonry = function() {
    return this.oView.find('.' + this.sClassItems).hasClass(this.sClassMasonry);
};

BxTimelineMain.prototype.isMasonryEmpty = function() {
    return this.oView.find('.' + this.sClassItems + ' .' + this.sClassItem).length == 0;
};

BxTimelineMain.prototype.initMasonry = function(onComplete) {
    var $this = this;
    var oHolder = this.oView.find('.' + this.sClassItems);

    var oItems = oHolder.find('.' + this.sClassItem);
    if(oItems.length == 0) 
        return;

    oItems.resize(function(){
            $this.reloadMasonry();
    }).find('.bx-tl-item-text .bx-tl-content').checkOverflowHeight(this.sSP + '-overflow', function(oElement) {
            $this.onFindOverflow(oElement);
    });

    oHolder.addClass(this.sClassMasonry).masonry({
      itemSelector: '.' + this.sClassItem,
      columnWidth: '.' + this.sSP + '-grid-sizer'
    }).masonry('once', 'layoutComplete', function() {
        if(typeof onComplete === 'function')
            onComplete(oItems);
    });
};

BxTimelineMain.prototype.destroyMasonry = function() {
    this.oView.find('.' + this.sClassItems).removeClass(this.sClassMasonry).masonry('destroy');
};

BxTimelineMain.prototype.appendMasonry = function(oItems, onComplete) {
    var $this = this;
    var oItems = $(oItems);
    oItems.resize(function(){
            $this.reloadMasonry();
    }).find('iframe').load(function() {
            $this.reloadMasonry();
    }).find('img.' + this.sSP + '-item-image').load(function() {
            $this.reloadMasonry();
    });

    var oHolder = this.oView.find('.' + this.sClassItems).masonry('layout').append(oItems);
    if(!this.isMasonry())
        this.initMasonry(onComplete);
    else
        oHolder.masonry('appended', oItems).masonry('layout').masonry('once', 'layoutComplete', function() {
            if(typeof onComplete === 'function')
                onComplete(oItems);
        });
};

BxTimelineMain.prototype.prependMasonry = function(oItems, onComplete) {
    var $this = this;
    var oItems = $(oItems);
    oItems.resize(function(){
        $this.reloadMasonry();
    }).find('iframe').load(function() {
        $this.reloadMasonry();
    }).find('img.' + this.sSP + '-item-image').load(function() {
        $this.reloadMasonry();
    });

    var oHolder = this.oView.find('.' + this.sClassItems).masonry('layout').prepend(oItems);
    if(!this.isMasonry())
        this.initMasonry(onComplete);
    else
        oHolder.masonry('prepended', oItems).masonry('layout').masonry('once', 'layoutComplete', function() {
            if(typeof onComplete === 'function')
                onComplete(oItems);
        });
};

BxTimelineMain.prototype.removeMasonry = function(oItems, onRemove) {
    var $this = this;
    var oItems = $(oItems);

    var oHolder = this.oView.find('.' + this.sClassItems);
    if(typeof onRemove === 'function')
        oHolder.masonry('once', 'removeComplete', onRemove);

    oHolder.masonry('remove', oItems).masonry('layout');
};

BxTimelineMain.prototype.reloadMasonry = function() {
    this.oView.find('.' + this.sClassItems).masonry('reloadItems').masonry('layout');
};

BxTimelineMain.prototype.initFlickityImages = function(oParent, sSelectorItemImage) {
    var sItem = sSelectorItemImage ? sSelectorItemImage : 'div.' + this.sClassItemImage;
    if(oParent.find(sItem).length <= 1)
        return;

    var oCarousel = oParent;

    oCarousel.flickity({
        cellSelector: sItem,
        cellAlign: 'left',
        imagesLoaded: true,
        wrapAround: true,
        pageDots: false
    });

    oCarousel.find(sItem + ' img').each(function() {
        $(this).load(function() {
            oCarousel.flickity('resize');
        });
    });
};

BxTimelineMain.prototype.initFlickityVideos = function(oParent) {
    var sItem = 'div.' + this.sClassItemVideo;
    if(oParent.find(sItem).length <= 1)
        return;

    var oCarousel = oParent;

    oCarousel.flickity({
        cellSelector: sItem,
        cellAlign: 'left',
        imagesLoaded: true,
        wrapAround: true,
        pageDots: false
    });

    oCarousel.find(sItem + ' video').each(function() {
        this.addEventListener('loadedmetadata', function() {
            oCarousel.flickity('resize');
        }, true);
    });
};

BxTimelineMain.prototype.initFlickityAttachments = function(oParent) {
    var sItem = 'div.' + this.sClassItemAttachment;
    if(oParent.find(sItem).length <= 1)
        return;

    var oCarousel = oParent;

    oCarousel.flickity({
        cellSelector: sItem,
        cellAlign: 'left',
        imagesLoaded: true,
        wrapAround: true,
        pageDots: false
    });

    oCarousel.find(sItem + ' img').each(function() {
        $(this).load(function() {
            oCarousel.flickity('resize');
        });
    });

    oCarousel.find(sItem + ' video').each(function() {
        this.addEventListener('loadedmetadata', function() {
            oCarousel.flickity('resize');
        }, true);
    });
};

BxTimelineMain.prototype.initFlickity = function(oView) {
    var $this = this;

    //--- init Flickity for images (may be used in header section)
    oView.find('.' + this.sClassItem + ' .' + this.sClassItemImages + '.' + this.sSP + '-ii-gallery').each(function() {
        $this.initFlickityImages($(this));
    });

    //--- init Flickity for videos (may be used in header section)
    oView.find('.' + this.sClassItem + ' .' + this.sClassItemVideos + '.' + this.sSP + '-iv-gallery').each(function() {
        $this.initFlickityVideos($(this));
    });

    //--- init Flickity for attachments (images and video in attachments seation)
    oView.find('.' + this.sClassItem + ' .' + this.sClassItemAttachments + '.' + this.sSP + '-ia-gallery').each(function() {
        $this.initFlickityAttachments($(this));
    });

    //--- init Flickity for attachments (files in attachments seation)
    oView.find('.' + this.sClassItem + ' .' + this.sClassItemAttachments + '.' + this.sSP + '-iaf-gallery').each(function() {
        $this.initFlickityAttachments($(this));
    });
};

BxTimelineMain.prototype.initFlickityByItem = function(oItem) {
    //--- init Flickity for images (may be used in header section)
    var oGalleryImages = $(oItem).find('.' + this.sClassItemImages + '.' + this.sSP + '-ii-gallery');
    if(oGalleryImages.length > 0)
        this.initFlickityImages(oGalleryImages);

    //--- init Flickity for videos (may be used in header section)
    var oGalleryVideos = $(oItem).find('.' + this.sClassItemVideos + '.' + this.sSP + '-iv-gallery');
    if(oGalleryVideos.length > 0)
        this.initFlickityVideos(oGalleryVideos);

    //--- init Flickity for attachments (images and video in attachments seation)
    var oGalleryAttachments = $(oItem).find('.' + this.sClassItemAttachments + '.' + this.sSP + '-ia-gallery');
    if(oGalleryAttachments.length > 0)
        this.initFlickityAttachments(oGalleryAttachments);
};

BxTimelineMain.prototype.onFindOverflow = function(oElement) {
    $(oElement).after($(oElement).parents('.' + this.sClassView + ':first').find('.' + this.sSP + '-content-show-more:hidden:first').clone().show());
};

BxTimelineMain.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);	
};

BxTimelineMain.prototype.loadingInItem = function(e, bShow) {
    var oParent = $('body');
    if($(e).length)
        oParent = !$(e).hasClass(this.sClassItem) ? $(e).parents('.' + this.sClassItem + ':first') : $(e);

    bx_loading(oParent.find('.' + this.sClassItemCnt), bShow);
};

BxTimelineMain.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxTimelineMain.prototype.loadingInBlockHeaderCustom = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-tl-v-header:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxTimelineMain.prototype.loadingInPopup = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-popup-content:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxTimelineMain.prototype.loadingIn = function(e, bShow) {
    var oElement = $(e);
    var bElement = oElement.length > 0;

    if(bElement && oElement.hasClass('bx-btn'))
        this.loadingInButton(e, bShow);
    else if(bElement && (oElement.hasClass(this.sClassItem) || oElement.parents('.' + this.sClassItem + ':first').length > 0))
        this.loadingInItem(e, bShow);
    else if(bElement && oElement.parents('.bx-popup-content:first').length > 0)
        this.loadingInPopup(e, bShow);
    else if(bElement && oElement.parents('.bx-db-container:first').length > 0)
        this.loadingInBlock(e, bShow);
    else if(bElement && oElement.parents('.bx-tl-v-header:first').length > 0)
        this.loadingInBlockHeaderCustom(e, bShow);
    else 
        bx_loading($('body'), bShow);
};

BxTimelineMain.prototype._loading = function(e, bShow) {
    var oParent = $(e).length ? $(e) : $('body'); 
    bx_loading(oParent, bShow);
};

BxTimelineMain.prototype._getViewObject = function(oElement) {
    oElement = $(oElement);

    var oView = oElement;
    if(oView.hasClass(this.sClassView))
        return oView;

    oView = oElement.parents('.' + this.sClassView + ':first');
    if(oView.hasClass(this.sClassView))
        return oView;

    oView = oElement.parents('.bx-page-block-container:first').find('.' + this.sClassView + ':first');
    if(oView.hasClass(this.sClassView))
        return oView;

    return null;
};

BxTimelineMain.prototype._getView = function(oElement) {
    var oView = this._getViewObject(oElement);
    if(!oView || oView.length == 0)
        return '';

    if(oView.hasClass(this.sClassView + '-item'))
        return 'item';

    if(oView.hasClass(this.sClassView + '-timeline'))
        return 'timeline';

    if(oView.hasClass(this.sClassView + '-outline'))
        return 'outline';

    if(oView.hasClass(this.sClassView + '-search'))
        return 'search';

    return '';
};

BxTimelineMain.prototype._getName = function(oData, oRules)
{
    oRules = jQuery.extend({}, oRules);

    var bWithView = oRules.with_view === undefined || oRules.with_view === true;
    var bWithType = oRules.with_type === undefined || oRules.with_type === true;
    var bWithOwner = oRules.with_owner !== undefined && oRules.with_owner === true;
    var sGlue = oRules.glue !== undefined && oRules.glue ? oRules.glue : '_';

    var aAddons = [];
    if(oData['name'])
        aAddons.push(oData['name']);
    else {
        if(bWithView && oData['view'])
            aAddons.push(oData['view']);

        if(bWithType && oData['type'])
            aAddons.push(oData['type']);
    }

    if(bWithOwner)
        aAddons.push(this._iOwnerId);

    return aAddons.length > 0 ? aAddons.join(sGlue) : '';
};

BxTimelineMain.prototype._getHtmlId = function(sKey, oData, oRules)
{
    oRules = jQuery.extend({}, oRules);

    var bWhole = oRules.whole === undefined || oRules.whole === true;
    var bHash = oRules.hash === undefined || oRules.hash === true;
    var sGlue = oRules.glue !== undefined && oRules.glue ? oRules.glue : '_';

    var sHtmlId = this.sId  + sGlue + sKey + sGlue + this._getName(oData, oRules) + (!bWhole ? sGlue : '');
    if(!bHash)
        sHtmlId = sHtmlId.substr(1);

    return sHtmlId.replace(new RegExp(sGlue, 'g'), '-');
};

BxTimelineMain.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};


BxTimelineMain.prototype._getPost = function(oView, iId, aParams) {
    var $this = this;
    var oData = this._getDefaultData();
    oData = jQuery.extend({}, oData, {js_object: this._sObjName, id: iId}, aParams != undefined ? aParams : {});

    this.loadingInBlock(oView, true);

    jQuery.post (
        this._sActionsUrl + 'get_post/',
        oData,
        function(oData) {
            $this.loadingInBlock(oView, false);

            processJsonData(oData);
        },
        'json'
    );
};

(function($) {
    $.fn.checkOverflowHeight = function(sClass, onFind) {
    	if(!sClass)
    		sClass = 'bx-overflow';

        return this.each(function() {
            var oElement = $(this);
            if(oElement.hasClass(sClass) || oElement.css('overflow') != 'hidden')
            	return;

            if(oElement.prop('scrollHeight') <= oElement.height())
            	return;

            oElement.addClass(sClass);
            if(typeof onFind === 'function')
            	onFind(oElement);
        });
    };
})(jQuery);