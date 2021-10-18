/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Stream Stream
 * @ingroup     UnaModules
 *
 * @{
 */

function BxStrmPolls(oOptions)
{
    BxBaseModTextPolls.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxStrmPolls' : oOptions.sObjName;
}

BxStrmPolls.prototype = Object.create(BxBaseModTextPolls.prototype);
BxStrmPolls.prototype.constructor = BxStrmPolls;

/** @} */
