/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

function BxTimelinePolls(oOptions)
{
    BxBaseModGeneralPolls.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxTimelinePolls' : oOptions.sObjName;
}

BxTimelinePolls.prototype = Object.create(BxBaseModGeneralPolls.prototype);
BxTimelinePolls.prototype.constructor = BxTimelinePolls;

/** @} */
