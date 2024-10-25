/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 *
 * @{
 */

function BxGroupsMain(oOptions) {
    BxBaseModGroupsMain.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxGroupsMain' : oOptions.sObjName;
}

BxGroupsMain.prototype = Object.create(BxBaseModGroupsMain.prototype);
BxGroupsMain.prototype.constructor = BxGroupsMain;

/** @} */
