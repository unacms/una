/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

function BxBaseModTextPolls(oOptions)
{
    BxBaseModGeneralPolls.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxBaseModTextPolls' : oOptions.sObjName;
}

BxBaseModTextPolls.prototype = Object.create(BxBaseModGeneralPolls.prototype);
BxBaseModTextPolls.prototype.constructor = BxBaseModTextPolls;

/** @} */
