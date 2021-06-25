/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

function BxTimelineView(oOptions) {
    BxTimelineMain.call(this, oOptions);

    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oTimelineView' : oOptions.sObjName;
    this._sName = oOptions.sName == undefined ? '' : oOptions.sName;
    this._sView = oOptions.sView == undefined ? 'timeline' : oOptions.sView;
    this._sType = oOptions.sType == undefined ? 'public' : oOptions.sType;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sReferrer = oOptions.sReferrer == undefined ? '' : oOptions.sReferrer;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sVideosAutoplay = oOptions.sVideosAutoplay == undefined ? 'off' : oOptions.sVideosAutoplay;
    this._bEventsToLoad = oOptions.bEventsToLoad == undefined ? false : oOptions.bEventsToLoad;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

    this._bInfScroll = oOptions.bInfScroll == undefined ? false : oOptions.bInfScroll;
    this._iInfScrollAutoPreloads = oOptions.iInfScrollAutoPreloads == undefined ? 10 : oOptions.iInfScrollAutoPreloads;
    this._sInfScrollAfter = 'item';
    this._iInfScrollAfterItem = 2; //--- Preload more info when scroll reached N item from the end of Timeline block.
    this._fInfScrollAfterPercent = 0.25; //--- Preload more info when specified portion of Timeline block's content was already scrolled.
    this._bInfScrollBusy = false;
    this._iInfScrollPreloads = 1; //--- First portion is loaded with page loading or 'Load More' button click.

    this._fOutsideOffset = 0.8;
    this._oSaved = {};

    this._fVapOffsetStart = 0.8;
    this._fVapOffsetStop = 0.2;

    this._bLiveUpdatePaused = false;

    if(typeof window.glBxTimelineVapPlayers === 'undefined')
        window.glBxTimelineVapPlayers = [];

    //--- Get currently active 'view'.
    this.initView();

    //--- Initialize components on currently active 'view'.
    this.init();
}

BxTimelineView.prototype = Object.create(BxTimelineMain.prototype);
BxTimelineView.prototype.constructor = BxTimelineView;

BxTimelineView.prototype.init = function(bForceInit)
{
    var $this = this;

    if(!this.oView || bForceInit)
        this.initView();

    if(this.bViewTimeline) {
        //-- Check content to show 'See More'
        this.initSeeMore(this.oView, true);

        //--- Hide timeline Events which are outside the viewport
        this.hideEvents(this.oView.find('.' + this.sClassItem), this._fOutsideOffset);

        //--- Init Video Autoplay
        if(this._sVideosAutoplay != 'off') {
            this.initVideosAutoplay(this.oView);

            this.oView.on('hide')

            $(window).on('scroll', function() {
                if(!$this.oView.is(':visible'))
                    return;

                if(!window.requestAnimationFrame) 
                    setTimeout(function() {
                        $this.autoplayVideos($this.oView, $this._fVapOffsetStart, $this._fVapOffsetStop);
                    }, 100);
                else
                    window.requestAnimationFrame(function() {
                        $this.autoplayVideos($this.oView, $this._fVapOffsetStart, $this._fVapOffsetStop);
                    });
            });
        }

        //--- Blink (highlight) necessary items
        this.blink(this.oView);

        //--- Load 'Jump To'
        this.initJumpTo(this.oView);

        //--- Init 'Infinite Scroll'
        this.initInfiniteScroll(this.oView);
    }

    if(this.bViewOutline) {
        this.initMasonry();

        this.oView.find('.' + this.sClassItem).resize(function() {
            $this.reloadMasonry();
        });
        this.oView.find('img.' + this.sClassItemImage).load(function() {
            $this.reloadMasonry();
        });

        //--- Init Video Layout
        if(this._sVideosAutoplay != 'off')
            this.initVideos(this.oView);

        //--- Blink (highlight) necessary items
        this.blink(this.oView);

        //--- Load 'Jump To'
        this.initJumpTo(this.oView);

        //--- Init 'Infinite Scroll'
        this.initInfiniteScroll(this.oView);
    }

    if(this.bViewItem) {
        //-- Check content to show 'See More'
        this.initSeeMore(this.oView, false);

        //--- Init Video Layout
        if(this._sVideosAutoplay != 'off')
            this.initVideos(this.oView);
    }

    //--- Init Flickity
    this.initFlickity(this.oView);
};

