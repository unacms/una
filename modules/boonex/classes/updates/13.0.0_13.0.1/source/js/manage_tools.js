/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

function BxClssManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxClssManageTools' : oOptions.sObjName;
}

BxClssManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxClssManageTools.prototype.constructor = BxClssManageTools;

/** @} */
