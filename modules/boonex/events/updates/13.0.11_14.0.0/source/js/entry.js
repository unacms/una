/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

function BxEventsEntry(oOptions) {
    BxBaseModGroupsEntry.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxEventsEntry' : oOptions.sObjName;

    var $this = this;
    $(document).ready(function() {
        $this.init();
    });
}

BxEventsEntry.prototype = Object.create(BxBaseModGroupsEntry.prototype);
BxEventsEntry.prototype.constructor = BxEventsEntry;

BxEventsEntry.prototype.init = function() {
    if ('UTC' == $("[name = 'timezone']").val())
        $("[name = 'timezone']").val(moment.tz.guess());
};

BxEventsEntry.prototype.checkIn = function(oElement, iId) {
    var $this = this;
    var oParams = this._getDefaultData();
    oParams['id'] = iId;

    if(oElement)
        this.loadingInButton(oElement, true);

    jQuery.get (
        this._sActionsUrl + 'check_in',
        oParams,
        function(oData) {
            if(oElement)
                $this.loadingInButton(oElement, false);

            processJsonData(oData);
        },
        'json'
    );
};

/** @} */
