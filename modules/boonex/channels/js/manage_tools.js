/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Channels Channels
 * @indroup     UnaModules
 *
 * @{
 */

function BxCnlManageTools(oOptions)
{
    BxBaseModGroupsManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxCnlManageTools' : oOptions.sObjName;
}

BxCnlManageTools.prototype = Object.create(BxBaseModGroupsManageTools.prototype);
BxCnlManageTools.prototype.constructor = BxCnlManageTools;

/** @} */
