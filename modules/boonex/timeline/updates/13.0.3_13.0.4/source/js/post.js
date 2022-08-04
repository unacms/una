/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

function BxTimelinePost(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oTimelinePost' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : parseInt(oOptions.iOwnerId);
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._bEmoji = oOptions.bEmoji == undefined ? false : oOptions.bEmoji;
    this._bAutoAttach = oOptions.bAutoAttach == undefined ? false : oOptions.bAutoAttach;
    this._sAutoUploader = oOptions.sAutoUploader == undefined ? '' : oOptions.sAutoUploader;
    this._sAutoUploaderId = oOptions.sAutoUploaderId == undefined ? '' : oOptions.sAutoUploaderId;
    this._iLimitAttachLinks = oOptions.iLimitAttachLinks == undefined ? 0 : oOptions.iLimitAttachLinks;
    this._sLimitAttachLinksErr = oOptions.sLimitAttachLinksErr == undefined ? '' : oOptions.sLimitAttachLinksErr;
    this._oAttachedLinks = oOptions.oAttachedLinks == undefined ? {} : oOptions.oAttachedLinks;
    this._sVideosAutoplay = oOptions.sVideosAutoplay == undefined ? 'off' : oOptions.sVideosAutoplay;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

    this._sPregTag = "(<([^>]+bx-tag[^>]+)>)";
    this._sPregMention = "(<([^>]+bx-mention[^>]+)>)";
    this._sPregUrl = "(([A-Za-z]{3,9}:(?:\\/\\/)?)(?:[\\-;:&=\\+\\$,\\w]+@)?[A-Za-z0-9\\.\\-]+|(?:www\\.|[\\-;:&=\\+\\$,\\w]+@)[A-Za-z0-9\\.\\-]+)((?:\\/[\\+~%#\\/\\.\\w\\-_!\\(\\)]*)?\\??(?:[\\-\\+=&;%@\\.\\w_]*)#?(?:[\\.\\!\\/\\w]*))?";

    var $this = this;
    if (typeof window.glOnInitEditor === 'undefined')
        window.glOnInitEditor = [];

    if(this._bEmoji)
        window.glOnInitEditor.push(function (sEditorSelector) {
            var sBaseUrl = sUrlRoot + 'plugins_public/emoji/js/';
            if ('undefined' === typeof(EmojiPicker)) {
                bx_get_scripts([
                    sBaseUrl + 'util.js', 
                    sBaseUrl + 'config.js',
                    sBaseUrl + 'emoji-picker.js', 
                    sBaseUrl + 'jquery.emojiarea.js'
                ], function() {
                    $this.initEmoji(sEditorSelector)
                });
            }
            else
                $this.initEmoji(sEditorSelector);
        });

    $(document).ready(function () {
        var oPost = $($this.sIdPost + ' form');
        if(!oPost || oPost.length == 0)
            oPost = $($this.sIdPostForm);

    	oPost.each(function() {
            var sId = $(this).attr('id');
            $this.initFormPost(sId);
    	});
    });
}

BxTimelinePost.prototype = new BxTimelineMain();

BxTimelinePost.prototype.initEmoji = function(sEditorSelector)
{
    if(!this._bEmoji)
        return;

    var oLink = $(sEditorSelector).parents('form:first').find('a.add-emoji');

    var oEmojiConf = {
        emojiable_selector: bx_editor_get_htmleditable(sEditorSelector), 
        menu_icon: oLink  /* emoji popup menu icon */
    };
    new EmojiPicker(oEmojiConf ).discover();  /* call init emoji function */
};

