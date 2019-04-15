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
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oTimelineView' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sReferrer = oOptions.sReferrer == undefined ? '' : oOptions.sReferrer;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sVideosAutoplay = oOptions.sVideosAutoplay == undefined ? 'off' : oOptions.sVideosAutoplay;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

    this._fOutsideOffset = 0.8;
    this._oSaved = {};

    this._oVapPlayers = {};
    this._fVapOffsetStart = 0.8;
    this._fVapOffsetStop = 0.2;

    this._bLiveUpdatePaused = false;

    var $this = this;
    $(document).ready(function() {
    	$this.init();
    });
}

BxTimelineView.prototype = new BxTimelineMain();

BxTimelineView.prototype.init = function()
{
    var $this = this;

    this.oView = $(this._getHtmlId('main', this._oRequestParams));
    if(this.oView.length > 0) {
        if(this.oView.hasClass(this.sClassView + '-timeline'))
            this.bViewTimeline = true;
        else if(this.oView.hasClass(this.sClassView + '-outline'))
            this.bViewOutline = true;
        else if(this.oView.hasClass(this.sClassView + '-item'))
            this.bViewItem = true;
    }

    if(this.bViewTimeline) {
        var oItems = this.oView.find('.' + this.sClassItem);
        oItems.find('.bx-tl-item-text .bx-tl-content').checkOverflowHeight(this.sSP + '-overflow', function(oElement) {
            $this.onFindOverflow(oElement);
        });

        //--- Hide timeline Events which are outside the viewport
        this.hideEvents(oItems, this._fOutsideOffset);

        //--- on scolling, show/animate timeline Events when enter the viewport
        $(window).on('scroll', function() {
            if(!window.requestAnimationFrame) 
                setTimeout(function() {
                    $this.showEvents(oItems, $this._fOutsideOffset);
                }, 100);
            else
                window.requestAnimationFrame(function() {
                    $this.showEvents(oItems, $this._fOutsideOffset);
                });
        });

        //--- Init Video Autoplay
        if(this._sVideosAutoplay != 'off') {
            this.initVideosAutoplay(this.oView);

            $(window).on('scroll', function() {
                var oItems = $this.oView.find('.' + $this.sClassItem);

                if(!window.requestAnimationFrame) 
                    setTimeout(function() {
                        $this.playVideos(oItems, $this._fVapOffsetStart, $this._fVapOffsetStop);
                    }, 100);
                else
                    window.requestAnimationFrame(function() {
                        $this.playVideos(oItems, $this._fVapOffsetStart, $this._fVapOffsetStop);
                    });
            });
        }

        //--- Blink (highlight) necessary items
        this.blink(this.oView);
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
    }

    if(this.bViewItem) {
        this.oView.find('.bx-tl-item-text .bx-tl-content').checkOverflowHeight(this.sSP + '-overflow', function(oElement) {
            $this.onFindOverflow(oElement);
        });

        //--- Init Video Layout
        if(this._sVideosAutoplay != 'off')
            this.initVideos(this.oView);
    }

    this.initFlickity();
};

BxTimelineView.prototype.initVideosAutoplay = function(oParent)
{
    var $this = this;

    if(this._sVideosAutoplay == 'off')
        return;

    this.initVideos(oParent);

    oParent.find('iframe').each(function() {
        var sPlayer = $(this).attr('id');
        if($this._oVapPlayers[sPlayer])
            return;

        var oPlayer = new playerjs.Player(this);
        if($this._sVideosAutoplay == 'on_mute')
            oPlayer.mute();

        var fFixHeight = function () {
            $('#' + sPlayer).height(($('#' + sPlayer).contents().find('video').height()) + 'px');
        };
        oPlayer.on('ready', fFixHeight);
        oPlayer.on('play', fFixHeight);

        $this._oVapPlayers[sPlayer] = oPlayer;
    });
};

BxTimelineView.prototype.hideEvents = function(oEvents, fOffset)
{
    oEvents.each(function(iIndex, oElement) {
        (iIndex >=3 && $(this).offset().top > $(window).scrollTop() + $(window).height() * fOffset ) && $(this).find('.bx-tl-item-type, .bx-tl-item-cnt').addClass('is-hidden');
    });
};

