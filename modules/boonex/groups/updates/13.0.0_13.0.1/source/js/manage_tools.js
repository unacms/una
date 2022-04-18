/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 *
 * @{
 */

function BxGroupsManageTools(oOptions)
{
    BxBaseModGroupsManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxGroupsManageTools' : oOptions.sObjName;
}

BxGroupsManageTools.prototype = Object.create(BxBaseModGroupsManageTools.prototype);
BxGroupsManageTools.prototype.constructor = BxGroupsManageTools;

/** @} */
