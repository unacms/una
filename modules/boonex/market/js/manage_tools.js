/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

function BxMarketManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxMarketManageTools' : oOptions.sObjName;
}

BxMarketManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxMarketManageTools.prototype.constructor = BxMarketManageTools;

/** @} */
