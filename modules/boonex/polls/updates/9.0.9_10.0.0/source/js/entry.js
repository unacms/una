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

BxPollsEntry.prototype.changeBlockSnippet = function(oLink, sBlock, mixedContent) {
    var $this = this;

    this.changeBlock(oLink, sBlock, mixedContent, function(iContentId, oData) {
        $(oLink).hide().siblings('.bx-base-text-unit-switcher:hidden').show();

        $(oLink).parents('.bx-base-text-unit:first').find('#' + $this._aHtmlIds['content'] + iContentId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
            $(this).replaceWith(oData.content);
        });
    });
};

BxPollsEntry.prototype.changeBlock = function(oLink, sBlock, mixedContent, onComplete) {
    var $this = this;

    this.loadingInBlock(oLink, true);

    if(typeof onComplete !== 'function')
        onComplete = function(iContentId, oData) {
            var sContentId = $this._aHtmlIds['content'] + iContentId;

            var oContent = $(oLink).parents('.bx-db-container:first').find('#' + sContentId);
            if(!oContent.length)
                oContent = $('#' + sContentId);
            if(!oContent.length)
                return;

            oContent.bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                $(this).replaceWith(oData.content);
            });
        };

    var iContentId = 0;
    var oParams = {
        block: sBlock,
    };

    switch(typeof mixedContent) {
        case 'number':
            iContentId = parseInt(mixedContent);
            oParams['content_id'] = iContentId;
            break;

        case 'object':
            iContentId = parseInt(mixedContent.content_id);
            oParams = $.extend({}, oParams, mixedContent); 
            break;
    }

    jQuery.get (
        this._sActionsUrl + 'get_block',
        oParams,
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

BxPollsEntry.prototype.onVote = function(oLink, oData, iContentId, sSalt) {
    //--- Check for block submenu (tabs)
    var oMenuLink = $('#' + this._aHtmlIds['block_link_results'] + sSalt);
    if(oMenuLink.length > 0) {
        oMenuLink.click();
        return;
    }

    //--- Check for snippet meta submenu
    var oMenuLink = $('#' + this._aHtmlIds['snippet_link_results'] + sSalt);
    if(oMenuLink.length > 0) {
        oMenuLink.click();
        return;
    }

    this.changeBlock(oLink, 'results', {content_id: iContentId, salt: sSalt});
};

BxPollsEntry.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};