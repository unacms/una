/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

function BxForumLinks(oOptions)
{
    BxBaseModTextLinks.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxForumLinks' : oOptions.sObjName;
}

BxForumLinks.prototype = Object.create(BxBaseModTextLinks.prototype);
BxForumLinks.prototype.constructor = BxForumLinks;

/** @} */
