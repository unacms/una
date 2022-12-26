/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Spaces Spaces
 * @ingroup     UnaModules
 *
 * @{
 */

function BxSpacesPrices(oOptions) {
    BxBaseModGroupsPrices.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxSpacesPrices' : oOptions.sObjName;
}

BxSpacesPrices.prototype = Object.create(BxBaseModGroupsPrices.prototype);
BxSpacesPrices.prototype.constructor = BxSpacesPrices;

/** @} */