BxTimelineView.prototype.showEvents = function(oEvents, fOffset)
{
    oEvents.each(function() {
        ( $(this).offset().top <= $(window).scrollTop() + $(window).height() * fOffset && $(this).find('.bx-tl-item-type').hasClass('is-hidden') ) && $(this).find('.bx-tl-item-type, .bx-tl-item-cnt').removeClass('is-hidden').addClass('bounce-in');
    });
};

BxTimelineView.prototype.playVideos = function(oEvents, fOffsetStart, fOffsetStop)
{
    var $this = this;

    oEvents.each(function() {
        $(this).find('iframe').each(function() {
            var oFrame = $(this);
            var oPlayer = $this._oVapPlayers[oFrame.attr('id')];
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

BxTimelineView.prototype.changePage = function(oLink, iStart, iPerPage)
{
    var $this = this;

    this.loadingInButton(oLink, true);

    this._oRequestParams.start = iStart;
    this._oRequestParams.per_page = iPerPage;
    this._getPosts(oLink, function(oData) {
    	$this.loadingInButton(oLink, false);

    	var sItems = $.trim(oData.items);

        if($this.bViewTimeline)
            $this.oView.find('.' + $this.sClassItems).append($(sItems).hide()).find('.' + $this.sClassItem + ':hidden').bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                $(this).bxProcessHtml().find('.bx-tl-item-text .bx-tl-content').checkOverflowHeight($this.sSP + '-overflow', function(oElement) {
                    $this.onFindOverflow(oElement);
                });

                $this.initFlickity();

                //--- Init Video Autoplay
                $this.initVideosAutoplay($this.oView);
            });

        if($this.bViewOutline)
            $this.appendMasonry($(sItems).bxProcessHtml(), function(oItems) {
                oItems.find('.bx-tl-item-text .bx-tl-content').checkOverflowHeight($this.sSP + '-overflow', function(oElement) {
                    $this.onFindOverflow(oElement);
                });

                $this.initFlickity();

                //--- Init Video Layout
                if($this._sVideosAutoplay != 'off') 
                    $this.initVideos($this.oView);
            });

    	if(oData && oData.load_more != undefined)
            $this.oView.find('.' + $this.sSP + '-load-more-holder').html($.trim(oData.load_more));

    	if(oData && oData.back != undefined)
            $this.oView.find('.' + $this.sSP + '-back-holder').html($.trim(oData.back));

    	if(oData && oData.empty != undefined)
            $this.oView.find('.' + $this.sSP + '-empty-holder').html($.trim(oData.empty));
    });
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

BxTimelineView.prototype.changeTimeline = function(oLink, iYear)
{
    this.loadingInBlock(oLink, true);

    this._oRequestParams.start = 0;
    this._oRequestParams.timeline = iYear;
    this._getPosts(oLink);
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
            value: this._getHtmlId('item_popup', this._oRequestParams, false, false) + iId, 
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

    var oLoadingContainer = $(this._getHtmlId('item', this._oRequestParams, false) + iId);

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

    var oItem = this.oView.find(this._getHtmlId('item', this._oRequestParams, false) + iId);

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

    var oItem = $(this._getHtmlId('item', this._oRequestParams, false) + oData.id);

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

        $this.loadingInItem($($this._getHtmlId('item', $this._oRequestParams, false) + iId), true);

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
    var oItem = $(this._getHtmlId('item', this._oRequestParams, false) + oData.id);

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
            this.initFlickity();

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
            this.initFlickity();

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
    var sItem = this._getHtmlId('item', this._oRequestParams, false) + oData.id;
    this.oView.find(sItem).replaceWith($(oData.item).bxProcessHtml());
    this.oView.find(sItem).find('.bx-tl-item-text .bx-tl-content').checkOverflowHeight(this.sSP + '-overflow', function(oElement) {
        $this.onFindOverflow(oElement);
    });
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

    this.loadingInItem($(this._getHtmlId('item', this._oRequestParams, false) + iId), true);

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
    var sItem = this._getHtmlId('item', this._oRequestParams, false) + oData.id;

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