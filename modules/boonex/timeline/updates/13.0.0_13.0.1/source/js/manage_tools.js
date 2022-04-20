/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

function BxTimelineManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'BxTimelineManageTools' : oOptions.sObjName;
}

BxTimelineManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxTimelineManageTools.prototype.constructor = BxTimelineManageTools;

/** @} */