BxTimelineView.prototype.initView = function() 
{   
    BxTimelineMain.prototype.initView.call(this);

    this.oView = $(this._getHtmlId('main', this._oRequestParams));
    if(!this.oView.length) 
        return;

    if(this.oView.hasClass(this.sClassView + '-timeline'))
        this.bViewTimeline = true;
    else if(this.oView.hasClass(this.sClassView + '-outline'))
        this.bViewOutline = true;
    else if(this.oView.hasClass(this.sClassView + '-item'))
        this.bViewItem = true;
};

BxTimelineView.prototype.initSeeMore = function(oParent, bInItems)
{
    var $this = this;

    var oSubParent = oParent;
    if(bInItems)
        oSubParent = oParent.find('.' + this.sClassItem);

    oSubParent.find('.bx-tl-item-text .bx-tl-content').checkOverflowHeight(this.sSP + '-overflow', function(oElement) {
        $this.onFindOverflow(oElement);
    });
};

BxTimelineView.prototype.initJumpTo = function(oParent)
{
    var oJumpTo = $(oParent).find('.' + this.sClassJumpTo);
    if(!oJumpTo || oJumpTo.length == 0 || oJumpTo.html() != '')
        return;

    bx_loading_btn(oJumpTo, true);

    jQuery.post (
        this._sActionsUrl + 'get_jump_to/',
        this._getDefaultData(oParent),
        function(oData) {
            oData.holder = oJumpTo;

            processJsonData(oData);
        },
        'json'
    );
};

BxTimelineView.prototype.onGetJumpTo = function(oData)
{
    if(!oData.holder || oData.content == undefined)
        return;

    $(oData.holder).html(oData.content);
};

BxTimelineView.prototype.initInfiniteScroll = function(oParent)
{
    var $this = this;

    if(!this._bInfScroll || !this._bEventsToLoad)
        return;

    $(window).bind('scroll', function(oEvent) {
        if(!$this.oView.is(':visible'))
            return;

        if($this.oView.attr('id') != $this._getHtmlId('main', {name: $this._sName, view: $this._sView, type: $this._sType}, {hash: false}))
            return;

        if(!$this._bEventsToLoad || $this._bInfScrollBusy || $this._iInfScrollPreloads >= $this._iInfScrollAutoPreloads)
            return;

        var iScrollTop = parseInt($(window).scrollTop());
        var iWindowHeight = $(window).height();

        //--- Auto-scroll by reaching the N item from the end of parent block.
        if($this._sInfScrollAfter == 'item') {
            var oItems = oParent.find('.' + $this.sClassItem);
            if((iScrollTop + iWindowHeight) <= ($(oItems.get(oItems.length - $this._iInfScrollAfterItem)).offset().top))
                return;
        }

        //--- Auto-scroll by reaching the percent of parent block's height.
        if($this._sInfScrollAfter == 'percent') {
            var iParentTop = parseInt(oParent.offset().top);
            var iParentHeight = parseInt(oParent.height());
            if((iScrollTop + iWindowHeight) <= (iParentTop + iParentHeight * $this._fInfScrollAfterPercent))
                return;
        }

        $this._bInfScrollBusy = true;
        $this._getPage(undefined, $this._oRequestParams.start + $this._oRequestParams.per_page, $this._oRequestParams.per_page, function(oData) {
            $this._bEventsToLoad = oData.events_to_load;
            $this._iInfScrollPreloads += 1;
            $this._bInfScrollBusy = false;
        });
    });
};

BxTimelineView.prototype.initVideosAutoplay = function(oParent)
{
    var $this = this;

    if(this._sVideosAutoplay == 'off')
        return;

    this.initVideos(oParent);

    var sPrefix = oParent.hasClass(this.sClassView) ? oParent.attr('id') : oParent.parents('.' + this.sClassView + ':first').attr('id');

    oParent.find('iframe').each(function() {
        var sPlayer = sPrefix + '_' + $(this).attr('id');
        if(window.glBxTimelineVapPlayers[sPlayer])
            return;

        var oPlayer = new playerjs.Player(this);
        if($this._sVideosAutoplay == 'on_mute')
            oPlayer.mute();

        var fFixHeight = function () {
            $('#' + sPlayer).height(($('#' + sPlayer).contents().find('video').height()) + 'px');
        };
        oPlayer.on('ready', fFixHeight);
        oPlayer.on('play', fFixHeight);

        window.glBxTimelineVapPlayers[sPlayer] = oPlayer;
    });
};

