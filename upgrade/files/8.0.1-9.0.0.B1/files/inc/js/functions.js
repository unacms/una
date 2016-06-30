/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

function getHtmlData( elem, url, callback, method , confirmation)
{
    if ('undefined' != typeof(confirmation) && confirmation && !confirm(_t('_are you sure?'))) 
        return false;

    // in most cases it is element ID, in other cases - object of jQuery
    if (typeof elem == 'string')
        elem = '#' + elem; // create selector from ID

    var $block = $(elem);

    var blockPos = $block.css('position');

    $block.css('position', 'relative'); // set temporarily for displaying "loading icon"

    bx_loading_content($block, true);
    var $loadingDiv = $block.find('.bx-loading-ajax');

    var iLeftOff = parseInt(($block.innerWidth() / 2.0) - ($loadingDiv.outerWidth()  / 2.0));
    var iTopOff  = parseInt(($block.innerHeight() / 2.0) - ($loadingDiv.outerHeight()));
    if (iTopOff<0) iTopOff = 0;

    $loadingDiv.css({
        position: 'absolute',
        left: iLeftOff,
        top:  iTopOff
    });

    if (undefined != method && (method == 'post' || method == 'POST')) {

        $.post(url, function(data) {

            $block.html(data);
	        $block.css('position', blockPos).bxTime();
            if ($.isFunction($.fn.addWebForms))
                $block.addWebForms();

            bx_activate_anim_icons();

            if (typeof callback == 'function')
                callback.apply($block);
        });

    } else {

        $block.load(url + '&_r=' + Math.random(), function() {

	        $(this).css('position', blockPos).bxTime();
            if ($.isFunction($.fn.addWebForms))
                $(this).addWebForms();

            bx_activate_anim_icons();

            if (typeof callback == 'function')
                callback.apply(this);
        });

    }
}

function loadDynamicBlockAutoPaginate (e, iStart, iPerPage, sAdditionalUrlParams) {

    sUrl = location.href;

    sUrl = sUrl.replace(/start=\d+/, '').replace(/per_page=\d+/, '').replace(/[&\?]+$/, '');
    sUrl = bx_append_url_params(sUrl, 'start=' + parseInt(iStart) + '&per_page=' + parseInt(iPerPage));
    if ('undefined' != typeof(sAdditionalUrlParams))
        sUrl = bx_append_url_params(sUrl, sAdditionalUrlParams);

    return loadDynamicBlockAuto(e, sUrl);
}

/**
 * This function reloads page block automatically, 
 * just provide any element inside the block and this function will reload the block.
 * @param e - element inside the block
 * @return true on success, or false on error - particularly, if block isn't found
 */
function loadDynamicBlockAuto(e, sUrl) {
    var sId = $(e).parents('.bx-page-block-container:first').attr('id');

    if ('undefined' == typeof(sUrl))
        sUrl = location.href;

    if (!sId || !sId.length)
        return false;

    var aMatches = sId.match(/\-(\d+)$/);
    if (!aMatches || !aMatches[1])
        return false;
        
    loadDynamicBlock(parseInt(aMatches[1]), sUrl);
    return true;
}

function loadDynamicBlock(iBlockID, sUrl) {
    getHtmlData($('#bx-page-block-' + iBlockID), bx_append_url_params(sUrl, 'dynamic=tab&pageBlock=' + iBlockID));
    return true;
}

function loadDynamicPopupBlock(iBlockID, sUrl) {
    if (!$('#dynamicPopup').length) {
        $('<div id="dynamicPopup" style="display:none;"></div>').prependTo('body');
    }

    $('#dynamicPopup').load(
        (sUrl + '&dynamic=popup&pageBlock=' + iBlockID),
        function() {
            $(this).dolPopup({
                left: 0,
                top: 0
            });
        }
    );
}

function closeDynamicPopupBlock() {
    $('#dynamicPopup').dolPopupHide();
}


/**
 * Translate string
 */
function _t(s, arg0, arg1, arg2) {
    if (!window.aDolLang || !aDolLang[s])
        return s;

    cs = aDolLang[s];
    cs = cs.replace(/\{0\}/g, arg0);
    cs = cs.replace(/\{1\}/g, arg1);
    cs = cs.replace(/\{2\}/g, arg2);
    return cs;
}

