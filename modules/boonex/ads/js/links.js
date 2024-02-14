/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAdsLinks(oOptions)
{
    BxBaseModTextLinks.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxAdsLinks' : oOptions.sObjName;
}

BxAdsLinks.prototype = Object.create(BxBaseModTextLinks.prototype);
BxAdsLinks.prototype.constructor = BxAdsLinks;

/** @} */
