/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPostsManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxPostsManageTools' : oOptions.sObjName;
}

BxPostsManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxPostsManageTools.prototype.constructor = BxPostsManageTools;

/** @} */
