/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

function BxOrgsMain(oOptions) {
    BxBaseModGroupsMain.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxOrgsMain' : oOptions.sObjName;
}

BxOrgsMain.prototype = Object.create(BxBaseModGroupsMain.prototype);
BxOrgsMain.prototype.constructor = BxOrgsMain;

/** @} */
