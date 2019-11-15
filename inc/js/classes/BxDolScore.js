/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolScore(oOptions)
{
    this._sObjName = undefined == oOptions.sObjName ? 'oScore' : oOptions.sObjName; // javascript object name, to run current object instance from onTimer
    this._sSystem = oOptions.sSystem; // current score system
    this._iAuthorId = oOptions.iAuthorId; // score's author ID.
    this._iObjId = oOptions.iObjId; // object id the scores are collected for

    this._sActionsUri = 'score.php';
    this._sActionsUrl = oOptions.sRootUrl + this._sActionsUri; // actions url address

    this._sAnimationEffect = 'fade';
    this._iAnimationSpeed = 'slow';
    this._sSP = undefined == oOptions.sStylePrefix ? 'bx-score' : oOptions.sStylePrefix;
    this._aHtmlIds = oOptions.aHtmlIds;
    this._aRequestParams = oOptions.aRequestParams;

    this._iSaveWidth = -1;
}

BxDolScore.prototype.toggleByPopup = function(oLink) {
    var $this = this;
    var oData = this._getDefaultParams();
    oData['action'] = 'GetVotedBy';

    $(oLink).dolPopupAjax({
        id: this._aHtmlIds['by_popup'], 
        url: bx_append_url_params(this._sActionsUri, oData)
    });
};

BxDolScore.prototype.voteUp = function (oLink, onComplete)
{
	this._vote(oLink, 'up', onComplete);
};

BxDolScore.prototype.voteDown = function (oLink, onComplete)
{
	this._vote(oLink, 'down', onComplete);
};

BxDolScore.prototype._vote = function (oLink, sType, onComplete)
{
    var $this = this;
    var oParams = this._getDefaultParams();
    oParams['action'] = 'vote_' + sType;

    $.post(
    	this._sActionsUrl,
    	oParams,
    	function(oData) {
            var fContinue = function() {
            if(oData && oData.code != 0)
                return;

            if(oData && oData.label_icon)
                $(oLink).find('.sys-action-do-icon .sys-icon').attr('class', 'sys-icon ' + oData.label_icon);

            if(oData && oData.label_title) {
                $(oLink).attr('title', oData.label_title);
                $(oLink).find('.sys-action-do-text').html(oData.label_title);
            }

            if(oData && oData.disabled)
                $this._getActions(oLink).removeAttr('onclick').addClass($(oLink).hasClass('bx-btn') ? 'bx-btn-disabled' : 'bx-score-disabled');

            var oCounter = $this._getCounter(oLink);
            if(oCounter && oCounter.length > 0) {
                if(oData && oData.counter)
                    oCounter.replaceWith(oData.counter);
                else
                    oCounter.html(oData.scoref).parents('.' + $this._sSP + '-counter-holder:first:hidden').bx_anim('show');
            }

            if(typeof onComplete == 'function')
                onComplete(oLink, oData);
            };

            if(oData && oData.message != undefined)
                bx_alert(oData.message, fContinue);
            else
                fContinue();
        },
        'json'
    );
};

BxDolScore.prototype._getActions = function(oElement) {
    var oParent = $(oElement);
    if(!oParent.hasClass(this._sSP))
        oParent = $(oElement).parents('.' + this._sSP + ':first');

    return oParent.find('.' + this._sSP + '-do-vote');
};

BxDolScore.prototype._getCounter = function(oElement) {
    var oParent = $(oElement);
    if(!oParent.hasClass(this._sSP))
        oParent = $(oElement).parents('.' + this._sSP + ':first');

    var oCounter = oParent.find('.' + this._sSP + '-counter');
    if(oCounter && oCounter.length > 0)
        return oCounter;

    return $('#' + this._aHtmlIds['counter'] + '.' + this._sSP + '-counter');
};

BxDolScore.prototype._loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);
};

BxDolScore.prototype._getDefaultParams = function() {
    var oDate = new Date();
    return {
        sys: this._sSystem,
        id: this._iObjId,
        params: $.param(this._aRequestParams),
        _t: oDate.getTime()
    };
};

/** @} */
