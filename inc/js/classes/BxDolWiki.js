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
    this._sCurrentLang = this._oOptions['lang'];
    this._oTranslations = {};
    this.bindEvents();    
}

BxDolWiki.prototype.bindEvents = function () {
    var self = this;
    var oEvents = {
        "edit": "onEdit",
        "delete-version": "onDeleteVersion",
        "delete-block": "onDeleteBlock",
        "translate": "onTranslate",
        "history": "onHistory",
    }
    $.each(oEvents, function (sSel, sFunc) {
        $(self._eCont).find(".bx-wiki-controls-menu a." + sSel).on('click', {wiki_obj:self}, function() {
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
    $(eForm).find("textarea[name=content],input[name=lang],button[type=submit]").prop('disabled', b);
}

BxDolWiki.prototype.onChangeLangSelector = function (e) {
    var self = this;
    var eContent = $("#bx-wiki-form-" + this._oOptions.block_id + " textarea[name=content]");
    var sLangNew = $(e).val();

    // save current translation
    this._oTranslations[this._sCurrentLang] = eContent.val();

    if ('undefined' === typeof(this._oTranslations[sLangNew])) {
        // no saved translation was found - get from server
        self.loadingForm(true);
        var sUrl = bx_append_url_params(sUrlRoot + 'r.php?_q=' + this._oOptions.wiki_action_uri + '-action/get-translation', {
            block_id: this._oOptions.block_id,
            lang: sLangNew,
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
    this.popup('translate');
};

BxDolWiki.prototype.onEdit = function () {
    this.popup('edit');
};

BxDolWiki.prototype.onDeleteVersion = function () {
    this.popup('delete-version');
};

BxDolWiki.prototype.onDeleteBlock = function () {
    if (!confirm(this._oOptions.t_confirm_block_deletion))
        return;

    var self = this;
    $.post(this._sActionUrl + 'delete-block', {block_id: this._oOptions.block_id}, function (oData) {
        self.processResponce(oData);
    }, 'json');
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
    alert(oResponce.msg);
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
    loadDynamicBlock(oResponce.block_id, document.location.href);
}

BxDolWiki.prototype.popup = function (sAction) {
    
    var self = this;
    var sActionUrl = bx_append_url_params(this._sActionUrl + sAction, {
        block_id: this._oOptions.block_id
    });

    // remove previous popup
    this.removePopup();

    // show new popup
    $(window).dolPopupAjax({url: sActionUrl, closeOnOuterClick: false});
};

/** @} */
