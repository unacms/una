/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Snipcart Snipcart
 * @ingroup     UnaModules
 *
 * @{
 */

function BxSnipcartManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxSnipcartManageTools' : oOptions.sObjName;
}

BxSnipcartManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxSnipcartManageTools.prototype.constructor = BxSnipcartManageTools;

/** @} */
