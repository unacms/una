/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

function BxCoursesEntry(oOptions) {
    BxBaseModGroupsEntry.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxCoursesEntry' : oOptions.sObjName;
}

BxCoursesEntry.prototype = Object.create(BxBaseModGroupsEntry.prototype);
BxCoursesEntry.prototype.constructor = BxCoursesEntry;

BxCoursesEntry.prototype.passNode = function(oElement, iNodeId) {
    var $this = this;
    var oParams = this._getDefaultData();
    oParams['node_id'] = iNodeId;

    if(oElement)
        this.loadingInButton(oElement, true);

    jQuery.get (
        this._sActionsUrl + 'pass_node',
        oParams,
        function(oData) {
            if(oElement)
                $this.loadingInButton(oElement, false);

            processJsonData(oData);
        },
        'json'
    );

    return false;
};

BxCoursesEntry.prototype.passData = function(oElement, iDataId) {
    var $this = this;
    var oParams = this._getDefaultData();
    oParams['data_id'] = iDataId;

    if(oElement)
        this.loadingInButton(oElement, true);

    jQuery.get (
        this._sActionsUrl + 'pass_data',
        oParams,
        function(oData) {
            if(oElement)
                $this.loadingInButton(oElement, false);

            processJsonData(oData);
        },
        'json'
    );

    return false;
};

/** @} */
