/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

function BxTasksManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxTasksManageTools' : oOptions.sObjName;
}

BxTasksManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxTasksManageTools.prototype.constructor = BxTasksManageTools;

/** @} */
