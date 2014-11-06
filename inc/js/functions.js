/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
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


function bx_loading_btn (e, b) {
    e = $(e);

    if (e.children('div').size())
        e = e.children('div').first();

    if (!b) {
        e.find('.bx-loading-ajax-btn').remove();
    } else if (!e.find('.bx-loading-ajax-btn').length) {
        e.append('<b class="bx-loading-ajax-btn"></b>');
        new Spinner(aSpinnerSmallOpts).spin(e.find('.bx-loading-ajax-btn').get(0));
    }
}

function bx_loading_animate (e) {
    e = $(e);
    if (!e.length)
        return false;
    if (e.find('.bx-sys-spinner').length)
        return false;
    return new Spinner(aSpinnerOpts).spin(e.get(0));
}

function bx_loading_content (elem, b, isReplace) {
    var block = $(elem);
    if (!b) {
        block.find(".bx-loading-ajax").remove();
    } else if (!block.find('.bx-loading-ajax').length) {
        if ('undefined' != typeof(isReplace) && isReplace)
            block.html('<div class="bx-loading-ajax bx-def-z-index-front" style="position:static;"></div>');
        else
            block.append('<div class="bx-loading-ajax bx-def-z-index-front"></div>');
        bx_loading_animate(block.find('.bx-loading-ajax'));
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
function bx_center_content (sSel, sBlockStyle) {
    var sId = 'id' + (new Date()).getTime();
    $(sSel).wrap('<div id="'+sId+'"></div>');

    var eCenter = $('#' + sId);
    var iAll = $('#' + sId + ' ' + sBlockStyle).size();
    var iWidthUnit = $('#' + sId + ' ' + sBlockStyle + ':first').outerWidth(true);
    var iWidthContainer = eCenter.innerWidth();           
    var iPerRow = parseInt(iWidthContainer/iWidthUnit);
    var iLeft = (iWidthContainer - (iAll > iPerRow ? iPerRow * iWidthUnit : iAll * iWidthUnit)) / 2;
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
    var o = $.extend({}, $.fn.dolPopupDefaultOptions, options, {id: o, url: bx_append_url_params('menu.php', $.extend({o:o}, vars))});
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
            left: 0,
            width: eBlock.width()
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

    var fCloseAllOpened = function () {
        $('.bx-sliding-menu-main:visible, .bx-popup-slide-wrapper:visible').each(function () {
            bx_menu_slide('#' + this.id);
        });
    }

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
        $(document).off('click.bx-sliding-menu');
        $(window).off('resize.bx-sliding-menu');
    } 
    else {
        fCloseAllOpened();
        fOpen();
        eSlider.find('a').each(function () {
            $(this).off('click.bx-sliding-menu');
            $(this).on('click.bx-sliding-menu', function () {
                fClose();
            });
        });

        setTimeout(function () {
           
            $(window).on('resize.bx-sliding-menu', function () {
                fCloseAllOpened();
            });
 
            $(document).on('click.bx-sliding-menu', function (event) {
                if ($(event.target).parents('.bx-sliding-menu-main, .bx-popup-slide-wrapper, .bx-db-header').length || $(event.target).filter('.bx-sliding-menu-main, .bx-popup-slide-wrapper, .bx-db-header').length || e.isSameNode(event.target))
                    event.stopPropagation();
                else
                    fCloseAllOpened();
            });

        }, 10);
    }
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
            s = moment(sTime).lang(sLang).fromNow(); // 'time ago' format
        else
            s = moment(sTime).lang(sLang).format($(this).attr('data-bx-format')); // custom format

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
function bx_conn_action(e, sObj, sAction, iContentId, bConfirm) {
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
            if (!loadDynamicBlockAuto(e))
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
