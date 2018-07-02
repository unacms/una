/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

function BxVideosListTools(oOptions) {
    this._sContainerId = oOptions.sContainerId == undefined ? 'opopup' : oOptions.sContainerId;
}

BxVideosListTools.prototype.cmtAdd = function () {
    var $this = this;
    var oDate = new Date();
    $(window).dolPopupAjax({
        id: $this._sContainerId,
        url: bx_append_url_params('/m/videos/ListCreate', { _t: oDate.getTime() }),
        closeOnOuterClick: true
    });
};

BxVideosListTools.prototype.reloadForm = function (data, form_id) {
    var $this = this;
    $('#' + form_id).closest('.bx-popup-content-wrapped').html(data);
};

/** @} */