BxTimelineView.prototype.hideEvents = function(oEvents, fOffset)
{
    var $this = this;

    oEvents.each(function(iIndex, oEvent) {
        $this.hideEvent($(oEvent), fOffset, iIndex, false);
    });

    //--- on scolling, show/animate timeline Events when enter the viewport
    $(window).on('scroll', function() {
        if(!window.requestAnimationFrame) 
            setTimeout(function() {
                $this.showEvents(oEvents, $this._fOutsideOffset);
            }, 100);
        else
            window.requestAnimationFrame(function() {
                $this.showEvents(oEvents, $this._fOutsideOffset);
            });
    });
};

BxTimelineView.prototype.hideEvent = function(oEvent, fOffset, iIndex, bInitAutoShow)
{
    var $this = this;

    iIndex >=3 && oEvent.offset().top > ($(window).scrollTop() + $(window).height() * fOffset) && oEvent.find('.bx-tl-item-type, .bx-tl-item-cnt').addClass('is-hidden');

    if(bInitAutoShow)
        $(window).on('scroll', function() {
            if(!window.requestAnimationFrame) 
                setTimeout(function() {
                    $this.showEvent(oEvent, $this._fOutsideOffset);
                }, 100);
            else
                window.requestAnimationFrame(function() {
                    $this.showEvent(oEvent, $this._fOutsideOffset);
                });
        });
};

BxTimelineView.prototype.showEvents = function(oEvents, fOffset)
{
    var $this = this;
    oEvents.each(function() {
        $this.showEvent($(this), fOffset);
    });
};

BxTimelineView.prototype.showEvent = function(oEvent, fOffset)
{
    oEvent.offset().top <= ($(window).scrollTop() + $(window).height() * fOffset) && oEvent.find('.bx-tl-item-type').hasClass('is-hidden') && oEvent.find('.bx-tl-item-type, .bx-tl-item-cnt').removeClass('is-hidden').addClass('bounce-in');
};

BxTimelineView.prototype.autoplayVideos = function(oView, fOffsetStart, fOffsetStop)
{
    var $this = this;

    var oItems = oView.find('.' + this.sClassItem);
    var sPrefix = oView.attr('id') + '_';

    oItems.each(function() {
        $(this).find('iframe').each(function() {
            var oFrame = $(this);
            var oPlayer = window.glBxTimelineVapPlayers[sPrefix + oFrame.attr('id')];
            if(!oPlayer)
                return;

            var iFrameTop = oFrame.offset().top;
            var iFrameBottom = iFrameTop + oFrame.height();
            var iWindowTop = $(window).scrollTop();
            var iWindowHeight = $(window).height();
            if(iFrameTop <= iWindowTop + iWindowHeight * fOffsetStart && iFrameBottom >= iWindowTop + iWindowHeight * fOffsetStop)
                oPlayer.play();
            else
                oPlayer.pause();
        });
    });
};

BxTimelineView.prototype.playVideos = function(oView)
{
    var $this = this;

    var oItems = oView.find('.' + this.sClassItem);
    var sPrefix = oView.attr('id') + '_';

    oItems.each(function() {
        $(this).find('iframe').each(function() {
            var oFrame = $(this);
            var oPlayer = window.glBxTimelineVapPlayers[sPrefix + oFrame.attr('id')];
            if(!oPlayer)
                return;

            oPlayer.play();
        });
    });
};

BxTimelineView.prototype.pauseVideos = function(oView)
{
    var $this = this;

    var oItems = oView.find('.' + this.sClassItem);
    var sPrefix = oView.attr('id') + '_';

    oItems.each(function() {
        $(this).find('iframe').each(function() {
            var oFrame = $(this);
            var oPlayer = window.glBxTimelineVapPlayers[sPrefix + oFrame.attr('id')];
            if(!oPlayer)
                return;

            oPlayer.pause();
        });
    });
};

BxTimelineView.prototype.reload = function(oSource)
{
    var $this = this;

    this.loadingInBlock(oSource, true);

    this._oRequestParams.start = 0;
    this._getPosts(oSource, function(oData) {
        processJsonData(oData);

        $this.init(true);
    });
};

