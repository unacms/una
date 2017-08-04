/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPollsEntry(oOptions) {
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxPollsEntry' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxPollsEntry.prototype.changeBlockSnippet = function(oLink, sBlock, iContentId) {
	var $this = this;

	this.changeBlock(oLink, sBlock, iContentId, function(iContentId, oData) {
		$(oLink).hide().siblings('.bx-base-text-unit-switcher:hidden').show();

		$(oLink).parents('.bx-base-text-unit:first').find('#' + $this._aHtmlIds['content'] + iContentId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
    		$(this).replaceWith(oData.content);
    	});
	});
};

BxPollsEntry.prototype.changeBlock = function(oLink, sBlock, iContentId, onComplete) {
	var $this = this;

    this.loadingInBlock(oLink, true);

    if(typeof onComplete !== 'function')
    	onComplete = function(iContentId, oData) {
			$('#' + $this._aHtmlIds['content'] + iContentId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
	    		$(this).replaceWith(oData.content);
	    	});
		};

    jQuery.get (
        this._sActionsUrl + 'get_block',
        {
        	block: sBlock,
        	content_id: iContentId
        },
        function(oData) {
        	if(oLink)
        		$this.loadingInBlock(oLink, false);

        	if(!oData.content)
        		return;

        	if(typeof onComplete === 'function')
    			onComplete(iContentId, oData);
        },
        'json'
    );
};

BxPollsEntry.prototype.onVote = function(oLink, oData, iContentId) {
	
	var oMenuLink = $('#' + this._aHtmlIds['block_link_results']);
	if(oMenuLink.length > 0) {
		oMenuLink.click();
		return;
	}

	this.changeBlock(oLink, 'results', iContentId);
};

BxPollsEntry.prototype.loadingInBlock = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
	bx_loading(oParent, bShow);
};