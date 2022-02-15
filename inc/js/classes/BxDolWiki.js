/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolWiki (sObject, eContainer, oOptions) {
    var oDefault = {};
    this._oOptions = $.extend({}, oDefault, oOptions);
    this._sObject = sObject;
    this._eCont = eContainer;
    this._sActionUrl = sUrlRoot + 'r.php?_q=' + this._oOptions.wiki_action_uri + '-action/';
    this._sCurrentLang = this._oOptions['language'];
    this._oTranslations = {};
}

BxDolWiki.prototype.bindEvents = function () {
    var self = this;
    var e = $('#bx-popup-ajax-wrapper-bx-wiki-menu-' + this._oOptions.block_id);
    var oEvents = {
        "edit": "onEdit",
        "delete-version": "onDeleteVersion",
        "delete-block": "onDeleteBlock",
        "translate": "onTranslate",
        "history": "onHistory",
    }
    $.each(oEvents, function (sSel, sFunc) {
        e.find(".bx-menu-item a." + sSel).on('click', {wiki_obj:self}, function() {
            $(".bx-popup-active").dolPopupHide(); // close menu popup
            BxDolWiki.prototype[sFunc].apply(self);
            return false;
        });
    });
};

BxDolWiki.prototype.removePopup = function (iBlockId) {
    if ('undefined' === typeof(iBlockId))
        iBlockId = this._oOptions.block_id;
    $("#bx-wiki-form-" + iBlockId).parents(".bx-popup-wrapper").remove(); 
    $(".bx-wiki-history").parents(".bx-popup-wrapper").remove();
    $(".bx-wiki-del-rev").parents(".bx-popup-wrapper").remove();
}

BxDolWiki.prototype.getTranslations = function () {
    return this._oTranslations;
}

BxDolWiki.prototype.loadingForm = function (b) {
    bx_loading($('#bx-form-element-content'), b);
    var eForm = $("#bx-wiki-form-" + this._oOptions.block_id);
    if (b)
        $(eForm).find("textarea[name=content]").val('');
    $(eForm).find("textarea[name=content],input[name=language],button[type=submit]").prop('disabled', b);
}

BxDolWiki.prototype.onChangeLangSelector = function (e) {
    var self = this;
    var eContent = $("#bx-wiki-form-" + this._oOptions.block_id + " textarea[name=content]");
    var sLangNew = $(e).val();

    // save current translation
    if (null !== this._sCurrentLang)
        this._oTranslations[this._sCurrentLang] = eContent.val();

    if ('undefined' === typeof(this._oTranslations[sLangNew])) {
        // no saved translation was found - get from server
        self.loadingForm(true);
        var sUrl = bx_append_url_params(sUrlRoot + 'r.php?_q=' + this._oOptions.wiki_action_uri + '-action/get-translation', {
            block_id: this._oOptions.block_id,
            language: sLangNew,
        });
        $.getJSON(sUrl, function (oData) {
            if ('undefined' !== typeof(oData.content))
                eContent.val(oData.content);
            // save current language
            self._sCurrentLang = sLangNew;
            self.loadingForm(false);
        });
    } 
    else {
        // saved translation was found - switch to it immediately
        eContent.val(this._oTranslations[sLangNew]);
        // save current language
        this._sCurrentLang = sLangNew;
    }
};

BxDolWiki.prototype.onTranslate = function () {
    this._sCurrentLang = this._oOptions['language'];
    this._oTranslations = {};
    this.popup('translate');
};

BxDolWiki.prototype.onEdit = function () {
    this._sCurrentLang = this._oOptions['language'];
    this._oTranslations = {};
    this.popup('edit');
};

BxDolWiki.prototype.onDeleteVersion = function () {
    this.popup('delete-version');
};

BxDolWiki.prototype.onDeleteBlock = function () {
    var self = this;
    bx_confirm(this._oOptions.t_confirm_block_deletion, function () {
        $.post(self._sActionUrl + 'delete-block', {block_id: self._oOptions.block_id}, function (oData) {
            self.processResponce(oData);
        }, 'json');
    });
};

BxDolWiki.prototype.onHistory = function () {
    this.popup('history');
};

BxDolWiki.prototype.processResponce = function (oResponce) {
    var self = this;

    if ('undefined' === typeof(oResponce.actions))
        return;

    if ('string' === typeof(oResponce.actions))
        oResponce.actions = [oResponce.actions];

    if ('object' === typeof(oResponce.actions)) {
        $.each(oResponce.actions, function () {
            if ('function' == typeof(self['action' + this]))
                self['action' + this](oResponce);
        });
    }
}

BxDolWiki.prototype.actionShowMsg = function (oResponce) {
    bx_alert(oResponce.msg);
}