BxTimelineView.prototype.changeView = function(oLink, sType, oRequestParams)
{
    var $this = this;
    var oViews = $(this._getHtmlId('views_content', this._oRequestParams, {with_type: false})); 

    var oViewBefore = $(this._getHtmlId('main', this._oRequestParams));
    if(!oViewBefore.length)
        oViewBefore = oViews.children(':visible');

    if(this._sVideosAutoplay != 'off')
        this.pauseVideos(oViewBefore);

    this._oRequestParams.start = 0;
    this._oRequestParams.type = sType;

    var oTab = $(oLink);
    var sTabActive = 'bx-menu-tab-active';
    oTab.parents('.bx-db-menu:first').find('li.' + sTabActive).removeClass(sTabActive);
    oTab.parents('li:first').addClass(sTabActive);

    var sView = this._getHtmlId('main', this._oRequestParams);
    if(oViews.find(sView).length !== 0) {
        oViewBefore.hide();
        oViews.find(sView).show();

        this.initView();

        return;
    }

    var oData = this._getDefaultData(oLink);
    if(oRequestParams != undefined)
        oData = jQuery.extend({}, oData, oRequestParams);

    this.loadingIn(oLink, true);

    jQuery.get (
        this._sActionsUrl + 'get_view',
        oData,
        function(oResponse) {
            if(oLink)
                $this.loadingIn(oLink, false);

            if(!oResponse.content)
                return;

            oViewBefore.hide();
            oViews.append(oResponse.content).find(sView).bxProcessHtml();
        },
        'json'
    );
};

BxTimelineView.prototype.changePage = function(oLink, iStart, iPerPage, onLoad)
{
    if(this._bInfScroll)
        this._iInfScrollPreloads = 1;

    this._getPage(oLink, iStart, iPerPage, onLoad);
};

BxTimelineView.prototype.changeFilter = function(oLink)
{
    var sId = $(oLink).attr('id');
    sId = sId.substr(sId.lastIndexOf('-') + 1, sId.length);

    this.loadingInBlock(oLink, true);

    this._oRequestParams.start = 0;
    this._oRequestParams.filter = sId;
    this._getPosts(oLink);
};

BxTimelineView.prototype.changeTimeline = function(oLink, sDate)
{
    var $this = this;

    oLink = $(oLink);
    var bLink = oLink.length > 0;
    var bLoadingInButton = bLink && oLink.hasClass('bx-btn');

    if(bLink) {
        if(bLoadingInButton)
            this.loadingInButton(oLink, true);
        else
            this.loadingInBlock(oLink, true);
    }

    this._oRequestParams.start = 0;
    this._oRequestParams.timeline = sDate;
    this._getPosts(oLink, function(oData) {
        if(bLink) {
            if(bLoadingInButton)
                $this.loadingInButton(oLink, false);
            else
                $this.loadingInBlock(oLink, false);
        }

        window.scrollTo(0, $this.oView.offset().top - 150);

        processJsonData(oData);
    });
};

BxTimelineView.prototype.showCalendar = function(oLink)
{
    var $this = this;
    var oInput = $(oLink).siblings('.' + this.sSP + '-jump-to-calendar');
    if(!oInput.length)
        return;

    var sClassProcessed = this.sSP + '-datepicker-processed';
    if(!oInput.hasClass(sClassProcessed)) {
        oInput.datepicker({
            changeYear: true,
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '1900:2100',
            onSelect: function(sDate, oPicker){
		$this.changeTimeline(oLink, sDate);
            }
        });
        oInput.addClass(sClassProcessed);
    }

    oInput.datepicker('show');
};

BxTimelineView.prototype.showMore = function(oLink)
{
    var sClassOverflow = this.sSP + '-overflow';

    $(oLink).parents('.' + this.sClassItem + ':first').find('.' + sClassOverflow).css('max-height', 'none').removeClass(sClassOverflow);
    $(oLink).parents('.' + this.sSP + '-content-show-more:first').remove();

    if(this.bViewOutline)
        this.reloadMasonry();
};

BxTimelineView.prototype.showItem = function(oLink, iId, sMode, oParams)
{
    var oData = $.extend({}, this._getDefaultData(), {id: iId, mode: sMode}, (oParams != undefined ? oParams : {}));

    $(".bx-popup-full-screen.bx-popup-applied:visible").dolPopupHide();

    $(window).dolPopupAjax({
        id: {
            value: this._getHtmlId('item_popup', this._oRequestParams, {whole: false, hash: false}) + iId, 
            force: true
        },
        url: bx_append_url_params(this._sActionsUrl + 'get_item_brief', oData),
        closeOnOuterClick: false,
        removeOnClose: true,
        fullScreen: true
    });

    return false;
};

