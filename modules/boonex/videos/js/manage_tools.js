/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

function BxVideosManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxVideosManageTools' : oOptions.sObjName;
}

BxVideosManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxVideosManageTools.prototype.constructor = BxVideosManageTools;

/** @} */
