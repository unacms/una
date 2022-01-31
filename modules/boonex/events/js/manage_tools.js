/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

function BxEventsManageTools(oOptions)
{
    BxBaseModGroupsManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxEventsManageTools' : oOptions.sObjName;
}

BxEventsManageTools.prototype = Object.create(BxBaseModGroupsManageTools.prototype);
BxEventsManageTools.prototype.constructor = BxEventsManageTools;

/** @} */
