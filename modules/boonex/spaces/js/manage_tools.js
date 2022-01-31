/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Spaces Spaces
 * @indroup     UnaModules
 *
 * @{
 */

function BxSpacesManageTools(oOptions)
{
    BxBaseModGroupsManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxSpacesManageTools' : oOptions.sObjName;
}

BxSpacesManageTools.prototype = Object.create(BxBaseModGroupsManageTools.prototype);
BxSpacesManageTools.prototype.constructor = BxSpacesManageTools;

/** @} */
