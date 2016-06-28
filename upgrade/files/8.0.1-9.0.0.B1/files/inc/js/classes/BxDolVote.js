/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

function BxDolVote(options)
{
	this._sObjName = undefined == options.sObjName ? 'oVote' : options.sObjName; // javascript object name, to run current object instance from onTimer
	this._sSystem = options.sSystem; // current comment system
	this._iAuthorId = options.iAuthorId; // this comment's author ID.
    this._iObjId = options.iObjId; // this object id comments
    this._iLikeMode = undefined == options.iLikeMode ? 0 : options.iLikeMode;

    this._sActionsUri = 'vote.php';
    this._sActionsUrl = options.sRootUrl + this._sActionsUri; // actions url address

    this._sAnimationEffect = 'fade';
    this._iAnimationSpeed = 'slow';
    this._sSP = undefined == options.sStylePrefix ? 'bx-vote' : options.sStylePrefix;
    this._aHtmlIds = options.aHtmlIds;

    this._iSaveWidth = -1;

    //--- Init stars based vote ---//
    var $this = this;
    if(!this._iLikeMode) {
    	$('.' + this._sSP + '.' + this._sSP + '-stars').each(function() {
    		var oDoVote = $(this);
	    	var fRate = oDoVote.attr('bx_vote_data_rate');
	    	var iStarWidth = $this._getStarWidthDo(oDoVote);

	    	$this._getSliderDo(oDoVote).width(Math.round(fRate * iStarWidth));
    	});

    	$('.' + this._sSP + '-legend.' + this._sSP + '-legend-stars').each(function() {
    		var oLegend = $(this);
    		var iStarWidth = $this._getStarWidthLegend(oLegend);

	    	oLegend.find('.' + $this._sSP + '-legend-item').each(function() {
	    		var oItem = $(this);

	    		oItem.find('.' + $this._sSP + '-slider').width(parseInt(oItem.attr('bx_vote_item_value')) * iStarWidth); 
	    	});
    	});
    }
}

BxDolVote.prototype.toggleByPopup = function(oLink) {
	var $this = this;
    var oData = this._getDefaultParams();
    oData['action'] = 'GetVotedBy';

	$(oLink).dolPopupAjax({
		id: this._aHtmlIds['by_popup'], 
		url: bx_append_url_params(this._sActionsUri, oData)
	});
};

BxDolVote.prototype.over = function (oLink)
{
	var oSlider = this._getSliderDo(oLink);
	var iIndex = this._getButtons(oLink).index(oLink);

    this._iSaveWidth = parseInt(oSlider.width());
    oSlider.width((iIndex + 1) * this._getStarWidthDo(oLink));
};

BxDolVote.prototype.out = function (oLink)
{
	var oSlider = this._getSliderDo(oLink);

	oSlider.width(this._iSaveWidth);
};

BxDolVote.prototype.vote = function (oLink, iValue)
{
    var $this = this;
    var oParams = this._getDefaultParams();
    oParams['action'] = 'Vote';
    oParams['value'] = iValue;

    $.post(
    	this._sActionsUrl,
    	oParams,
    	function(oData) {
    		if(oData && oData.msg != undefined)
                alert(oData.msg);

    		if(oData && oData.code != 0)
                return;

    		if($this._iLikeMode) {
	    		if(oData && oData.label_icon)
	    			$(oLink).find('.sys-icon').attr('class', 'sys-icon ' + oData.label_icon);

	    		if(oData && oData.label_title) {
	    			$(oLink).attr('title', oData.label_title);
	    			$(oLink).find('span').html(oData.label_title);
	    		}

	    		if(oData && oData.disabled)
	    			$(oLink).removeAttr('onclick').addClass($(oLink).hasClass('bx-btn') ? 'bx-btn-disabled' : 'bx-vote-disabled');
    		}

    		if(!$this._iLikeMode)
    			$this._iSaveWidth = Math.round(oData.rate * $this._getStarWidthDo(oLink));

            var oCounter = $this._getCounter(oLink);
            if(oCounter && oCounter.length > 0) {
            	oCounter.html(oData.countf);

            	oCounter.parents('.' + $this._sSP + '-counter-holder:first').bx_anim(oData.count > 0 ? 'show' : 'hide');
            }
        },
        'json'
    );
};

BxDolVote.prototype._getButtons = function(oElement) {
	if($(oElement).hasClass(this._sSP))
		return $(oElement).find('.' + this._sSP + '-button');
	else
		return $(oElement).parents('.' + this._sSP + ':first').find('.' + this._sSP + '-button');
};

BxDolVote.prototype._getSliderDo = function(oElement) {
	return this._getSlider(oElement, '.' + this._sSP + '-do');
};
BxDolVote.prototype._getSliderLegend = function(oElement) {
	return this._getSlider(oElement, '.' + this._sSP + '-legend');
};
BxDolVote.prototype._getSlider = function(oElement, sParent) {
	var sSlider = (sParent.length > 0 ? sParent + ' ' : '') + '.' + this._sSP + '-slider';
	if($(oElement).hasClass(this._sSP))
		return $(oElement).find(sSlider);
	else
		return $(oElement).parents('.' + this._sSP + ':first').find(sSlider);
};

BxDolVote.prototype._getStarWidthDo = function(oElement) {
	return this._getSliderDo(oElement).find('.sys-icon').width();
};
BxDolVote.prototype._getStarWidthLegend = function(oElement) {
	return this._getSliderLegend(oElement).find('.sys-icon').width();
};

BxDolVote.prototype._getCounter = function(oElement) {
	if($(oElement).hasClass(this._sSP))
		return $(oElement).find('.' + this._sSP + '-counter');
	else 
		return $(oElement).parents('.' + this._sSP + ':first').find('.' + this._sSP + '-counter');
};

BxDolVote.prototype._loadingInButton = function(e, bShow) {
	if($(e).length)
		bx_loading_btn($(e), bShow);
	else
		bx_loading($('body'), bShow);	
};

BxDolVote.prototype._getDefaultParams = function() {
	var oDate = new Date();
    return {
        sys: this._sSystem,
        id: this._iObjId,
        _t: oDate.getTime()
    };
};

/** @} */
