/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPostsLinks(oOptions)
{
    BxBaseModTextLinks.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxPostsLinks' : oOptions.sObjName;
}

BxPostsLinks.prototype = Object.create(BxBaseModTextLinks.prototype);
BxPostsLinks.prototype.constructor = BxPostsLinks;

/** @} */
