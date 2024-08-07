/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolVoteReactions(oOptions)
{
    BxDolVote.call(this, oOptions);

    this._iTimeoutShowId = 0;
    this._iTimeoutShowDelay = 750;
    this._iTimeoutHideId = 0;
    this._iTimeoutHideDelay = 1000;
    this._fOnVoteIn = null;
    this._fOnVoteOut = null;
    this._fOnDoPopupIn = null;
    this._fOnDoPopupOut = null;

    this._sClassDo = 'bx-vote-do-vote';
    this._sClassDoVoted = 'bx-vote-voted';

    var $this = this;
    $(document).ready(function() {
        $this.initVote();
    });
}

BxDolVoteReactions.prototype = Object.create(BxDolVote.prototype);
BxDolVoteReactions.prototype.constructor = BxDolVoteReactions;

BxDolVoteReactions.prototype.initVote = function()
{
    var $this = this;

    this._fOnVoteIn = function() {
        $this.onVoteIn(this);
    };

    this._fOnVoteOut = function() {
        $this.onVoteOut(this);
    };

    $('#' + this._aHtmlIds['main'] + ' .' + this._sClassDo).hover(this._fOnVoteIn, this._fOnVoteOut);
};

BxDolVoteReactions.prototype.vote = function(oLink, iValue, sReaction, onComplete)
{
    var $this = this;
    var oParams = this._getDefaultParams();
    oParams['action'] = 'Vote';
    oParams['value'] = iValue;
    oParams['reaction'] = sReaction;

    $('#' + this._aHtmlIds['do_popup']).dolPopupHide({});

    $.post(
    	this._sActionsUrl,
    	oParams,
    	function(oData) {
            if(oData && oData.message != undefined)
                bx_alert(oData.message, function() {
                    $this.onVote(oLink, oData, onComplete);
                });
            else
                $this.onVote(oLink, oData, onComplete);
        },
        'json'
    );
};

BxDolVoteReactions.prototype.onVote = function (oLink, oData, onComplete)
{
    var $this = this;

    if(oData && oData.code != 0)
        return;

    oLink = $('.' + this._aHtmlIds['main'] + ' .' + this._sClassDo);

    //--- Update Do button.
    oLink.each(function() {
        if(oData && oData.label_icon)
            $(this).find('.sys-action-do-icon .sys-icon').attr('class', 'sys-icon ' + oData.label_icon).html('');
        if(oData && oData.label_emoji){
            $(this).find('.sys-action-do-icon .sys-icon').html(oData.label_emoji).attr('class', 'sys-icon ');
        }
        
        if(oData && oData.label_title) {
            $(this).attr('title', oData.label_title);
            $(this).find('.sys-action-do-text').html(oData.label_title);
        }

        if(oData && oData.label_click)
            $(this).attr('onclick', 'javascript:' + oData.label_click)

        if(oData && oData.disabled)
            $(this).removeAttr('onclick').addClass($(this).hasClass('bx-btn') ? 'bx-btn-disabled' : 'bx-vote-disabled');
        else
            $(this).toggleClass($this._sClassDoVoted);
    });

    //--- Update Counter.
    var oCounter = this._getCounter(oLink);
    if(oCounter && oCounter.length > 0) {
        oCounter.filter('.' + oData.reaction).each(function() {
            $(this).html(oData.countf).toggleClass('bx-vc-hidden', !oData.count);            
        });

        //--- Update Total.
        if(oData.total)
            oCounter.filter('.total-count').each(function() {
                $(this).html(oData.total.countf).toggleClass('bx-vc-hidden', !oData.total.count);
            });
        
        oCounter.parents('.' + this._sSP + '-counter-wrapper:first').toggleClass('bx-vc-hidden', !oData.count && !oData.total.count);
    }

    if(typeof onComplete == 'function')
        onComplete(oLink, oData);
};

