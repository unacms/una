/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

function BxEventsPrices(oOptions) {
    BxBaseModGroupsPrices.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxEventsPrices' : oOptions.sObjName;
}

BxEventsPrices.prototype = Object.create(BxBaseModGroupsPrices.prototype);
BxEventsPrices.prototype.constructor = BxEventsPrices;

/** @} */
