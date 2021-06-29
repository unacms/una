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

BxGroupsPrices.prototype.checkName = function(sNameId, iId) {
    var oDate = new Date();

    var oName = jQuery("[name='" + sNameId + "']");
    var sName = oName.val();

    if(!sName.length)
        return;

    jQuery.get(
        this._sActionsUrl + 'check_name',
        {
            name: sName,
            id: iId && parseInt(iId) > 0 ? iId : 0,
            _t: oDate.getTime()
        },
        function(oData) {
            if(!oData || oData.name == undefined)
                return;

            oName.val(oData.name);
        },
        'json'
    );
};

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
