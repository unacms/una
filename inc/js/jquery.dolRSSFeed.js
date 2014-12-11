/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

(function($){
	$.fn.dolRSSFeed = function(oOptions) {
		var oOptions = oOptions || {};

		return this.each( function(){

			var $Cont = $(this);
			var sRSSObject = $Cont.attr( 'rssobject' );
			var iRSSID = $Cont.attr( 'rssid' );
			if((!iRSSID || !sRSSObject) && oOptions.forceUrl == undefined)
				return false;

			var iMaxNum = parseInt( $Cont.attr( 'rssnum' ) || 0 );
			var iMemID  = parseInt( $Cont.attr( 'member' ) || 0 );

			var sFeedURL = (oOptions.forceUrl != undefined) ? oOptions.forceUrl : sUrlRoot + 'get_rss_feed.php?object=' + sRSSObject + '&id=' + iRSSID + '&member=' + iMemID;

            bx_loading_animate($(this).find('.bx-loading-ajax'));

            $.getFeed( {
				url: sFeedURL,
				error: function(oResponse) {
					if(typeof oOptions.onError == 'function')
						oOptions.onError();
				},
				success: function(feed) {
					if (feed == undefined || !feed.items) 
						return;

					if(typeof oOptions.onLoad == 'function')
						oOptions.onLoad();

					var sCode =
						'<div class="sys-rss-feed-wrapper bx-def-bc-margin bx-def-padding-sec-top">';
					var sTarget, iCount = 0;
					for( var iItemId = 0; iItemId < feed.items.length; iItemId ++ ) {
						var item = feed.items[iItemId];
						var sDate = '', oDate, a;

                        if (null != (a = item.updated.match(/(\d+)-(\d+)-(\d+)T(\d+):(\d+):(\d+)Z/))) {
                            oDate = new Date( a[1], a[2]-1, a[3], a[4], a[5], a[6], 0 );
                            sDate = oDate.toLocaleString();
                        } else if (item.updated.length > 0) {
							oDate = new Date(item.updated.replace(/z$/i, "-00:00"));
                            sDate = isNaN(oDate) ? '' : oDate.toLocaleString();
                        }

                        sTarget = '';
                        if (item.link.substring(0, sUrlRoot.length) != sUrlRoot) // open external links in new window
                            sTarget = 'target="_blank"';
                        
						sCode +=								
							'<div class="sys-rss-item-wrapper">' +
								'<div class="sys-rss-item-header bx-def-font-h2">' +
									'<a href="' + item.link + '" ' + sTarget + '>' + item.title + '</a>' +
								'</div>' +
								'<div class="sys-rss-item-desc">' + item.description + '</div>' +
								'<div class="sys-rss-item-info bx-def-font-small bx-def-font-grayed">' +
									'<span>' +
										sDate +
									'</span>' +
								'</div>' +
							'</div>' +
                            '<hr class="bx-def-hr bx-def-margin-sec-top bx-def-margin-sec-bottom" />';
						
						iCount ++;
						if( iCount == iMaxNum )
							break;
					}
						
                    sTarget = '';
                    if (feed.link.substring(0, sUrlRoot.length) != sUrlRoot) // open external links in new window
                        sTarget = 'target="_blank"';

					sCode +=
						'</div>' +
                        
                        '<div class="sys-rss-read-more">' +
                            '<i class="sys-icon link"></i><a href="' + feed.link + '" ' + sTarget + ' class="bx-def-margin-sec-left sys-rss-read-more-link">' + feed.title + '</a>' +
                        '</div>';

					$Cont.hide().html( sCode );

					if(typeof oOptions.onBeforeShow == 'function')
						oOptions.onBeforeShow();

					$Cont.show();

					if(typeof oOptions.onShow == 'function')
						oOptions.onShow();
				}
            });
		});
	};
})(jQuery);

/** @} */