function showPopupAnyHtml(sUrl, sId) {

    var oPopupOptions = {};

    if (!sId || !sId.length)
        sId = 'login_div';

    $('#' + sId).remove();
    $('<div id="' + sId + '" style="display: none;"></div>').prependTo('body').load(
        sUrl.match('^http[s]{0,1}:\/\/') ? sUrl : sUrlRoot + sUrl,
        function() {
            $(this).dolPopup(oPopupOptions);
        }
    );
}

function bx_loading_svg(sType) {
	var s = '';
	s += '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 520 520" style="enable-background:new 0 0 520 520;" xml:space="preserve">';
	s += '<g class="' + sType + '">';
	s += '<g class="inner">';
	s += '<path class="p1" d="M152,260C152,260,152,260,152,260c0-59.8,48.4-108.4,108.1-108.4c43.3,0,80.9,25.6,97.9,62.4V107.3c-28-18.3-61.9-28.9-98.1-28.9C159.8,78.4,78.6,159.9,78.6,260c0,59.5,28.4,112.4,73.4,145.6V260z"/>';
	s += '<path class="p2" d="M368,114.4V260c0,59.8-48.4,108.4-108.1,108.4c-43.3,0-80.9-25.6-97.9-62.4v106.7c28,18.3,61.9,28.9,98.1,28.9c100.1,0,181.3-81.4,181.3-181.6C441.4,200.5,413,147.6,368,114.4z"/>';
	s += '</g>';
	s += '<g class="outer">';
	s += '<path class="p1" d="M146.9,106.7c-8.1-15-36.9-29.9-68.9-27.3v124.2C90,164.3,114.6,130.5,146.9,106.7z"/>';
	s += '<path class="p2" d="M373.1,413.3c8.1,15,36.7,29.9,68.9,27.3V316.4C429.8,355.7,405.4,389.5,373.1,413.3z"/>';
	s += '</g>';
	s += '</g>';
	s += '</svg>';
	return s;
}

function bx_loading_animate (e, aOptions) {
    e = $(e);
    if (!e.length)
        return false;
    if (e.find('.bx-sys-spinner').length)
        return false;
    return new Spinner(aOptions).spin(e.get(0));
}

function bx_loading_btn (oElement, bEnable) {
	var bUseSvg = true;

    var oButton = $(oElement);

    if (oButton.children('div').size())
        oButton = oButton.children('div').first();

    if(!bEnable)
    	oButton.find('.bx-loading-ajax-btn').remove();
    else if (!oButton.find('.bx-loading-ajax-btn').length)
    	oButton.append('<b class="bx-loading-ajax-btn">' + (bUseSvg ? bx_loading_svg('colored') : '') + '</b>');

    if(!bUseSvg)
    	bx_loading_animate(oButton.find('.bx-loading-ajax-btn'), aSpinnerSmallOpts);    
}

function bx_loading_content (oElement, bEnable, isReplace) {
	var bUseSvg = true;

    var oBlock = $(oElement);
    var oLoading = $('<div class="bx-loading-ajax bx-def-z-index-front">' + (bUseSvg ? bx_loading_svg('colored') : '') + '</div>');
    
    if(!bEnable)
    	oBlock.find(".bx-loading-ajax").remove();
    else if(!oBlock.find('.bx-loading-ajax').length) {
        if('undefined' != typeof(isReplace) && isReplace)
            oBlock.html(oLoading.addClass('static'));
        else
            oBlock.append(oLoading);

        if(!bUseSvg)
        	bx_loading_animate(oBlock.find('.bx-loading-ajax'), aSpinnerOpts);
    } 
}

function bx_loading (elem, b) {

    if (typeof elem == 'string')
        elem = '#' + elem;

    var block = $(elem);

    if (block.hasClass('bx-btn')) {
        bx_loading_btn (block, b);
        return;
    }

    if (1 == b || true == b) {

        bx_loading_content(block, b);

        e = block.find(".bx-loading-ajax");
        e.css('left', parseInt(block.width()/2.0 - e.width()/2.0));

        var he = e.outerHeight();
        var hc = block.outerHeight();

        if (block.css('position') != 'relative' && block.css('position') != 'absolute') {
            if (!block.data('css-save-position'))
                block.data('css-save-position', block.css('position'));
            block.css('position', 'relative');
        }

        if (hc > he) {
            e.css('top', parseInt(hc/2.0 - he/2.0));
        }

        if (hc < he) {
            if (!block.data('css-save-min-height'))
                block.data('css-save-min-height', block.css('min-height'));
            block.css('min-height', he);
        }

    } else {

        if (block.data('css-save-position'))
            block.css('position', block.data('css-save-position'));

        if (block.data('css-save-min-height'))
            block.css('min-height', block.data('css-save-min-height'));

        bx_loading_content(block, b);

    }

}


