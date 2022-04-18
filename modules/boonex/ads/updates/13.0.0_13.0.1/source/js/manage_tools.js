/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAdsManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxAdsManageTools' : oOptions.sObjName;
}

BxAdsManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxAdsManageTools.prototype.constructor = BxAdsManageTools;

/** @} */
