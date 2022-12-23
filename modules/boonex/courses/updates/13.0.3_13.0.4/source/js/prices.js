/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

function BxCoursesPrices(oOptions) {
    BxBaseModGroupsPrices.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxCoursesPrices' : oOptions.sObjName;
}

BxCoursesPrices.prototype = Object.create(BxBaseModGroupsPrices.prototype);
BxCoursesPrices.prototype.constructor = BxCoursesPrices;

/** @} */