/**
 * Center content with floating blocks.
 * sSel - jQuery selector of content to be centered
 * sBlockSel - jquery selector of blocks
 */
function bx_center_content (sSel, sBlockStyle, bListenOnResize) {

    var sId;
    if ($(sSel).parent().hasClass('bx-center-content-wrapper')) {
        sId = $(sSel).parent().attr('id');
    } else {
        sId = 'id' + (new Date()).getTime();
        $(sSel).wrap('<div id="'+sId+'" class="bx-center-content-wrapper"></div>');
    }

    var eCenter = $('#' + sId);
    var iAll = $('#' + sId + ' ' + sBlockStyle).size();
    var iWidthUnit = $('#' + sId + ' ' + sBlockStyle + ':first').outerWidth(true);
    var iWidthContainer = eCenter.innerWidth();           
    var iPerRow = parseInt(iWidthContainer/iWidthUnit);
    var iLeft = (iWidthContainer - (iAll > iPerRow ? iPerRow * iWidthUnit : iAll * iWidthUnit)) / 2;

    if (iWidthUnit > iWidthContainer)
        return;

    if ('undefined' != typeof(bListenOnResize) && bListenOnResize) {
        $(window).on('resize.bx-center-content', function () {
            bx_center_content(sSel, sBlockStyle);
        });
    }

    eCenter.css("padding-left", iLeft);
}

/**
 * Show pointer popup with menu from URL.
 * @param o - menu object name
 * @param e - element to show popup at
 * @param options - popup options
 * @param vars - additional GET variables
 */
function bx_menu_popup (o, e, options, vars) {
    var options = options || {};
    var vars = vars || {};
    var o = $.extend({}, $.fn.dolPopupDefaultOptions, {id: o, url: bx_append_url_params('menu.php', $.extend({o:o}, vars))}, options);
    if ('undefined' == typeof(e))
        e = window;
    $(e).dolPopupAjax(o);
}

/**
 * Show pointer popup with menu from existing HTML.
 * @param jSel - jQuery selector for html to show in popup
 * @param e - element to show popup at
 * @param options - popup options
 */
function bx_menu_popup_inline (jSel, e, options) {
    var options = options || {};
    var o = $.extend({}, $.fn.dolPopupDefaultOptions, options, {pointer:{el:$(e)}});
    if ($(jSel + ':visible').length) 
        $(jSel).dolPopupHide(); 
    else 
        $(jSel).dolPopup(o);
}

/**
 * Show pointer popup with menu from existing HTML.
 * @param jSel - jQuery selector for html to show in popup
 * @param e - element to click to open/close slider
 * @param sPosition - 'block' for sliding menu in blocks, 'site' - for sliding main menu
 */
function bx_menu_slide (jSel, e, sPosition) {
    var options = options || {};
    var eSlider = $(jSel);    

    if ('undefined' == typeof(e))
        e = eSlider.data('data-control-btn');

    var eIcon = $(e).find('.sys-icon-a');    

    var fPositionBlock = function () {
        var eBlock = eSlider.parents('.bx-page-block-container');
        eSlider.css({
            position: 'absolute',
            top: eBlock.find('.bx-db-header').outerHeight(true),
            left: 0
            //width: eBlock.width() TODO: Commited to test. Remove if everything is OK.
        });
    };

    var fPositionSite = function () {
        var eToolbar = $('#bx-toolbar');
        eSlider.css({
            position: 'fixed',
            top: eToolbar.outerHeight(true),
            left: 0
        });
    };

    var fClose = function () {
        if (eIcon.length)
            (new Marka(eIcon[0])).set(eIcon.attr('data-icon-orig'));
        eSlider.slideUp()
    };

    var fOpen = function () {
        if (eIcon.length) {
            eIcon.attr('data-icon-orig', eIcon.attr('data-icon'));
            (new Marka(eIcon[0])).set('times');
        }

        if ('block' == sPosition)
            fPositionBlock();
        else
            fPositionSite();
        
        eSlider.slideDown();

        eSlider.data('data-control-btn', e);        
    };    
    
    if ('undefined' == typeof(sPosition))
        sPosition = 'block';

    if ($(jSel + ':visible').length) {
        fClose();

        $(document).off('click.bx-sliding-menu touchend.bx-sliding-menu');
        $(window).off('resize.bx-sliding-menu');
    }
    else {
        bx_menu_slide_close_all_opened();

        $(document).off('click.bx-sliding-menu touchend.bx-sliding-menu');
        $(window).off('resize.bx-sliding-menu');

        fOpen();
        eSlider.find('a').each(function () {
            $(this).off('click.bx-sliding-menu');
            $(this).on('click.bx-sliding-menu', function () {
                fClose();
            });
        });

        setTimeout(function () {

            var iWidthPrev = $(window).width();
            $(window).on('resize.bx-sliding-menu', function () {
                if ($(this).width() == iWidthPrev)
                    return;
                iWidthPrev = $(this).width();
                bx_menu_slide_close_all_opened();
            });
 
            $(document).on('click.bx-sliding-menu touchend.bx-sliding-menu', function (event) {
                if ($(event.target).parents('.bx-sliding-menu-main, .bx-popup-slide-wrapper, .bx-db-header').length || $(event.target).filter('.bx-sliding-menu-main, .bx-popup-slide-wrapper, .bx-db-header').length || e === event.target)
                    event.stopPropagation();
                else
                    bx_menu_slide_close_all_opened();
            });

        }, 10);
    }
}

