/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolAuditManageTools(oOptions) {
    this._iSearchTimeoutId = false;
    this._sObjNameGrid = oOptions.sObjNameGrid;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDolAuditManageTools' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

}

BxDolAuditManageTools.prototype.onChangeFilter = function (oFilter) {
    var $this = this;
    
    var oFilter1 = $('#bx-grid-module-' + this._sObjNameGrid);
    var sValueFilter1 = oFilter1.length > 0 ? oFilter1.val() : '';

    var oFilterProfile = $('#bx-grid-profile-' + this._sObjNameGrid);
    var sValueFilterProfile = oFilterProfile.length > 0 ? oFilterProfile.val() : '';

    var oFilterAction = $('#bx-grid-action-' + this._sObjNameGrid);
    var sValueFilterAction = oFilterAction.length > 0 ? oFilterAction.val() : '';

    var oFilteFromDate = $('#bx-grid-from_date-' + this._sObjNameGrid);
    var sValueFilterFromDate = oFilteFromDate.length > 0 ? oFilteFromDate.val() : '';

    var oFilteToDate = $('#bx-grid-to_date-' + this._sObjNameGrid);
    var sValueFilterToDate = oFilteToDate.length > 0 ? oFilteToDate.val() : '';

    var oSearch = $('#bx-grid-search-' + this._sObjNameGrid);
    var sValueSearch = oSearch.length > 0 ? oSearch.val() : '';
    if (sValueSearch == _t('_sys_grid_search'))
        sValueSearch = '';

    clearTimeout($this._iSearchTimeoutId);
    $this._iSearchTimeoutId = setTimeout(function () {
        glGrids[$this._sObjNameGrid].setFilter(sValueFilter1 + $this._sParamsDivider + sValueFilterProfile + $this._sParamsDivider + sValueFilterAction + $this._sParamsDivider + sValueFilterFromDate + $this._sParamsDivider + sValueFilterToDate + $this._sParamsDivider + sValueSearch, true);
    }, 500);
};
/** @} */
