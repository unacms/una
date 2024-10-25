/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

function BxCoursesMain(oOptions) {
    BxBaseModGroupsMain.call(this, oOptions);
    
    this._sObjName = oOptions.sObjName == undefined ? 'oBxCoursesMain' : oOptions.sObjName;
}

BxCoursesMain.prototype = Object.create(BxBaseModGroupsMain.prototype);
BxCoursesMain.prototype.constructor = BxCoursesMain;

/** @} */