BxTimelinePost.prototype.initFormPost = function(sFormId)
{
    var $this = this;
    var oForm = $('#' + sFormId);
    var oTextarea = oForm.find('textarea');
    autosize(oTextarea);

    this._oAttachedLinks = [];

    oForm.ajaxForm({
        dataType: "json",
        beforeSubmit: function (formData, jqForm, options) {
            window[$this._sObjName].beforeFormPostSubmit(oForm);
        },
        success: function (oData) {
            window[$this._sObjName].afterFormPostSubmit(oForm, oData);
        }
    });

    if (typeof window.glOnSpaceEnterInEditor === 'undefined')
        window.glOnSpaceEnterInEditor = [];    

    window.glOnSpaceEnterInEditor.push(function (sData, sSelector) {
        if(!oTextarea.is(sSelector))
            return;

        var oExp, aMatch = null;

        oExp = new RegExp($this._sPregTag , "ig");
        sData = sData.replace(oExp, '');

        oExp = new RegExp($this._sPregMention , "ig");
        sData = sData.replace(oExp, '');

        oExp = new RegExp($this._sPregUrl , "ig");
        while(aMatch = oExp.exec(sData)) {
            var sUrl = aMatch[0].replace(/^(\s|(&nbsp;))+|(\s|(&nbsp;))+$/gm,'');
            if(!sUrl.length || $this._oAttachedLinks[sUrl] != undefined || ($this._iLimitAttachLinks != 0 && Object.keys($this._oAttachedLinks).length >= $this._iLimitAttachLinks))
                continue;

            $this.lockForm($('#' + sFormId));

            //--- Mark that 'attach link' process was started.
            $this._oAttachedLinks[sUrl] = 0;

            $this.addAttachLink(oForm, sUrl);
        }
    });

    if(this._bAutoAttach) {
        if (typeof window.glOnInsertImageInEditor === 'undefined')
            window.glOnInsertImageInEditor = [];

        window.glOnInsertImageInEditor.push(function (oFile) {
            const oFormData = new FormData();
            oFormData.append('file', oFile);
            oFormData.append('u', $this._sAutoUploader);
            oFormData.append('uid', $this._sAutoUploaderId);

            fetch($this._sActionsUrl + 'auto_attach_insertion/', {method: "POST", body: oFormData})
            .then(response => response.json())
            .then(result => {
                processJsonData(result)
            });
        });
   }
};

BxTimelinePost.prototype.onFormPostSubmit = function(oForm)
{
    if(this.isLockedForm($(oForm)))
        return false;

    return true;
};

BxTimelinePost.prototype.beforeFormPostSubmit = function(oForm)
{
    this.loadingInButton($(oForm).children().find(':submit'), true);
};

BxTimelinePost.prototype.afterFormPostSubmit = function (oForm, oData)
{
    var $this = this;
    var fContinue = function() {
        if(oData && oData.form != undefined && oData.form_id != undefined) {
            $('#' + oData.form_id).replaceWith(oData.form);
            $this.initFormPost(oData.form_id);

            return;
        }

        if(oData && oData.id != undefined) {
            var iId = parseInt(oData.id);
            if(iId <= 0) 
                return;

            $('.' + $this.sClassView + ':visible').each(function() {
                var oView = $(this);
                $this._getPost(oView, iId, jQuery.extend({}, $this._oRequestParams, {view: $this._getView(oView), afps_loading: 1}));
            });
        }

        $this._getForm(oForm);
    };

    this.loadingInButton($(oForm).children().find(':submit'), false);

    if(oData && oData.message != undefined)
        bx_alert(oData.message, fContinue);
    else
        fContinue();
};

BxTimelinePost.prototype.lockForm = function(oForm)
{
    if(this.isLockedForm(oForm))
        return;

    oForm.attr('bx_form_locked', 1).find('input[type="submit"],button[type="submit"]').addClass('bx-btn-disabled');
};

BxTimelinePost.prototype.unlockForm = function(oForm)
{
    if(!this.isLockedForm(oForm))
        return;

    oForm.removeAttr('bx_form_locked').find('input[type="submit"],button[type="submit"]').removeClass('bx-btn-disabled');
};

BxTimelinePost.prototype.isLockedForm = function(oForm)
{
    return oForm.attr('bx_form_locked') == 1;
};
    
