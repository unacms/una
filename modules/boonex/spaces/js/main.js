/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Spaces Spaces
 * @ingroup     UnaModules
 *
 * @{
 */

function BxSpacesMain(oOptions) {
    BxBaseModGroupsMain.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxSpacesMain' : oOptions.sObjName;
}

BxSpacesMain.prototype = Object.create(BxBaseModGroupsMain.prototype);
BxSpacesMain.prototype.constructor = BxSpacesMain;

/** @} */
