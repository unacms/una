/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

Array.prototype.indexOfMediaObject = function (o) {
    var l = this.length;
    for (var i=0 ; i < l ; ++i) {
        if (o['media-id'] == this[i]['media-id'])
            return i;
    }
    return -1;
}

function bx_albums_open_gallery(e, sContext) {

    var gallery;
    var pswpElement = document.querySelectorAll('.pswp')[0];
    var $e = $(e);
    var aItems = []; // more items are added dynamically
    var oItem = {};
    var iCount = 0;

    $.each(e.attributes, function(i, attr) {
        var sName = attr.name;
        if (sName.indexOf('data-') !== 0)
            return;
        sName = sName.replace('data-', '');
        oItem[sName] = attr.value;
        ++iCount;
    });

    if (!iCount) // no image - no concert!
        return;

    aItems.push(oItem);

    var options = {
        bx_albums_context: sContext,
        shareEl: false,
        counterEl: false,
        history: false,
        loop: false,
        index: 0
    };

    fnLoadMoreItem = function (oItemCurrent, bFirstLoad) {

        if (0 != aItems.indexOfMediaObject(oItemCurrent) && (aItems.length-1) != aItems.indexOfMediaObject(oItemCurrent)) // load addutional items only for items on the border
            return;

        var sUrl = sUrlRoot + 'modules/index.php?r=albums/get_sibling_media/' + oItemCurrent['media-id'] + '/' + sContext;
        $.getJSON(sUrl, function(oData) {

            if ('undefined' !== typeof(oData.error)) {
                console.log(oData.error);
                return;
            }

            var l = aItems.length;

            if (0 == aItems.indexOfMediaObject(oItemCurrent) && 'undefined' !== typeof(oData.prev.url)) {
                aItems.unshift(bx_albums_convert_media(oData.prev));
                if (bFirstLoad)
                    options.index = 1;
                else
                    gallery.goTo(gallery.getCurrentIndex() + 1);
            }
    
            if ((aItems.length - 1) == aItems.indexOfMediaObject(oItemCurrent) && 'undefined' !== typeof(oData.next.url))
                aItems.push(bx_albums_convert_media(oData.next));

            if (!bFirstLoad && l != aItems.length) {
                gallery.invalidateCurrItems();
                gallery.updateSize(true);
            }

            if (bFirstLoad) {
                gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, aItems, options);
                gallery.init();

                gallery.listen('beforeChange', function() { 
                    // load more items if we are on first or on the last item
                    fnLoadMoreItem(gallery.currItem, false);
                });

                gallery.listen('afterChange', function() { 
                    var oItem = gallery.currItem;
                    
                    // refresh url when we navigate to next/prev item
                    bx_albums_reload_page(oItem.url, oItem.title, true, false);

                    // disable prev item and action if we are on the first item
                    if (0 == aItems.indexOfMediaObject(oItem)) {
                        $('.pswp__button--arrow--left').hide();
                        glBxAlbumsPrevFn = gallery.prev;
                        gallery.prev = function () { };
                    } else {
                        $('.pswp__button--arrow--left').show();
                        if ('undefined' !== typeof(glBxAlbumsPrevFn))
                            gallery.prev = glBxAlbumsPrevFn;
                    }

                    // disable next item and action when we are in the last item
                    if ((aItems.length-1) == aItems.indexOfMediaObject(oItem)) {
                        $('.pswp__button--arrow--right').hide();
                        glBxAlbumsNextFn = gallery.next;
                        gallery.next = function () { };
                    } else {
                        $('.pswp__button--arrow--right').show();
                        if ('undefined' !== typeof(glBxAlbumsNextFn))
                            gallery.next = glBxAlbumsNextFn;
                    }
                });

                gallery.listen('close', function() {
                    // upon gallery close reload page via AJAX, since url was changes when we navigate through items in the gallery
                    bx_albums_reload_page(gallery.currItem.url, gallery.currItem.title, false, true);
                });

            }
        });
    };

    fnLoadMoreItem(oItem, true);
}

function bx_albums_convert_media(oMedia) {
    var oMap = {
        'id': 'media-id',
        'url': 'url',
        'url_img' : 'src',
        'w': 'w',
        'h': 'h',
        'title': 'title',
    };
    var o = {};
    for (var i in oMap)
        o[oMap[i]] = oMedia[i];
    return o;
}

function bx_albums_reload_page(sUrl, sTitle, bChangeHistory, bChangeContent) {

    // if HTML5 history API isn't supported then use href attribute in the link
    if (!(window.history && history.pushState))
        return true; // when onclick returns true, page is reloaded using href attribute

    // reload all blocks on the page using AJAX
    if ('undefined' === typeof(bChangeContent) || bChangeContent) {
        $('.bx-layout-wrapper .bx-page-block-container').each(function () {
            var iId = parseInt($(this).attr('id').replace('bx-page-block-',''));
            if (iId)
                loadDynamicBlock(iId, sUrl);
        });
    }

    // remember actual url, to not load it twice
    glBxAlbumsActualUrl = sUrl;

    // change history
    if ('undefined' === typeof(bChangeHistory) || bChangeHistory) {
        if ('undefined' === typeof(sTitle))
            sTitle = null;
        History.pushState({title:sTitle, url:sUrl}, sTitle, sUrl);
    }

    return false; // when onclick returns false, href attr isn't used
}

function bx_albums_replace_url(sUrl) {

}

$(document).ready(function () {
    glBxAlbumsActualUrl = location.href;    
    // listen when we press back and forward buttons in browser
    History.Adapter.bind(window, 'statechange', function(event) { 
        var oState = History.getState();        
        if ('undefined' !== typeof(oState) && glBxAlbumsActualUrl != oState.url)
           bx_albums_reload_page(oState.url, oState.title, false);
    });
});

/** @} */
