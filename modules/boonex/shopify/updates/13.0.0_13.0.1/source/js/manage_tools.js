/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Shopify Shopify
 * @ingroup     UnaModules
 *
 * @{
 */

function BxShopifyManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxShopifyManageTools' : oOptions.sObjName;
}

BxShopifyManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxShopifyManageTools.prototype.constructor = BxShopifyManageTools;

/** @} */
