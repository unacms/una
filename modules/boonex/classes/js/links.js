/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

function BxClssLinks(oOptions)
{
    BxBaseModTextLinks.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxClssLinks' : oOptions.sObjName;
}

BxClssLinks.prototype = Object.create(BxBaseModTextLinks.prototype);
BxClssLinks.prototype.constructor = BxClssLinks;

/** @} */
