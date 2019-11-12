/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPostsPolls(oOptions)
{
    BxBaseModTextPolls.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxPostsPolls' : oOptions.sObjName;
}

BxPostsPolls.prototype = Object.create(BxBaseModTextPolls.prototype);
BxPostsPolls.prototype.constructor = BxPostsPolls;

/** @} */