BxDolWiki.prototype.actionClosePopup = function (oResponce) {
    var self = this;
    if ($(".bx-popup-active").size())
        $(".bx-popup-active").dolPopupHide({onHide:function (){
            self.removePopup();
        }})
    else
        this.removePopup();
}
BxDolWiki.prototype.actionReload = function (oResponce) {
    var l = document.location;
    var ePage = $('#bx-page-block-' + oResponce.block_id).parents('.bx-layout-wrapper:first');
    var sUrl = l.hash.length ? l.href.replace(l.hash, '') : l.href;
    if (ePage.size())
        sUrl = sUrlRoot + 'page.php?i=' + ePage.attr('id').replace('bx-page-', ''); 
    loadDynamicBlock(oResponce.block_id, sUrl);
}

BxDolWiki.prototype.actionDeleteBlock = function (oResponce) {
    $('#bx-page-block-' + oResponce.block_id).remove();
}

BxDolWiki.prototype.popup = function (sAction) {
    
    var self = this;
    var sActionUrl = bx_append_url_params(this._sActionUrl + sAction, {
        block_id: this._oOptions.block_id,
        language: this._sCurrentLang,
    });

    // remove previous popup
    this.removePopup();

    // show new popup
    $(window).dolPopupAjax({url: sActionUrl, closeOnOuterClick: false});
};

function bx_wiki_add_block(e, sPage, iCellId, sActionUri) {
    var sActionUrl = sUrlRoot + 'r.php?_q=' + sActionUri + '-action/';
    bx_loading($(e), true);
    $.post(sActionUrl + 'add', {page: sPage, cell_id: iCellId}, function (oData) {
        bx_loading($(e), false);
        if ('undefined' !== typeof(oData.code)) {
            if (0 == oData.code) {
                $(e).parents('.bx-wiki-add-block').before('<div class="bx-page-block-container bx-def-padding-sec-topbottom" id="bx-page-block-' + oData.block_id + '">');
                loadDynamicBlock(oData.block_id, document.location.href);
            }
            else {
                bx_alert(oData.msg);
            }
        }
    }, 'json');
}

function bx_wiki_insert_img (sEditorId, sImgUrl, sImgName) {
    var s = "![" + sImgName + "](" + sImgUrl + ")";
    var e = $(sEditorId).size() ? $(sEditorId).get(0) : false;
    
    if (!e)
        return;

    if (e.setRangeText) {
        e.setRangeText(s);
    } 
    else {
        e.focus();
        document.execCommand('insertText', false, s);
    }
}

function bx_wiki_remove_img (sEditorId, sImgUrl, sImgName) {
    var sVal = $(sEditorId).val();
    if (!sVal)
        return;

    var sImg = "![IMGNAMEHERE](" + sImgUrl + ")";
    sImg = bx_regexp_escape(sImg);
    sImg = sImg.replace(/IMGNAMEHERE/, '(.*?)');

    var re = new RegExp(sImg, 'gm');
    sVal = sVal.replace(re, '');

    $(sEditorId).val(sVal);
}

function bx_wiki_open_editor(sSel, bBackground) {
    const e = $(sSel).size() ? $(sSel)[0] : false;
    if (!e)
        return;

    bx_wiki_open_editor_convert_links(e.value, function (s) {
        // Open the iframe
        const oStackedit = new Stackedit();
        oStackedit.openFile({
            content: {
                text: s // and the Markdown content.
            }
        }, 'undefined' === typeof(bBackground) ? false : bBackground);

        // Listen to StackEdit events and apply the changes to the textarea.
        oStackedit.on('fileChange', (file) => {
            e.value = bx_wiki_open_editor_convert_links_back(file.content.text);
        });
    });
}

function bx_wiki_open_editor_convert_links(s, onSuccess) {
    var sActionUrl = sUrlRoot + 'r.php?_q=wiki-action/';
    $.post(sActionUrl + 'convert-links', {s: s}, function (oData) {
        if ('undefined' !== typeof(oData.s))
            onSuccess(oData.s);
    }, 'json');
}

function bx_wiki_open_editor_convert_links_back(s) {
    
    var re = new RegExp('\\\(' + bx_regexp_escape(sUrlRoot) + 's/([a-zA-Z0-9_]+)/([a-zA-Z0-9]+)[^\\\)]*\\\)', 'gm');
    s = s.replace(re, "($1/$2)");

    var sRefs = '<!-- ' + $('<textarea />').html(_t('_sys_wiki_external_editor_references_comment')).text() + ' -->';
    var i = s.indexOf(sRefs);
    if (-1 !== i)
        s = s.substring(0, i);

    return s;
}

/** @} */
