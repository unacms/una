/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPollsManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxPollsManageTools' : oOptions.sObjName;
}

BxPollsManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxPollsManageTools.prototype.constructor = BxPollsManageTools;

/** @} */
