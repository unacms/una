/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 *
 * @{
 */


function BxGroupsPrices(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjNameGrid = oOptions.sObjNameGrid;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxGroupsPrices' : oOptions.sObjName;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxGroupsPrices.prototype.onChangeRole = function() {
	this.reloadGrid($('#bx-grid-level-' + this._sObjNameGrid).val());
};

BxGroupsPrices.prototype.reloadGrid = function(iRoleId) {
    if(glGrids[this._sObjNameGrid]._oQueryAppend['role_id'] == iRoleId)
        return;

    glGrids[this._sObjNameGrid]._oQueryAppend['role_id'] = iRoleId;
    glGrids[this._sObjNameGrid].reload(0);
};

/** @} */
