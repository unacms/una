/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

function BxClssPolls(oOptions)
{
    BxBaseModTextPolls.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxClssPolls' : oOptions.sObjName;
}

BxClssPolls.prototype = Object.create(BxBaseModTextPolls.prototype);
BxClssPolls.prototype.constructor = BxClssPolls;

/** @} */
