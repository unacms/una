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

    this._oBrowsingFiltersPopupOptionsDefaults = jQuery.extend(this._oBrowsingFiltersPopupOptionsDefaults,  {
        onShow: function(oPopup) {
            var oTimezone = $(oPopup).find("[name = 'timezone']");
            if(oTimezone && oTimezone.length != 0)
                oTimezone.val(moment.tz.guess());
    
            console.log(oTimezone.val());
    
            $(oPopup).find("[name = 'cancel']").focus();
        }
    });   
}

BxEventsMain.prototype = Object.create(BxBaseModGroupsMain.prototype);
BxEventsMain.prototype.constructor = BxEventsMain;

BxEventsMain.prototype.onChangeBrowsingFiltersByDate = function(oElement) {
    var sAction = $(oElement).val() == 'date_range' ? 'show' : 'hide';

    $(oElement).parents('.bx-form-element-wrapper:first').siblings('.date-range').each(function() {
        $(this).bx_anim(sAction);
    });
};

BxEventsMain.prototype.applyBrowsingFilter = function(oElement, oRequestParams)
{
    var oFilters = $(oElement).parents('.bx-base-general-browsing-filters:first');

    if(oRequestParams == undefined)
        oRequestParams = {};

    oRequestParams.by_date = oFilters.find("input[name='by_date']:checked").val();
    if(oRequestParams.by_date == 'date_range') {
        oRequestParams.date_start = oFilters.find("input[name='date_start']").val();
        oRequestParams.date_end = oFilters.find("input[name='date_end']").val();
    }
    oRequestParams.timezone = oFilters.find("input[name='timezone']").val();

    BxBaseModGroupsMain.prototype.applyBrowsingFilter.call(this, oElement, oRequestParams);
};
/** @} */