BxTimelinePost.prototype.initFormAttachLink = function(sFormId)
{
    var $this = this;
    var oForm = $('#' + sFormId);

    oForm.ajaxForm({
        dataType: "json",
        clearForm: true,
        beforeSubmit: function (formData, jqForm, options) {
            window[$this._sObjName].beforeFormAttachLinkSubmit(oForm);
        },
        success: function (oData) {
            window[$this._sObjName].afterFormAttachLinkSubmit(oForm, oData);
        }
    });
};

BxTimelinePost.prototype.beforeFormAttachLinkSubmit = function(oForm)
{
    this.loadingInButton($(oForm).children().find(':submit'), true);
};

BxTimelinePost.prototype.afterFormAttachLinkSubmit = function (oForm, oData)
{
    var $this = this;
    var fContinue = function() {
        if(oData && oData.item != undefined) {
            $('#' + $this._aHtmlIds['attach_link_popup']).dolPopupHide({});

            if(!$.trim(oData.item).length)
                return;

            //--- Mark that 'attach link' process was finished.
            $this._oAttachedLinks[oData.url] = oData.id;

            var iEventId = 0;
            if(oData && oData.event_id != undefined)
                iEventId = parseInt(oData.event_id);

            var oItem = $(oData.item).hide();
            $('#' + $this._aHtmlIds['attach_link_form_field'] + iEventId).prepend(oItem).find('#' + oItem.attr('id')).bx_anim('show', $this._sAnimationEffect, $this._sAnimationSpeed, function() {
                $(this).bxProcessHtml();
            });

            $this.unlockForm($('#' + $this._aHtmlIds['attach_link_form_field'] + iEventId).parents('form:first'));
            return;
        }

        if(oData && oData.form != undefined && oData.form_id != undefined) {
            $('#' + oData.form_id).replaceWith(oData.form);
            $this.initFormAttachLink(oData.form_id);

            return;
        }
    };

    this.loadingInButton($(oForm).find(':submit'), false);

    if(oData && oData.message != undefined)
        bx_alert(oData.message, fContinue);
    else
        fContinue();
};

BxTimelinePost.prototype.deleteAttachLink = function(oLink, iId)
{
    var $this = this;
    var oData = this._getDefaultData();
    oData['id'] = iId;

    var oAttachLink = $('#' + this._aHtmlIds['attach_link_item'] + iId);
    bx_loading(oAttachLink, true);
    
    jQuery.post (
        this._sActionsUrl + 'delete_attach_link/',
        oData,
        function(oData) {
            var fContinue = function() {
                if(oData && oData.code != undefined && oData.code == 0) {
                    oAttachLink.bx_anim('hide', $this._sAnimationEffect, $this._sAnimationSpeed, function() {
                        $(this).remove;
                    });
                }
            };

            bx_loading(oAttachLink, false);

            if(oData && oData.message != undefined)
                bx_alert(oData.message, fContinue);
            else
                    fContinue();
        },
        'json'
    );

    return false;
};

BxTimelinePost.prototype.addAttachLink = function(oElement, sUrl)
{
    if(!sUrl || (this._iLimitAttachLinks != 0 && Object.keys(this._oAttachedLinks).length > this._iLimitAttachLinks))
        return;

    var $this = this;
    var oData = this._getDefaultData();
    oData['url'] = sUrl;

    jQuery.post (
        this._sActionsUrl + 'add_attach_link/',
        oData,
        function(oData) {
            if(!oData.id || !oData.item || !$.trim(oData.item).length)
                return;

            //--- Mark that 'attach link' process was finished.
            $this._oAttachedLinks[sUrl] = oData.id;

            var iEventId = 0;
            if(oData && oData.event_id != undefined)
                iEventId = parseInt(oData.event_id);

            var oItem = $(oData.item).hide();
            $('#' + $this._aHtmlIds['attach_link_form_field'] + iEventId).prepend(oItem).find('#' + oItem.attr('id')).bx_anim('show', $this._sAnimationEffect, $this._sAnimationSpeed);

            $this.unlockForm($('#' + $this._aHtmlIds['attach_link_form_field'] + iEventId).parents('form:first'));
        },
        'json'
    );
};

