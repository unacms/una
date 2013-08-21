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
        top:  iTopOff,
        zIndex:100
    });

    if (undefined != method && (method == 'post' || method == 'POST')) {

        $.post(url, function(data) {

            $block.html(data);

	        $block
			    .css('position', blockPos) // return previous value
		        .addWebForms();
        
            if (typeof callback == 'function')
                callback.apply($block);
        });

    } else {

        $block.load(url + '&_r=' + Math.random(), function() {
	        $(this).css('position', blockPos); // return previous value

            if ($.isFunction($.addWebForms))
		        $(this).addWebForms();

            if (typeof callback == 'function')
                callback.apply(this);
        });

    }
}


function loadDynamicBlock( iBlockID, sUrl ) {
    if( $ == undefined )
        return false;

    getHtmlData($('#page_block_' + iBlockID), (sUrl + '&dynamic=tab&pageBlock=' + iBlockID));

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


function showPopupLoginForm() {
    var oPopupOptions = {};

    if ($('#login_div').length)
        $('#login_div').dolPopup(oPopupOptions);
    else {
        $('<div id="login_div" style="visibility: none;"></div>').prependTo('body').load(
            sUrlRoot + 'member.php',
            {
                action: 'show_login_form',
                relocate: String(window.location)
            },
            function() {
                $(this).dolPopup(oPopupOptions);
            }
        );
    }
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
            block.html('<div class="bx-loading-ajax" style="position:static;"></div>');
        else
            block.append('<div class="bx-loading-ajax"></div>');
        bx_loading_animate(block.find('.bx-loading-ajax'));
    } 
}

function bx_loading (elem, b) {

    if (typeof elem == 'string')
        elem = '#' + elem;

    var block = $(elem);

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
 * js version on BxBaseFunction::centerContent function
 * sSel - jQuery selector of content to be centered
 * sBlockSel - jquery selector of blocks
 */
function bx_center_content (sSel, sBlockStyle) {
    var sId = 'id' + (new Date()).getTime();
    $(sSel).wrap('<div id="'+sId+'"></div>');
    //$(document).ready(function() {
            var eCenter = $('#' + sId);
            var iAll = $('#' + sId + ' ' + sBlockStyle).size();
            var iWidthUnit = $('#' + sId + ' ' + sBlockStyle + ':first').outerWidth({"margin":true});            
            var iWidthContainer = eCenter.innerWidth();           
            var iPerRow = parseInt(iWidthContainer/iWidthUnit);
            var iLeft = (iWidthContainer - (iAll > iPerRow ? iPerRow * iWidthUnit : iAll * iWidthUnit)) / 2;
            eCenter.css("padding-left", iLeft);
    //});
}

/**
 * Show pointer popup with menu.
 * @param e - element to show popup at
 * @param o - menu object name
 */
function bx_menu_popup (o, e) {
    $(e).dolPopupAjax({
        url: 'menu.php?o=' + o
    });
}

function validateLoginForm(eForm) {
    if (!eForm)
        return false;

    $(eForm).ajaxSubmit({
        success: function(sResponce) {
            if(sResponce == 'OK')
                eForm.submit();
            else
                alert(sResponce);
        }
    });

    return false;
}

