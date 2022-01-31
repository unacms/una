/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Stream Stream
 * @ingroup     UnaModules
 *
 * @{
 */

function BxStrmManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxStrmManageTools' : oOptions.sObjName;
}

BxStrmManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxStrmManageTools.prototype.constructor = BxStrmManageTools;

/** @} */