BxTimelinePost.prototype.showAttachLink = function(oLink, iEventId)
{
    if(this._iLimitAttachLinks != 0 && Object.keys(this._oAttachedLinks).length >= this._iLimitAttachLinks) {
        bx_alert(this._sLimitAttachLinksErr);
        return false;
    }

    var oData = this._getDefaultData();
    oData['event_id'] = iEventId;

    $(window).dolPopupAjax({
        id: {value: this._aHtmlIds['attach_link_popup'], force: true},
        url: bx_append_url_params(this._sActionsUri + 'get_attach_link_form/', oData),
        closeOnOuterClick: false,
        removeOnClose: true
    });

    return false;
};

BxTimelinePost.prototype._getForm = function(oElement)
{
    var $this = this;
    var oData = this._getDefaultData();

    jQuery.post (
        this._sActionsUrl + 'get_post_form/',
        oData,
        function(oData) {
            if(oData && oData.form != undefined && oData.form_id != undefined) {
                $('#' + oData.form_id).replaceWith(oData.form);
                $this.initFormPost(oData.form_id);
            }
        },
        'json'
    );
};

BxTimelinePost.prototype._onGetPost = function(oData)
{
    var $this = this;
    var fContinue = function(oData) {
        if(!$.trim(oData.item).length) 
            return;

        var aTypes = Array.isArray(oData.type) ? oData.type : new Array(oData.type);
        aTypes.forEach(function(sType) {
            oData['type'] = sType;
            $this.oView = $($this._getHtmlId('main', oData));

            /*
             * For backward compatibility.
             * Current UPF: 'Post to Feed' and Timeline: 'Post to Feed' forms 
             * should work with an old Account Feed ('Owner and Connections') block.
             */
            if(!$this.oView.length && oData['type'] == 'feed') {
                oData['type'] = 'owner_and_connections';
                $this.oView = $($this._getHtmlId('main', oData));
            }

            var oLoadMore = $this.oView.find('.' + $this.sSP + '-load-more');
            if(!oLoadMore.is(':visible'))
                oLoadMore.show();

            var oEmpty = $this.oView.find('.' + $this.sSP + '-empty');
            if(oEmpty.is(':visible'))
                oEmpty.hide();

            var oContent = $(oData.item).bxProcessHtml();
            switch(oData.view) {
                case 'timeline':
                    var oItems = $this.oView.find('.' + $this.sClassItems);
                    var oDivider  = oItems.find('.' + $this.sClassDividerToday);
                    var bDivider = oDivider.length > 0;

                    if(bDivider && !oDivider.is(':visible'))
                        oDivider.show();

                    oContent.hide();

                    var oItem = bDivider ? oDivider.after(oContent).next('.' + $this.sClassItem + ':hidden') : oContent.prependTo(oItems);
                    oItem.bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                        $(this).find('.bx-tl-item-text .bx-tl-content').checkOverflowHeight($this.sSP + '-overflow', function(oElement) {
                            $this.onFindOverflow(oElement);
                        });

                        $this.initFlickity($this.oView);
                    });

                    if($this._sVideosAutoplay != 'off')
                        $this.initVideos($this.oView);
                    break;

                case 'outline':
                    $this.prependMasonry(oContent, function(oItems) {
                        $(oItems).find('.bx-tl-item-text .bx-tl-content').checkOverflowHeight($this.sSP + '-overflow', function(oElement) {
                            $this.onFindOverflow(oElement);
                        });

                        $this.initFlickity($this.oView);
                    });
                    break;
            }
        });
    };

    if(oData && oData.message != undefined && oData.message.length != 0)
        bx_alert(oData.message, function() {
                fContinue(oData);
        });
    else
        fContinue(oData);
};
