/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 *
 * @{
 */

function BxGroupsPrices(oOptions) {
    BxBaseModGroupsPrices.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxGroupsPrices' : oOptions.sObjName;
}

BxGroupsPrices.prototype = Object.create(BxBaseModGroupsPrices.prototype);
BxGroupsPrices.prototype.constructor = BxGroupsPrices;

/** @} */