BxDolVoteReactions.prototype.onVoteIn = function(oLink)
{
    if($(oLink).hasClass(this._sClassDoVoted))
        return;

    if(this._iTimeoutHideId)
        clearTimeout(this._iTimeoutHideId);

    var oPopup = this.getDoPopup();
    if(oPopup !== false)
        return;

    this._iTimeoutShowId = setTimeout(function() {
        $(oLink).click();
    }, this._iTimeoutShowDelay);
};

BxDolVoteReactions.prototype.onVoteOut = function(oLink)
{
    if($(oLink).hasClass(this._sClassDoVoted))
        return;

    if(this._iTimeoutShowId)
        clearTimeout(this._iTimeoutShowId);

    this.hideDoPopup();
};

BxDolVoteReactions.prototype.getDoPopup = function()
{
    var oPopup = $('#' + this._aHtmlIds['do_popup'] + ':visible');
    return oPopup.length > 0 && oPopup.hasClass('bx-popup-applied') ? oPopup : false;
};

BxDolVoteReactions.prototype.toggleDoPopup = function(oLink, iValue)
{
    var $this = this;
    var oParams = this._getDefaultParams();
    oParams['action'] = 'GetDoVotePopup';

    if(this._iTimeoutShowId)
        clearTimeout(this._iTimeoutShowId);

    $(oLink).dolPopupAjax({
        id: {value: this._aHtmlIds['do_popup'], force: true}, 
        url: bx_append_url_params(this._sActionsUri, oParams),
        value: iValue,
        onShow: function(oPopup) {
            $this.onDoPopupShow(oPopup);
        },
        onHide: function(oPopup) {
            $this.onDoPopupHide(oPopup);
        }
    });
};

BxDolVoteReactions.prototype.hideDoPopup = function()
{
    var oPopup = this.getDoPopup();
    if(!oPopup)
        return;

    this._iTimeoutHideId = setTimeout(function() {
        oPopup.dolPopupHide();
    }, this._iTimeoutHideDelay);
};

BxDolVoteReactions.prototype.onDoPopupShow = function(oPopup)
{
    var $this = this;

    this._fOnDoPopupIn = function() {
        $this.onDoPopupIn(this);
    };

    this._fOnDoPopupOut = function() {
        $this.onDoPopupOut(this);
    };

    $(oPopup).hover(this._fOnDoPopupIn, this._fOnDoPopupOut);
};

BxDolVoteReactions.prototype.onDoPopupHide = function(oPopup)
{
    $(oPopup).unbind('mouseenter', this._fOnDoPopupIn).unbind('mouseleave', this._fOnDoPopupOut);
};

BxDolVoteReactions.prototype.onDoPopupIn = function(oPopup)
{
    if(this._iTimeoutHideId)
        clearTimeout(this._iTimeoutHideId);
};

BxDolVoteReactions.prototype.onDoPopupOut = function(oPopup)
{
    this.hideDoPopup();
};

BxDolVoteReactions.prototype.toggleByPopup = function(oLink, sReaction)
{
    var oParams = this._getDefaultParams();
    oParams['action'] = 'GetVotedBy';
    if(sReaction)
        oParams['reaction'] = sReaction;

    $(oLink).dolPopupAjax({
        id: {value: this._aHtmlIds['by_popup'], force: true}, 
        url: bx_append_url_params(this._sActionsUri, oParams),
        removeOnClose: true
    });
};

BxDolVoteReactions.prototype.changeVotedBy = function(oLink, sReaction)
{
    var oContent = $('#' + this._aHtmlIds['by_popup'] + ' .' + this._sSP + '-bls-content');
    if(oContent.length > 0)
        oContent.children(':visible').hide().siblings('.' + this._sSP + '-bl-' + sReaction).show();
};

BxDolVoteReactions.prototype._getCounter = function(oElement)
{
    var oCounter = BxDolVote.prototype._getCounter.call(this, oElement);
    if(oCounter && oCounter.length > 0)
        return oCounter;

    return $('.' + this._aHtmlIds['counter']).find('.' + this._sSP + '-counter');
};

/** @} */
