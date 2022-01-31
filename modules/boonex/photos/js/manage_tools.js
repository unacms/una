/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPhotosManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxPhotosManageTools' : oOptions.sObjName;
}

BxPhotosManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxPhotosManageTools.prototype.constructor = BxPhotosManageTools;

/** @} */
