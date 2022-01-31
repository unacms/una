/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

function BxCoursesManageTools(oOptions)
{
    BxBaseModGroupsManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxCoursesManageTools' : oOptions.sObjName;
}

BxCoursesManageTools.prototype = Object.create(BxBaseModGroupsManageTools.prototype);
BxCoursesManageTools.prototype.constructor = BxCoursesManageTools;

/** @} */
