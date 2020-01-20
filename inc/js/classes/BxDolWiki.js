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
}

BxDolWiki.prototype.onEdit = function () {

    var sActionUrl = bx_append_url_params(this._sActionUrl + 'edit', {
        block_id: this._oOptions.block_id
    });

    // remove previous popup
    this.removePopup();

    // show new popup
    $(window).dolPopupAjax({url: sActionUrl});
};

BxDolWiki.prototype.onDeleteVersion = function () {
    console.log("onDeleteVersion:" + this._sObject);
};

BxDolWiki.prototype.onDeleteBlock = function () {
    console.log("onDeleteBlock:" + this._sObject);
};

BxDolWiki.prototype.onHistory = function () {
    console.log("onHistory:" + this._sObject);
};

BxDolWiki.prototype.onTranslate = function () {
    console.log("onTranslate:" + this._sObject);
};

BxDolWiki.prototype.processResponce = function (oResponce) {
    if ('undefined' === typeof(oResponce.action))
        return;    
    if ('function' == typeof(this['action' + oResponce.action]))
        this['action' + oResponce.action](oResponce);
}

BxDolWiki.prototype.actionReload = function (oResponce) {
    var self = this;
    if ($(".bx-popup-active").size())
        $(".bx-popup-active").dolPopupHide({onHide:function (){
            self.removePopup();
        }})
    else
        this.removePopup();
    loadDynamicBlock(oResponce.block_id, document.location.href);
}

/** @} */
