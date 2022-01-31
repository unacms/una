/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Albums Albums
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAlbumsManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxAlbumsManageTools' : oOptions.sObjName;
}

BxAlbumsManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxAlbumsManageTools.prototype.constructor = BxAlbumsManageTools;

/** @} */
