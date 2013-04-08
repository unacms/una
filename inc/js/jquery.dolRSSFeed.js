/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

// jQuery plugin - Dolphin RSS Aggregator
(function($){
    $.fn.dolRSSFeed = function(sForceUrl) {
        return this.each( function(){

            var $Cont = $(this);
            var iRSSID = $Cont.attr( 'rssid' );
            if( !iRSSID && sForceUrl == undefined )
                return false;

            var iMaxNum = parseInt( $Cont.attr( 'rssnum' ) || 0 );
            var iMemID  = parseInt( $Cont.attr( 'member' ) || 0 );

            var sFeedURL = (sForceUrl != undefined) ? sForceUrl : sUrlRoot + 'get_rss_feed.php?ID=' + iRSSID + '&member=' + iMemID;

            $.getFeed( {
                url: sFeedURL ,
                success: function(feed) {
                    //if( window.console ) console.log( feed );

                    if (feed != undefined && feed.items) {
                        var sCode =
                            '<div class="rss_feed_wrapper bx-def-padding">';
                        var iCount = 0;
                        for( var iItemId = 0; iItemId < feed.items.length; iItemId ++ ) {
                            var item = feed.items[iItemId];
                            var sDate;
                            var a;
                            var oDate

                            if (null != (a = item.updated.match(/(\d+)-(\d+)-(\d+)T(\d+):(\d+):(\d+)Z/)))
                                oDate = new Date( a[1], a[2]-1, a[3], a[4], a[5], a[6], 0 );
                            else
                                oDate = new Date( item.updated );
                            sDate = oDate.toLocaleString();

                            sCode +=
                                '<div class="rss_item_wrapper bx-def-padding-sec-top">' +
                                    '<div class="rss_item_header">' +
                                        '<a href="' + item.link + '" target="_blank">' + item.title + '</a>' +
                                    '</div>' +
                                    '<div class="rss_item_info bx-def-font-small bx-def-font-grayed">' +
                                        '<span>' +
                                            sDate +
                                        '</span>' +
                                    '</div>' +
                                    '<div class="rss_item_desc">' + item.description + '</div>' +
                                '</div>';

                            iCount ++;
                            if( iCount == iMaxNum )
                                break;
                        }

                        sCode +=
                            '</div>' +

                            '<div class="bx-def-padding-sec-left bx-def-padding-sec-right bx-def-color-bg-sec">' +
                                '<span class="rss_read_more"><a href="' + feed.link + '" target="_blank" class="rss_read_more_link">' + feed.title + '</a></span>' +
                            '</div>' +

                            '<div class="clear_both"></div>';

                        $Cont.html( sCode );
                    }
                }
            } );

        } );
    };
})(jQuery);
