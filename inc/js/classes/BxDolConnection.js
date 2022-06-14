/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolConnection(options)
{
    this._sObjName = undefined == options.sObjName ? 'oConnection' : options.sObjName; // javascript object name, to run current object instance from onTimer
    this._sSystem = options.sSystem; // current comment system
    this._iObjId = options.iObjId; // this object id comments
    this._sContentType = options.sContentType; 
    this._bIsMutual = options.bIsMutual; 
    
    this._sActionsUri = 'conn.php';
    this._sActionsUrl = options.sRootUrl + this._sActionsUri; // actions url address

    this._sAnimationEffect = 'fade';
    this._iAnimationSpeed = 'slow';
    this._sSP = undefined == options.sStylePrefix ? 'bx-view' : options.sStylePrefix;
    this._aHtmlIds = options.aHtmlIds;
}

BxDolConnection.prototype.toggleByPopup = function(oLink) {
    var $this = this;
    var oData = this._getDefaultParams();
    oData['act'] = 'GetConnected';

    $(oLink).dolPopupAjax({
        id: this._aHtmlIds['by_popup'], 
        url: bx_append_url_params(this._sActionsUri, oData)
    });
};

BxDolConnection.prototype.getUsers = function(oLink, iStart, iPerPage) {
    var $this = this;
    var oData = this._getDefaultParams();
    oData['act'] = 'GetUsers';
    oData['fmt'] = 'json';
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

BxDolConnection.prototype.onGetUsers = function(oData) {
    if(!oData.content)
        return;

    $(oData.source).parents('.bx-popup-content-wrapped:first').html(oData.content);
};

BxDolConnection.prototype._loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);
};

BxDolConnection.prototype._loadingInPopup = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-popup-content:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolConnection.prototype._getDefaultParams = function() {
    var oDate = new Date();
    return {
        obj: this._sSystem,
        id: this._iObjId,
        mutual: this._bIsMutual,
        content_type: this._sContentType,
        _t: oDate.getTime(),
        fmt: 'html'
    };
};

/** @} */
