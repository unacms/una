/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

function BxForumPolls(oOptions)
{
    BxBaseModTextPolls.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxForumPolls' : oOptions.sObjName;
}

BxForumPolls.prototype = Object.create(BxBaseModTextPolls.prototype);
BxForumPolls.prototype.constructor = BxForumPolls;

/** @} */
