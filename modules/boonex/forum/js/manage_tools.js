/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

function BxForumManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxForumManageTools' : oOptions.sObjName;
}

BxForumManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxForumManageTools.prototype.constructor = BxForumManageTools;

/** @} */
