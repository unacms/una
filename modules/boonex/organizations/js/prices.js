/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

function BxOrgsPrices(oOptions) {
    BxBaseModGroupsPrices.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxOrgsPrices' : oOptions.sObjName;
}

BxOrgsPrices.prototype = Object.create(BxBaseModGroupsPrices.prototype);
BxOrgsPrices.prototype.constructor = BxOrgsPrices;

/** @} */
