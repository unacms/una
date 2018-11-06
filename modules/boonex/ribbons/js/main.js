/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

function BxRibbons(oOptions) {
    this._sContainerId = 'bx_ribbons_selector';
    this._sPopUpId = 'bx_ribbons_popup';
    this._sActionsUri = oOptions.sActionUri;
    this._iProfileId = oOptions.iProfileId;
    var $this = this;
}

BxRibbons.prototype.init = function (iProfileId) {
    this._iProfileId = iProfileId;
    var $this = this;
    showPopupAnyHtml($this._sActionsUri + 'getRibbons/' + $this._iProfileId + '/', $this._sPopUpId);
    $('#' + $this._sContainerId + ' button').click(function () {
        $this.saveRibbons();
    });
}

BxRibbons.prototype.saveRibbons = function () {   
    var $this = this;
    var allVals = [];
    $('#' + $this._sContainerId + ' input[type="checkbox"]:checked').each(function () {
        allVals.push($(this).val());
    });
    $.getJSON($this._sActionsUri + 'SetRibbons/' + $this._iProfileId + '/' + allVals + '/', function (aReports) {
        console.log($this._sPopUpId);
       // $('#' + $this._sPopUpId).remove();
        $('#' + $this._sPopUpId).dolPopupHide();
    });
};
