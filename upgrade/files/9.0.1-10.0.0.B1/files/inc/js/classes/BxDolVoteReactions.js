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
    
    this._sClassDoButton = 'bx-vote-do-vote';

}

BxDolVoteReactions.prototype = Object.create(BxDolVote.prototype);
BxDolVoteReactions.prototype.constructor = BxDolVoteReactions;

BxDolVoteReactions.prototype.vote = function(oLink, iValue, sReaction, onComplete)
{
    var $this = this;
    var oParams = this._getDefaultParams();
    oParams['action'] = 'Vote';
    oParams['value'] = iValue;
    oParams['reaction'] = sReaction;

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
    if(oData && oData.code != 0)
        return;
    
    $('#' + this._aHtmlIds['do_popup']).dolPopupHide({});

    oLink = $(oLink);
    if(!oLink.hasClass(this._sClassDoButton))
        oLink = $('#' + this._aHtmlIds['main'] + ' .' + this._sClassDoButton);

    //--- Update Do button.
    if(oData && oData.label_icon)
        oLink.find('.sys-icon').attr('class', 'sys-icon ' + oData.label_icon);

    if(oData && oData.label_title) {
        oLink.attr('title', oData.label_title);
        oLink.find('span').html(oData.label_title);
    }

    if(oData && oData.label_click)
        oLink.attr('onclick', 'javascript:' + oData.label_click)

    if(oData && oData.disabled)
        oLink.removeAttr('onclick').addClass($(oLink).hasClass('bx-btn') ? 'bx-btn-disabled' : 'bx-vote-disabled');

    //--- Update Counter.
    var oCounter = this._getCounter(oLink);
    if(oCounter && oCounter.length > 0)
        oCounter.filter('.' + oData.reaction).html(oData.countf).bx_anim(oData.count > 0 ? 'show' : 'hide');

    if(typeof onComplete == 'function')
        onComplete(oLink, oData);
};

BxDolVoteReactions.prototype.toggleDoPopup = function(oLink, iValue)
{
    var oParams = this._getDefaultParams();
    oParams['action'] = 'GetDoVotePopup';

    $(oLink).dolPopupAjax({
        id: {value: this._aHtmlIds['do_popup'], force: true}, 
        url: bx_append_url_params(this._sActionsUri, oParams),
        value: iValue
    });
};

BxDolVoteReactions.prototype.toggleByPopup = function(oLink, sReaction)
{
    var oParams = this._getDefaultParams();
    oParams['action'] = 'GetVotedBy';
    oParams['reaction'] = sReaction;

    $(oLink).dolPopupAjax({
        id: this._aHtmlIds['by_popup'], 
        url: bx_append_url_params(this._sActionsUri, oParams),
        removeOnClose: true
    });
};

BxDolVoteReactions.prototype._getCounter = function(oElement)
{
    var oCounter = BxDolVote.prototype._getCounter.call(this, oElement);
    if(oCounter && oCounter.length > 0)
        return oCounter;

    return $('#' + this._aHtmlIds['counter']).find('.' + this._sSP + '-counter');
};

/** @} */