BxTimelineView.prototype.commentItem = function(oLink, sSystem, iId)
{
    var $this = this;
    var oData = this._getDefaultData(oLink);
    oData['system'] = sSystem;
    oData['id'] = iId;

    var oComments = $(oLink).parents('.' + this.sClassItem + ':first').find('.' + this.sClassItemComments);
    if(oComments.children().length > 0) {
        oComments.bx_anim('toggle', this._sAnimationEffect, this._iAnimationSpeed);
        $(oLink).parents('.cmt-counter').toggleClass('cmt-counter-opened');
    	return;
    }

    if(oLink)
    	this.loadingInItem(oLink, true);

    jQuery.get (
        this._sActionsUrl + 'get_comments',
        oData,
        function(oData) {
            if(oLink)
                $this.loadingInItem(oLink, false);

            if(!oData.content)
                return;

            oComments.html($(oData.content).hide()).children(':hidden').bxProcessHtml().bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
            $(oLink).parents('.cmt-counter').toggleClass('cmt-counter-opened');
        },
        'json'
    );
};

BxTimelineView.prototype.pinPost = function(oLink, iId, iWay)
{
    this._markPost(oLink, iId, iWay, 'pin');
};

BxTimelineView.prototype.onPinPost = function(oData)
{
    this._onMarkPost(oData, 'pin');
};

BxTimelineView.prototype.stickPost = function(oLink, iId, iWay)
{
    this._markPost(oLink, iId, iWay, 'stick');
};

BxTimelineView.prototype.onStickPost = function(oData)
{
    this._onMarkPost(oData, 'stick');
};