/**
 * Close all opened sliding menus
 */
function bx_menu_slide_close_all_opened () {
    $('.bx-sliding-menu-main:visible, .bx-popup-slide-wrapper:visible').each(function () {
        bx_menu_slide('#' + this.id);
    });
}

/**
 * Set ACL level for specified profile
 * @param iProfileId - profile id to set acl level for
 * @param iAclLevel - acl level id to assign to a given rofile
 */
function bx_set_acl_level (iProfileId, iAclLevel, mixedLoadingElement) {

    if ('undefined' != typeof(mixedLoadingElement))
        bx_loading($(mixedLoadingElement), true);

    $.post(sUrlRoot + 'menu.php', {o:'sys_set_acl_level', 'profile_id': iProfileId, 'acl_level_id': iAclLevel}, function(data) {

        if ('undefined' != typeof(mixedLoadingElement))
            bx_loading($(mixedLoadingElement), false);

        if (data.length) {
            alert(data);
        } else if ($(mixedLoadingElement).hasClass('bx-popup-applied')) {
            $(mixedLoadingElement).dolPopupHide().remove();
        }
    });
}

function validateLoginForm(eForm) {
    return true;
}

/**
 * convert <time> tags to human readable format
 * @param sLang - use sLang localization
 * @param isAutoupdate - for internal usage only
 */
function bx_time(sLang, isAutoupdate, sRootSel) {
    if ('undefined' == typeof(sRootSel))
        sRootSel = 'body';
    var iAutoupdate = 22*60*60; // autoupdate time in realtime if less than 22 hours
    var sSel = 'time';

    if ('undefined' == typeof(sLang)) {
        sLang = glBxTimeLang;
    } else {
        glBxTimeLang = sLang;
    }

    if ('undefined' != typeof(isAutoupdate) && isAutoupdate)
        sSel += '.bx-time-autoupdate';

    $(sRootSel).find(sSel).each(function () {
        var s;
        var sTime = $(this).attr('datetime');
        var iSecondsDiff = moment(sTime).unix() - moment().unix();
        if (iSecondsDiff < 0)
            iSecondsDiff = -iSecondsDiff;

        if (iSecondsDiff < $(this).attr('data-bx-autoformat'))
            s = moment(sTime).locale(sLang).fromNow(); // 'time ago' format
        else
            s = moment(sTime).locale(sLang).format($(this).attr('data-bx-format')); // custom format

        if (iSecondsDiff < iAutoupdate)
            $(this).addClass('bx-time-autoupdate');
        else
            $(this).removeClass('bx-time-autoupdate');

        $(this).html(s);
    });

    if ($('time.bx-time-autoupdate').size()) {
        setTimeout(function () {
            bx_time(sLang, true);
        }, 20000);
    }
}

(function($) {
    $.fn.bxTime = function() {
        bx_time(undefined, undefined, this);
        return this;
    }; 
} (jQuery));


/**
 * Perform connections AJAX request. 
 * In case of error - it shows js alert with error message.
 * In case of success - the page is reloaded.
 * 
 * @param sObj - connections object
 * @param sAction - 'add', 'remove' or 'reject'
 * @param iContentId - content id, initiator is always current logged in user
 * @param bConfirm - show confirmation dialog
 */
