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

function bx_albums_open_gallery(eve, e, sContext) {
    var ePswp = document.querySelectorAll('.pswp')[0];
    var $e = $(e);
    var aItems = []; // more items are added dynamically
    var oItem = {};
    var iCount = 0;
    var options = {
        bx_albums_context: sContext,
        shareEl: false,
        counterEl: false,
        history: false,
        loop: false,
        showHideOpacity: true,
        index: 0,
        getThumbBoundsFn: function(index) {
            var e = $('a[data-media-id] img');
            if (!e.length)
                return false;
            return {x:e.offset().left, y:e.offset().top, w:e.width()};
        }
    };
    var fnProcessRetina = function (o) {
        var dpr = ((window.glBxDisableRetina !== undefined && window.glBxDisableRetina) || window.devicePixelRatio === undefined ? 1 : window.devicePixelRatio);
        if (dpr < 2)
            return o;
        o.w *= 2;
        o.h *= 2;
        return o;
    }

    eve.preventDefault ? eve.preventDefault() : eve.returnValue = false;

    

    // get data for initial item from the attributes of the item which was clicked
    $.each(e.attributes, function(i, attr) {
        var sName = attr.name;
        if (sName.indexOf('data-') !== 0)
            return;
        sName = sName.replace('data-', '');
        oItem[sName] = attr.value;        

        ++iCount;
    });

    oItem.msrc = oItem.src;
    oItem = fnProcessRetina(oItem);

    if (!iCount) // no image - no concert!
        return false;

    aItems.push(oItem);

    var fnConverMedia = function (oMedia) {
        var oMap = {
            'id': 'media-id',
            'url': 'url',
            'url_img' : 'src',
            'w': 'w',
            'h': 'h',
            'title': 'title'
        };

        if ('undefined' !== typeof(oMedia.html) && oMedia.html.length)
            return {'media-id': oMedia['id'], 'title': oMedia['title'], 'html': oMedia['html'], 'url': oMedia['url']};

        var o = {};
        for (var i in oMap)
            o[oMap[i]] = oMedia[i];
        return fnProcessRetina(o);
    };

    var fnDisableArrows = function (oItem) {

        // disable prev item and action if we are on the first item
        if (0 == aItems.indexOfMediaObject(oItem)) {
            $('.pswp__button--arrow--left').hide();
            glBxAlbumsPrevFn = glBxAlbumsGallery.prev;
            glBxAlbumsGallery.prev = function () { };
        } else {
            $('.pswp__button--arrow--left').show();
            if ('undefined' !== typeof(glBxAlbumsPrevFn))
                glBxAlbumsGallery.prev = glBxAlbumsPrevFn;
        }

        // disable next item and action when we are in the last item
        if ((aItems.length-1) == aItems.indexOfMediaObject(oItem)) {
            $('.pswp__button--arrow--right').hide();
            glBxAlbumsNextFn = glBxAlbumsGallery.next;
            glBxAlbumsGallery.next = function () { };
        } else {
            $('.pswp__button--arrow--right').show();
            if ('undefined' !== typeof(glBxAlbumsNextFn))
                glBxAlbumsGallery.next = glBxAlbumsNextFn;
        }
    };

    var fnLoadMoreItem = function (oItemCurrent, bFirstLoad) {

        // load addutional items only for items on the border
        if (0 != aItems.indexOfMediaObject(oItemCurrent) && (aItems.length-1) != aItems.indexOfMediaObject(oItemCurrent))
            return;

        var sUrl = sUrlRoot + 'modules/index.php?r=albums/get_sibling_media/' + oItemCurrent['media-id'] + '/' + sContext;
        $.getJSON(sUrl, function(oData) {

            if ('undefined' !== typeof(oData.error)) {
                if ('undefined' !== typeof(console))
                    console.log(oData.error);
                return;
            }

            var l = aItems.length;

            if (0 == aItems.indexOfMediaObject(oItemCurrent) && 'undefined' !== typeof(oData.prev.url)) {
                aItems.unshift(fnConverMedia(oData.prev));
                if (bFirstLoad)
                    options.index = 1;
                else
                    glBxAlbumsGallery.goTo(glBxAlbumsGallery.getCurrentIndex() + 1);
            }
    
            if ((aItems.length - 1) == aItems.indexOfMediaObject(oItemCurrent) && 'undefined' !== typeof(oData.next.url))
                aItems.push(fnConverMedia(oData.next));

            if (!bFirstLoad && l != aItems.length) {
                glBxAlbumsGallery.invalidateCurrItems();
                glBxAlbumsGallery.updateSize(true);
            }

            if (bFirstLoad) {
                glBxAlbumsGallery = new PhotoSwipe(ePswp, PhotoSwipeUI_Default, aItems, options);
                glBxAlbumsGallery.init();

                glBxAlbumsGallery.listen('beforeChange', function() { 
                    // load more items if we are on first or on the last item
                    fnLoadMoreItem(glBxAlbumsGallery.currItem, false);
                });

                glBxAlbumsGallery.listen('afterChange', function() {
                    var bChangeHistory = 'undefined' !== typeof(glBxAlbumsGallery._bx_albums_skip_history) && glBxAlbumsGallery._bx_albums_skip_history ? false : true;
                    bx_albums_reload_page(glBxAlbumsGallery.currItem.url, glBxAlbumsGallery.currItem.title, bChangeHistory, false); // refresh url when we navigate to next/prev item
                    glBxAlbumsGallery._bx_albums_skip_history = false;

                    fnDisableArrows(glBxAlbumsGallery.currItem);
                });

                glBxAlbumsGallery.listen('close', function() {
                    // upon gallery close reload page via AJAX, since url was changes when we navigate through items in the gallery
                    bx_albums_reload_page(glBxAlbumsGallery.currItem.url, glBxAlbumsGallery.currItem.title, false, true);
                });

                fnDisableArrows(glBxAlbumsGallery.currItem);
            }
        });
    };

    fnLoadMoreItem(oItem, true);

    return false;
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

    if (glBxAlbumsActualUrl == sUrl)
        return false;

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

$(document).ready(function () {
    glBxAlbumsActualUrl = location.href;    
    // listen when we press back and forward buttons in browser
    History.Adapter.bind(window, 'statechange', function(event) { 
        var oState = History.getState();        
        if ('undefined' === typeof(oState) || glBxAlbumsActualUrl == oState.url)
            return;

        if ($('.pswp:visible').length && 'undefined' !== typeof(glBxAlbumsGallery)) {
            var a = /id=(\d+)/.exec(document.location);
            if (a && a.length > 0 && a[1]) {
                for (var i in glBxAlbumsGallery.items) {
                    if (glBxAlbumsGallery.items[i]['media-id'] != a[1])
                        continue;
                    glBxAlbumsGallery._bx_albums_skip_history = true;
                    glBxAlbumsGallery.goTo(i);
                    break;
                }
                        
            }
        } else {
            bx_albums_reload_page(oState.url, oState.title, false);
        }
    });
});

/** @} */