BxTimelineView.prototype.promotePost = function(oLink, iId, iWay)
{
    var $this = this;
    var oData = this._getDefaultData();
    oData['id'] = iId;

    $(oLink).parents('.bx-popup-applied:first:visible').dolPopupHide({
        onHide: function(oPopup) {
            $(oPopup).remove();
        }
    });

    var oLoadingContainer = $(this._getHtmlId('item', this._oRequestParams, {whole: false}) + iId);

    this.loadingInItem(oLoadingContainer, true);

    $.post(
        this._sActionsUrl + 'promote/',
        oData,
        function(oData) {
            $this.loadingInItem(oLoadingContainer, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxTimelineView.prototype.muteAuthor = function(oLink, iId)
{
    var $this = this;
    var oData = this._getDefaultData();
    oData['id'] = iId;

    $(oLink).parents('.bx-popup-applied:first:visible').dolPopupHide({
        onHide: function(oPopup) {
            $(oPopup).remove();
        }
    });

    var oLoadingContainer = $(this._getHtmlId('item', this._oRequestParams, {whole: false}) + iId);

    this.loadingInItem(oLoadingContainer, true);

    $.post(
        this._sActionsUrl + 'mute/',
        oData,
        function(oData) {
            $this.loadingInItem(oLoadingContainer, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxTimelineView.prototype.initFormEdit = function(sFormId)
{
    var $this = this;
    var oForm = $('#' + sFormId);

    autosize(oForm.find('textarea'));
    oForm.ajaxForm({
        dataType: "json",
        beforeSubmit: function (formData, jqForm, options) {
            window[$this._sObjName].beforeFormEditSubmit(oForm);
        },
        success: function (oData) {
            window[$this._sObjName].afterFormEditSubmit(oForm, oData);
        }
    });
};

BxTimelineView.prototype.beforeFormEditSubmit = function(oForm)
{
    this.loadingInButton($(oForm).children().find(':submit'), true);
};

BxTimelineView.prototype.afterFormEditSubmit = function (oForm, oData)
{
    var $this = this;
    var fContinue = function() {
        if(oData && oData.id != undefined) {
            var iId = parseInt(oData.id);
            if(iId <= 0) 
                return;

            $this._getPost($this.oView, iId, $this._oRequestParams);
            return;
        }

        if(oData && oData.form != undefined && oData.form_id != undefined) {
            $('#' + oData.form_id).replaceWith(oData.form);
            $this.initFormEdit(oData.form_id);

            return;
        }
    };

    this.loadingInButton($(oForm).children().find(':submit'), false);

    if(oData && oData.message != undefined)
        bx_alert(oData.message, fContinue);
    else
        fContinue();
};

BxTimelineView.prototype.editPost = function(oLink, iId)
{
    var $this = this;
    var oData = this._getDefaultData(oLink);
    oData['id'] = iId;

    $(oLink).parents('.bx-popup-applied:first:visible').dolPopupHide();

    var oItem = this.oView.find(this._getHtmlId('item', this._oRequestParams, {whole: false}) + iId);

    var oContent = oItem.find('.' + this.sClassItemContent);
    if(oContent.find('form').length) {
        $(oContent).bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
            $(this).html($this._oSaved[iId]).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
        });
        return;
    }
    else
        this._oSaved[iId] = oContent.html();

    this.loadingInItem(oItem, true);

    jQuery.post (
        this._sActionsUrl + 'get_edit_form/' + iId + '/',
        oData,
        function (oData) {
            processJsonData(oData);
        },
        'json'
    );
};

BxTimelineView.prototype.onEditPost = function(oData)
{
    var $this = this;

    if(!oData || !oData.id)
        return;

    var oItem = $(this._getHtmlId('item', this._oRequestParams, {whole: false}) + oData.id);

    this.loadingInItem(oItem, false);

    if(oData && oData.form != undefined && oData.form_id != undefined) {
        oItem.find('.' + this.sClassItemContent).bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
            $(this).html(oData.form).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                $this.initFormEdit(oData.form_id);
            });
        });
    }
};

BxTimelineView.prototype.editPostCancel = function(oButton, iId)
{
    this.editPost(oButton, iId);
};

BxTimelineView.prototype.deletePost = function(oLink, iId)
{
    var $this = this;

    $(oLink).parents('.bx-popup-applied:first:visible').dolPopupHide();

    bx_confirm('', function() {
        var oData = $this._getDefaultData();
        oData['id'] = iId;

        $this.loadingInItem($($this._getHtmlId('item', $this._oRequestParams, {whole: false}) + iId), true);

        $.post(
            $this._sActionsUrl + 'delete/',
            oData,
            function(oData) {
                processJsonData(oData);
            },
            'json'
        );
    });
};

BxTimelineView.prototype.onDeletePost = function(oData)
{
    var $this = this;
    var oItem = $(this._getHtmlId('item', this._oRequestParams, {whole: false}) + oData.id);

    //--- Delete from 'Timeline' (if available)
    if(this.bViewTimeline) {
        oItem.bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
            $(this).remove();

            if($this.oView.find('.' + $this.sClassItem).length == 0) {
                $this.oView.find('.' + $this.sClassDividerToday).hide();
                $this.oView.find('.' + $this.sSP + '-load-more').hide();
                $this.oView.find('.' + $this.sSP + '-empty').show();
            }
        });

        return;
    }

    //--- Delete from 'Outline' (if available)
    if(this.bViewOutline) {
        oItem.bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
            $(this).remove();

            if($this.oView.find('.' + $this.sClassItem).length == 0) {
                $this.destroyMasonry();

                $this.oView.find('.' + $this.sSP + '-load-more').hide();
                $this.oView.find('.' + $this.sSP + '-empty').show();
            } 
            else
                $this.reloadMasonry();
        });

        return;
    }

    //--- Delete from 'View Item' page.
    if(this._sReferrer.length != 0)
        document.location = this._sReferrer;
};

BxTimelineView.prototype.onConnect = function(eElement, oData)
{
    $(eElement).remove();
};

/*----------------------------*/
/*--- Live Updates methods ---*/
/*----------------------------*/
BxTimelineView.prototype.goTo = function(oLink, sGoToId, sBlinkIds, onLoad)
{
    var $this = this;

    this.loadingInPopup(oLink, true);

    this._oRequestParams.start = 0;
    this._oRequestParams.blink = sBlinkIds;
    this._getPosts(this.oView, function(oData) {
        $this.loadingInPopup(oLink, false);

        $(oLink).parents('.bx-popup-applied:first:visible').dolPopupHide();

        oData.go_to = sGoToId;
        processJsonData(oData);
    });
};

BxTimelineView.prototype.goToBtn = function(oLink, sGoToId, sBlinkIds, onLoad)
{
    var $this = this;

    this.loadingInButton(oLink, true);

    this._oRequestParams.start = 0;
    this._oRequestParams.blink = sBlinkIds;
    this._getPosts(this.oView, function(oData) {
        oData.go_to = sGoToId;
        processJsonData(oData);

        $this.loadingInButton(oLink, false);
        $(oLink).parents('.' + $this.sSP + '-live-update-button:first').remove();

        $this.resumeLiveUpdates();
    });
};

