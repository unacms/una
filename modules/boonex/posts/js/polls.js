/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPostsPolls(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxPostsPolls' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxPostsPolls.prototype.initFlickity = function(oParent) {
    if(!oParent)
        return;

    if(typeof(oParent) == 'string')
        oParent = $(oParent);

    var sItemClass = 'bx-base-text-poll';
    var sItemsClass = 'bx-base-text-polls-showcase';

    var oItems = $(oParent).hasClass(sItemsClass) ? oParent : oParent.find('.' + sItemsClass);
    if(oItems.find('.' + sItemClass).length <= 1)
        return;

    oItems.flickity({
        cellSelector: 'div.' + sItemClass,
        cellAlign: 'left',
        imagesLoaded: true,
        wrapAround: true,
        pageDots: false
    });
};

BxPostsPolls.prototype.initPollForm = function(sFormId)
{
    var $this = this;
    var oForm = $('#' + sFormId);

    oForm.ajaxForm({
        dataType: "json",
        clearForm: true,
        beforeSubmit: function (formData, jqForm, options) {
            window[$this._sObjName].beforePollFormSubmit(oForm);
        },
        success: function (oData) {
            window[$this._sObjName].afterPollFormSubmit(oForm, oData);
        }
    });
};

BxPostsPolls.prototype.beforePollFormSubmit = function(oForm)
{
    this.loadingInButton($(oForm).children().find(':submit'), true);
};

BxPostsPolls.prototype.afterPollFormSubmit = function (oForm, oData)
{
    var $this = this;
    var fContinue = function() {
        if(oData && oData.item != undefined) {
            $('#' + $this._aHtmlIds['add_poll_popup']).dolPopupHide({onHide: function(oPopup) {
                oPopup.remove();
            }});

            if(!$.trim(oData.item).length)
                return;

            var oItem = $(oData.item).hide();
            $('#' + $this._aHtmlIds['add_poll_form_field']).append(oItem).find('#' + $this._aHtmlIds['poll'] + oData.id).bx_anim('show', $this._sAnimationEffect, $this._sAnimationSpeed);

            return;
        }

        if(oData && oData.form != undefined && oData.form_id != undefined) {
            $('#' + oData.form_id).replaceWith(oData.form);
            $this.initPollForm(oData.form_id);

            return;
        }
    };

    this.loadingInButton($(oForm).find(':submit'), false);

    if(oData && oData.message != undefined)
        bx_alert(oData.message, fContinue);
    else
        fContinue();
};

BxPostsPolls.prototype.deletePoll = function(oLink, iId)
{
    var $this = this;

    bx_confirm('', function() {
        var oData = $this._getDefaultData();
        oData['id'] = iId;

        var oPoll = $('#' + $this._aHtmlIds['poll'] + iId);

        bx_loading(oPoll, true);

        jQuery.post (
            $this._sActionsUrl + 'delete_poll/',
            oData,
            function(oData) {
                var fContinue = function() {
                    if(oData && oData.code != undefined && oData.code == 0) {
                        oPoll.bx_anim('hide', $this._sAnimationEffect, $this._sAnimationSpeed, function() {
                            $(this).remove;
                        });
                    }
                };

                bx_loading(oPoll, false);

                if(oData && oData.message != undefined)
                    bx_alert(oData.message, fContinue);
                else
                    fContinue();
            },
            'json'
        );
    });

    return false;
};

BxPostsPolls.prototype.showPollForm = function(oLink)
{
    var oData = this._getDefaultData();    

    $(window).dolPopupAjax({
        id: {value: this._aHtmlIds['add_poll_popup'], force: true},
        url: bx_append_url_params(this._sActionsUri + 'get_poll_form/', oData),
        closeOnOuterClick: false
    });

    return false;
};

BxPostsPolls.prototype.changePollView = function(oLink, sView, iPollId, onComplete) {
    var $this = this;

    this.loadingInBox(oLink, true);

    jQuery.get (
        this._sActionsUrl + 'get_poll',
        {
            poll_id: iPollId,
            view: sView
        },
        function(oData) {
            if(oLink)
                $this.loadingInBox(oLink, false);

            if(!oData.content)
                return;

            var sPollId = $this._aHtmlIds['poll'] + iPollId;
            var sContentId = $this._aHtmlIds['poll_content'] + iPollId;

            var oPoll = $(oLink).parents('.bx-db-container:first').find('#' + sPollId);
            if(!oPoll.length)
                oPoll = $('#' + sPollId);
            if(!oPoll.length)
                return;

            var oContent = $(oLink).parents('.bx-db-container:first').find('#' + sContentId);
            if(!oContent.length)
                oContent = $('#' + sContentId);
            if(!oContent.length)
                return;

            oContent.bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                $(this).replaceWith(oData.content);
                
                if(typeof onComplete === 'function')
                    onComplete(oPoll, iPollId, oData);
            });
            
            
        },
        'json'
    );
};

BxPostsPolls.prototype.addPollAnswer = function(oButton, sName) {
    var oButton = $(oButton);

    var oSubentry = oButton.parents('#bx-form-element-' + sName).find('.bx-form-input-answer:hidden:first').clone();
    oButton.parents('.bx-form-input-answer-add:first').before(oSubentry.removeClass('bx-fi-answer-blank').find("input[type = 'text']").removeAttr('disabled').end());
};

BxPostsPolls.prototype.deletePollAnswer = function(oButton) {
    $(oButton).parents('.bx-form-input-answer:first').remove();
};

BxPostsPolls.prototype.onPollAnswerVote = function(oLink, oData, iPollId) {
    var $this = this;

    this.changePollView(oLink, 'results', iPollId, function(oPoll, iPollId, oData) {
        var sMenuLink = $this._aHtmlIds['poll_view_link_results'] + iPollId;
        var oMenuLink = $(oPoll).find('.bx-menu-inter:first #' + sMenuLink);
        if(oMenuLink.length > 0)
            oMenuLink.parent().siblings('.bx-menu-inter-act:visible').hide().siblings('.bx-menu-inter-pas:hidden').show().siblings('#' + sMenuLink + '-pas:visible').hide().siblings('#' + sMenuLink + '-act:hidden').show();
    });
};

BxPostsPolls.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);	
};

BxPostsPolls.prototype.loadingInBox = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-base-text-poll:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxPostsPolls.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxPostsPolls.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

/** @} */
