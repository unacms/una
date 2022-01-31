/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

function BxFilesManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxFilesManageTools' : oOptions.sObjName;
}

BxFilesManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxFilesManageTools.prototype.constructor = BxFilesManageTools;

/** @} */