/*
 * Show only one live update notification for all new events.
 * 
 * Note. oData.count_old and oData.count_new are also available and can be checked or used in notification popup.  
 */
BxTimelineView.prototype.showLiveUpdate = function(oData)
{
    if(!oData.code)
        return;

    var oButton = $(oData.code);
    var sId = oButton.attr('id');
    $('#' + sId).remove();

    oButton.prependTo(this.oView);
};

/*
 * Show separate live update notification for each new Event.
 * 
 * Note. This way to display live update notifications isn't used for now. 
 * See BxTimelineView::showLiveUpdate method instead.
 * 
 * Note. oData.count_old and oData.count_new are also available and can be checked or used in notification popup.  
 */
BxTimelineView.prototype.showLiveUpdates = function(oData)
{
    if(!oData.code)
        return;

    var $this = this;

    var oItems = $(oData.code);
    var sId = oItems.attr('id');
    $('#' + sId).remove();

    oItems.prependTo('body').dolPopup({
        position: 'fixed',
        left: '1rem',
        top: 'auto',
        bottom: '1rem',
        fog: false,
        onBeforeShow: function() {
        },
        onBeforeHide: function() {
        },
        onShow: function() {
            setTimeout(function() {
                $('.bx-popup-chain.bx-popup-applied:visible:first').dolPopupHide();
            }, 5000);
        },
        onHide: function() {
            $this.resumeLiveUpdates();
        }
    });
};

BxTimelineView.prototype.previousLiveUpdate = function(oLink)
{
    var fPrevious = function() {
        var sClass = 'bx-popup-chain-item';
        $(oLink).parents('.' + sClass + ':first').hide().prev('.' + sClass).show();
    };

    if(!this.pauseLiveUpdates(fPrevious));
        fPrevious();
};

BxTimelineView.prototype.hideLiveUpdate = function(oLink)
{
    $(oLink).parents('.bx-popup-applied:visible:first').dolPopupHide();
};

BxTimelineView.prototype.resumeLiveUpdates = function(onLoad)
{
    if(!this._bLiveUpdatePaused)
        return false;

    var $this = this;
    this.changeLiveUpdates('resume_live_update', function() {
        $this._bLiveUpdatePaused = false;

        if(typeof onLoad == 'function')
            onLoad();
    });

    return true;
};

BxTimelineView.prototype.pauseLiveUpdates = function(onLoad)
{
    if(this._bLiveUpdatePaused)
        return false;

    var $this = this;
    this.changeLiveUpdates('pause_live_update', function() {
        $this._bLiveUpdatePaused = true;

        if(typeof onLoad == 'function')
            onLoad();
    });

    return true;
};

BxTimelineView.prototype.changeLiveUpdates = function(sAction, onLoad)
{
    var $this = this;
    var oParams = this._getDefaultActions();
    oParams['action'] = sAction;

    jQuery.get(
        this._sActionsUrl + sAction + '/',
        oParams,
        function() {
            if(typeof onLoad == 'function')
                onLoad();
        }
    );
};

BxTimelineView.prototype.blink = function(oParent)
{
	oParent.find('.' + this.sClassBlink + '-plate:visible').animate({
		opacity: 0
	}, 
	5000, 
	function() {
		oParent.find('.' + this.sClassBlink).removeClass(this.sClassBlink);
	});
};


/*------------------------------------*/
/*--- Internal (protected) methods ---*/
/*------------------------------------*/
BxTimelineView.prototype._getPage = function(oElement, iStart, iPerPage, onLoad)
{
    var $this = this;

    if(oElement)
        this.loadingIn(oElement, true);

    this._oRequestParams.start = iStart;
    this._oRequestParams.per_page = iPerPage;
    this._getPosts(oElement, function(oData) {
        if(oElement)
            $this.loadingIn(oElement, false);

    	var sItems = $.trim(oData.items);

        if($this.bViewTimeline)
            $this.oView.find('.' + $this.sClassItems).append($(sItems).hide()).find('.' + $this.sClassItem + ':hidden').bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                $(this).bxProcessHtml();

                //-- Check content to show 'See More'
                $this.initSeeMore($(this), false);

                //-- Init Flickity
                $this.initFlickity($this.oView);

                //--- Init Video Autoplay
                $this.initVideosAutoplay($this.oView);
            });

        if($this.bViewOutline)
            $this.appendMasonry($(sItems).bxProcessHtml(), function(oItems) {
                //-- Check content to show 'See More'
                $this.initSeeMore(oItems, false);

                //-- Init Flickity
                $this.initFlickity($this.oView);

                //--- Init Video Layout
                if($this._sVideosAutoplay != 'off') 
                    $this.initVideos($this.oView);
            });

    	if(oData && oData.load_more != undefined)
            $this.oView.find('.' + $this.sSP + '-load-more-holder').html($.trim(oData.load_more));

    	if(oData && oData.back != undefined)
            $this.oView.find('.' + $this.sSP + '-back-holder').html($.trim(oData.back));

    	if(oData && oData.empty != undefined && !$this.oView.find('.' + $this.sClassItem).length)
            $this.oView.find('.' + $this.sSP + '-empty-holder').html($.trim(oData.empty));

        if(typeof onLoad == 'function')
            onLoad(oData);
    });
};

