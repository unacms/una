/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolView(options)
{
    this._sObjName = undefined == options.sObjName ? 'oView' : options.sObjName; // javascript object name, to run current object instance from onTimer
    this._sSystem = options.sSystem; // current comment system
    this._iAuthorId = options.iAuthorId; // this comment's author ID.
    this._iObjId = options.iObjId; // this object id comments

    this._sActionsUri = 'view.php';
    this._sActionsUrl = options.sRootUrl + this._sActionsUri; // actions url address

    this._sAnimationEffect = 'fade';
    this._iAnimationSpeed = 'slow';
    this._sSP = undefined == options.sStylePrefix ? 'bx-view' : options.sStylePrefix;
    this._aHtmlIds = options.aHtmlIds;
}

BxDolView.prototype.toggleByPopup = function(oLink) {
    var $this = this;
    var oData = this._getDefaultParams();
    oData['action'] = 'GetViewedBy';

    $(oLink).dolPopupAjax({
        id: this._aHtmlIds['by_popup'], 
        url: bx_append_url_params(this._sActionsUri, oData)
    });
};

BxDolView.prototype.getUsers = function(oLink, iStart, iPerPage) {
    var $this = this;
    var oData = this._getDefaultParams();
    oData['action'] = 'GetUsers';
    oData['start'] = iStart;
    oData['per_page'] = iPerPage;

    this._loadingInPopup(oLink, true);

    $.get(
        this._sActionsUri,
        oData,
        function(oData) {
            $this._loadingInPopup(oLink, false);

            oData.source = oLink;
            processJsonData(oData);
        },
        'json'
    );
};

BxDolView.prototype.onGetUsers = function(oData) {
    if(!oData.content)
        return;

    $(oData.source).parents('.bx-popup-content-wrapped:first').html(oData.content);
};

BxDolView.prototype._loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);
};

BxDolView.prototype._loadingInPopup = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-popup-content:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolView.prototype._getDefaultParams = function() {
    var oDate = new Date();
    return {
        sys: this._sSystem,
        id: this._iObjId,
        _t: oDate.getTime()
    };
};

/** @} */
