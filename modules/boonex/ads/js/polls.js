/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAdsPolls(oOptions)
{
    BxBaseModTextPolls.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxAdsPolls' : oOptions.sObjName;
}

BxAdsPolls.prototype = Object.create(BxBaseModTextPolls.prototype);
BxAdsPolls.prototype.constructor = BxAdsPolls;

/** @} */