BxTimelineView.prototype._getPosts = function(oElement, onComplete)
{
    var $this = this;
    var oData = this._getDefaultData(oElement);

    jQuery.get(
        this._sActionsUrl + 'get_posts/',
        oData,
        function(oData) {
            if(typeof onComplete === 'function')
                return onComplete(oData);

            if(oElement)
                $this.loadingInBlock(oElement, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxTimelineView.prototype._onGetPosts = function(oData)
{
    var $this = this;

    var onComplete = function() {
        if(oData && oData.go_to != undefined)
            location.hash = oData.go_to;

        if(oData && oData.load_more != undefined)
            $this.oView.find('.' + $this.sSP + '-load-more-holder').html($.trim(oData.load_more));

        if(oData && oData.back != undefined)
            $this.oView.find('.' + $this.sSP + '-back-holder').html($.trim(oData.back));

        if(oData && oData.empty != undefined)
            $this.oView.find('.' + $this.sSP + '-empty-holder').html($.trim(oData.empty));
    };

    if(oData && oData.items != undefined) {
        var sItems = $.trim(oData.items);

        if(this.bViewTimeline) {
            var oItems = this.oView.find('.' + this.sClassItems);
            oItems.html(sItems).bxProcessHtml();

            this.blink(oItems);
            this.initFlickity(this.oView);

            onComplete();
            return;
        }

        if(this.bViewOutline) {
            oItems = this.oView.find('.' + this.sClassItems);
            oItems.html(sItems).bxProcessHtml();

            if(this.isMasonry())
                this.destroyMasonry();

            if(!this.isMasonryEmpty())
                this.initMasonry();

            this.blink(oItems);
            this.initFlickity(this.oView);

            onComplete();
            return;
        }
    }
};

BxTimelineView.prototype._onGetPost = function(oData)
{
    if(!$.trim(oData.item).length) 
        return;

    var $this = this;
    var sItem = this._getHtmlId('item', this._oRequestParams, {whole:false}) + oData.id;
    this.oView.find(sItem).replaceWith($(oData.item).bxProcessHtml());
    this.oView.find(sItem).find('.bx-tl-item-text .bx-tl-content').checkOverflowHeight(this.sSP + '-overflow', function(oElement) {
        $this.onFindOverflow(oElement);
    });

    this.initFlickity(this.oView);
};

BxTimelineView.prototype._markPost = function(oLink, iId, iWay, sAction)
{
    var oData = this._getDefaultData();
    oData['id'] = iId;

    $(oLink).parents('.bx-popup-applied:first:visible').dolPopupHide({
        onHide: function(oPopup) {
            $(oPopup).remove();
        }
    });

    this.loadingInItem($(this._getHtmlId('item', this._oRequestParams, {whole:false}) + iId), true);

    $.post(
        this._sActionsUrl + sAction + '/',
        oData,
        function(oData) {
        	processJsonData(oData);
        },
        'json'
    );
};

BxTimelineView.prototype._onMarkPost = function(oData, sAction)
{
    var $this = this;
    var sItem = this._getHtmlId('item', this._oRequestParams, {whole:false}) + oData.id;

    this._oRequestParams.start = 0;

    //--- Mark on Timeline (if available)
    if(this.bViewTimeline)
        this._getPosts(this.oView, function(oData) {
            $(sItem).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                $(this).remove();

                processJsonData(oData);
            });
        });

    //--- Mark on Outline (if available)
    if(this.bViewOutline)
        this._getPosts(this.oView, function(oData) {
            $this.removeMasonry(sItem, function() {
                processJsonData(oData);
            });
        });
};