function bx_conn_action(e, sObj, sAction, iContentId, bConfirm, fOnComplete) {
    if ('undefined' != typeof(bConfirm) && bConfirm && !confirm(_t('_are you sure?')))
        return;
                
    var aParams = {
        obj: sObj,
        act: sAction,
        id: iContentId
    };
    var fCallback = function (data) {
        bx_loading_btn(e, 0);
        if ('object' != typeof(data))
            return;
        if (data.err) {
            alert(data.msg);
        } else {
            if ('function' == typeof(fOnComplete))
                fOnComplete(data, e);
            else if (!loadDynamicBlockAuto(e))
                location.reload();
            else
                $('#bx-popup-ajax-wrapper-bx_persons_view_actions_more').remove();
        }
    };

    bx_loading_btn(e, 1);

    $.ajax({
        dataType: 'json',
        url: sUrlRoot + 'conn.php',
        data: aParams,
        type: 'POST',
        success: fCallback
    });
}

function bx_append_url_params (sUrl, mixedParams) {
    var sParams = sUrl.indexOf('?') == -1 ? '?' : '&';

    if(mixedParams instanceof Array) {
    	for(var i in mixedParams)
            sParams += i + '=' + mixedParams[i] + '&';
        sParams = sParams.substr(0, sParams.length-1);
    }
    else if(mixedParams instanceof Object) {
    	$.each(mixedParams, function(sKey, sValue) {
    		sParams += sKey + '=' + sValue + '&';
    	});
        sParams = sParams.substr(0, sParams.length-1);
    }
    else
        sParams += mixedParams;

    return sUrl + sParams;
}

function bx_search_on_type (e, n, sFormSel, sResultsContSel, sLoadingContSel, bSortResults) {
    if ('undefined' != typeof(e) && 13 == e.keyCode) {
        $(sFormSel).find('input[name=live_search]').val(0);
        $(sFormSel).submit();
        return false;
    }

    if ('undefined' != typeof(glBxSearchTimeoutHandler) && glBxSearchTimeoutHandler)
        clearTimeout(glBxSearchTimeoutHandler);

    glBxSearchTimeoutHandler = setTimeout(function () {
        bx_search (n, sFormSel, sResultsContSel, sLoadingContSel, bSortResults);
    }, 500);

    return true;
}

function bx_search (n, sFormSel, sResultsContSel, sLoadingContSel, bSortResults) {
    if ('undefined' == typeof(sLoadingContSel))
        sLoadingContSel = sResultsContSel;
    if ('undefined' == typeof(bSortResults))
        bSortResults = false;

    var sQuery = $('input', sFormSel).serialize();

    bx_loading($(sLoadingContSel), true);
    $.post(sUrlRoot + 'searchKeywordContent.php', sQuery, function(data) {
        bx_loading($(sLoadingContSel), false);
        if (bSortResults) {
            var aSortedUnits = $(data).find(".bx-def-unit-live-search").toArray().sort(function (a, b) {
                return b.getAttribute('data-ts') - a.getAttribute('data-ts');
            });
            data = '';
            $.each(aSortedUnits.slice(0, n), function (i, e) {
                data += e.outerHTML;
            });
        } 
        $(sResultsContSel).html(data).bxTime();
    });

    return false;
}

function on_filter_apply(e, sInputId, sFilterName)
{
	var oRegExp = new RegExp("[&]{0,1}" + sFilterName + "=.*");
    var s = ('' + document.location).replace(oRegExp, ''); // remove filter
    s = s.replace(/page=\d+/, 'page=1'); // goto 1st page
    if (e.checked && $('#' + sInputId).val().length > 2)
        s += (-1 == s.indexOf('?') ? '?' : '&') + sFilterName + '=' + $('#' + sInputId).val(); // append filter
    document.location = s;
}

function on_filter_key_up (e, sCheckboxId)
{
    if (13 == e.keyCode) {
        $('#' + sCheckboxId).click();
        return false;
    }
    else {
        $('#' + sCheckboxId).removeAttr('checked');
        return true;
    }
}

function bx_activate_anim_icons(sColor)
{
    if ('undefined' == typeof(sColor))
        sColor = glBxAnimIconColor;
    else
       glBxAnimIconColor = sColor;

    $('.sys-icon-a').not('.marka').each(function () {
        var e = $(this);
        var m = new Marka(e.get(0)), r = e.attr('data-rotate'), c = e.attr('data-color');
        m.set(e.attr('data-icon')).rotate(r ? r : 'down').color(c ? c : sColor);
    });
}

function bx_get_style(sUrl)
{
	$("head").append($("<link rel='stylesheet' href='" + sUrl + "' type='text/css' />"));
}

/** @} */
