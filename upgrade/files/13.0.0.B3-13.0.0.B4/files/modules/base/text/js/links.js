/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

function BxBaseModTextLinks(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxBaseModTextLinks' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._iContentId = oOptions.iContentId == undefined ? 0 : oOptions.iContentId;
    this._sFormId = oOptions.sFormId == undefined ? 0 : oOptions.sFormId;
    this._sEditorId = oOptions.sEditorId == undefined ? '' : oOptions.sEditorId;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._iLimitAttachLinks = oOptions.iLimitAttachLinks == undefined ? 0 : oOptions.iLimitAttachLinks;
    this._sLimitAttachLinksErr = oOptions.sLimitAttachLinksErr == undefined ? '' : oOptions.sLimitAttachLinksErr;
    this._oAttachedLinks = oOptions.oAttachedLinks == undefined ? {} : oOptions.oAttachedLinks;
    
    this._sPregTag = "(<([^>]+bx-tag[^>]+)>)";
    this._sPregMention = "(<([^>]+bx-mention[^>]+)>)";
    this._sPregUrl = "(([A-Za-z]{3,9}:(?:\\/\\/)?)(?:[\\-;:&=\\+\\$,\\w]+@)?[A-Za-z0-9\\.\\-]+|(?:www\\.|[\\-;:&=\\+\\$,\\w]+@)[A-Za-z0-9\\.\\-]+)((?:\\/[\\+~%#\\/\\.\\w\\-_!\\(\\)]*)?\\??(?:[\\-\\+=&;%@\\.\\w_]*)#?(?:[\\.\\!\\/\\w]*))?";
    
    var $this = this;
    
    $(document).ready(function () {
        $this.initFormPost($this._sFormId);
    });
}

BxBaseModTextLinks.prototype.initFormPost = function(sFormId)
{
    var $this = this;
    var oForm = $('#' + sFormId);
    var oTextarea = oForm.find('textarea');

    if (typeof window.glOnSpaceEnterInEditor === 'undefined')
        window.glOnSpaceEnterInEditor = [];    

    window.glOnSpaceEnterInEditor.push(function (sData, sSelector) {
        sSelector = '#' + $this._sFormId + ' [name=text]';
        if(!oTextarea.is(sSelector))
            return;

        $this.parseContent(oForm, sData, true);
    });

    var sContent = oTextarea.val();
    if(sContent && sContent.length > 0)
        this.parseContent(oForm, sContent, false);

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

BxBaseModTextLinks.prototype.parseContent = function(oForm, sData, bPerformAttach)
{
    var oExp, aMatch = null;

    oExp = new RegExp(this._sPregTag , "ig");
    sData = sData.replace(oExp, '');

    oExp = new RegExp(this._sPregMention , "ig");
    sData = sData.replace(oExp, '');

    oExp = new RegExp(this._sPregUrl , "ig");
    while(aMatch = oExp.exec(sData)) {
        var sUrl = aMatch[0].replace(/^(\s|(&nbsp;))+|(\s|(&nbsp;))+$/gm,'');
        if(!sUrl.length || this._oAttachedLinks[sUrl] != undefined || (this._iLimitAttachLinks != 0 && Object.keys(this._oAttachedLinks).length >= this._iLimitAttachLinks))
            continue;

        //--- Mark that 'attach link' process was started.
        this._oAttachedLinks[sUrl] = 0;

        if(bPerformAttach)
            this.addAttachLink(oForm, sUrl);
    }
};

BxBaseModTextLinks.prototype.addAttachLink = function(oElement, sUrl)
{
    if(!sUrl || (this._iLimitAttachLinks != 0 && Object.keys(this._oAttachedLinks).length > this._iLimitAttachLinks))
        return;

    var $this = this;
    var oData = this._getDefaultData();
    oData['url'] = sUrl;
    oData['content_id'] = $this._iContentId;
    

    jQuery.post (
        this._sActionsUrl + 'add_attach_link/',
        oData,
        function(oData) {
            if(!oData.id || !oData.item || !$.trim(oData.item).length)
                return;

            //--- Mark that 'attach link' process was finished.
            $this._oAttachedLinks[sUrl] = oData.id;

            var iContentId = 0;
            if(oData && oData.content_id != undefined)
                iContentId = parseInt(oData.content_id);

            var oItem = $(oData.item).hide();
            $('#' + $this._aHtmlIds['attach_link_form_field'] + iContentId).prepend(oItem).find('#' + oItem.attr('id')).bx_anim('show', $this._sAnimationEffect, $this._sAnimationSpeed, function() {
                $(this).bxProcessHtml();
            });
        },
        'json'
    );
};

BxBaseModTextLinks.prototype.showAttachLink = function(oLink)
{
    if(this._iLimitAttachLinks != 0 && Object.keys(this._oAttachedLinks).length >= this._iLimitAttachLinks) {
        bx_alert(this._sLimitAttachLinksErr);
        return false;
    }

    var oData = this._getDefaultData();
    oData['content_id'] = this._iContentId;

    $(window).dolPopupAjax({
        id: {value: this._aHtmlIds['attach_link_popup'], force: true},
        url: bx_append_url_params(this._sActionsUri + 'get_attach_link_form/', oData),
        closeOnOuterClick: false,
        removeOnClose: true
    });

    return false;
};

BxBaseModTextLinks.prototype.initFormAttachLink = function(sFormId)
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

BxBaseModTextLinks.prototype.beforeFormAttachLinkSubmit = function(oForm)
{
    this.loadingInButton($(oForm).children().find(':submit'), true);
};

BxBaseModTextLinks.prototype.afterFormAttachLinkSubmit = function (oForm, oData)
{
    var $this = this;
    var fContinue = function() {
        if(oData && oData.item != undefined) {
            $('#' + $this._aHtmlIds['attach_link_popup']).dolPopupHide({});

            if(!$.trim(oData.item).length)
                return;

            var iContentId = 0;            
            if(oData && oData.content_id != undefined)
                iContentId = parseInt(oData.content_id);
            
            var oItem = $(oData.item).hide();
            $('#' + $this._aHtmlIds['attach_link_form_field'] + iContentId).prepend(oItem).find('#' + oItem.attr('id')).bx_anim('show', $this._sAnimationEffect, $this._sAnimationSpeed, function() {
                $(this).bxProcessHtml();
            });

            $this._oAttachedLinks[oData.url] = oData.id;
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

BxBaseModTextLinks.prototype.deleteAttachLink = function(oLink, iId)
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

BxBaseModTextLinks.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

BxBaseModTextLinks.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);	
};