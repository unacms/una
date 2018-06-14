/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolCmtsManageTools(oOptions) {
    this._iSearchTimeoutId = false;
    this._sObjNameGrid = oOptions.sObjNameGrid;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDolCmtsManageTools' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxDolCmtsManageTools.prototype.onChangeFilter = function (oFilter) {
    var $this = this;
    var oFilter1 = $('#bx-grid-filter1-' + this._sObjNameGrid);
    var sValueFilter1 = oFilter1.length > 0 ? oFilter1.val() : '';

    var oSearch = $('#bx-grid-search-' + this._sObjNameGrid);
    var sValueSearch = oSearch.length > 0 ? oSearch.val() : '';
    if (sValueSearch == _t('_sys_grid_search'))
        sValueSearch = '';

    clearTimeout($this._iSearchTimeoutId);
    $this._iSearchTimeoutId = setTimeout(function () {
        glGrids[$this._sObjNameGrid].setFilter(sValueFilter1 + $this._sParamsDivider + sValueSearch, true);
    }, 500);
};

BxDolCmtsManageTools.prototype.onClickDelete = function (iContentId) {
    $('.bx-popup-applied:visible').dolPopupHide();

    glGrids[this._sObjNameGrid].actionWithId(iContentId, 'delete', {}, '', false, 1);
};



/** @} */
