/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

function BxEventsMain(oOptions) {
    BxBaseModGroupsMain.call(this, oOptions);
    
    this._sObjName = oOptions.sObjName == undefined ? 'oBxEventsMain' : oOptions.sObjName;
}

BxEventsMain.prototype = Object.create(BxBaseModGroupsMain.prototype);
BxEventsMain.prototype.constructor = BxEventsMain;

/** @} */
