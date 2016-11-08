/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Event intervals sub-form
 */
function BxDolEventsIntervals (options) {

    this.init(options);
    this._eForm = null;
};

BxDolEventsIntervals.prototype.init = function (options) {

    this._sUniqId = options.uniq_id;

    this._sJsInstanceName = options.js_instance_name;

    this._sResultContainerId = 'bx-form-input-reoccurring-intervals-forms-' + this._sUniqId;

    this._sTemplateGhost = options.template_ghost ? options.template_ghost : '<div id="' + this._getIntervalContainerId('{interval_id}') + '"><input type="hidden" name="f[]" value="{interval_id}" />{interval_name} (<a href="javascript:void(0);" onclick="{js_instance_name}.deleteGhost(this, \'{interval_id}\')">delete</a>)</div>';

    this._sTemplateErrorGhosts = options.template_error ? options.template_error : '<div class="bx-informer-msg-error bx-def-padding-sec bx-def-round-corners bx-def-margin-sec-top">{error}</div>';

    this._iContentId = undefined == options.content_id || '' == options.content_id ? '' : parseInt(options.content_id);
};

BxDolEventsIntervals.prototype._getUrlWithStandardParams = function () {
    return sUrlRoot + 'modules/?r=events/intervals&uid=' + this._sUniqId;
};

BxDolEventsIntervals.prototype._getIntervalContainerId = function (iIntervalId) {    
	return 'bx-events-interval-' + iIntervalId;
};

BxDolEventsIntervals.prototype.getCurrentIntervalsCount = function () {
    return $('#' + $this._sResultContainerId + ' .bx-events-interval-ghost').length;
};

BxDolEventsIntervals.prototype.addEmptyGhost = function () {
    var oVars = {
        js_instance_name: this._sJsInstanceName,
        file_id: 0,
        interval_id: 0,
        repeat_year: '',
        repeat_month: '',
        repeat_week_of_month: '',
        repeat_day_of_month: '',
        repeat_day_of_week: '',
        repeat_stop: ''
    }
    var sHTML = this._sTemplateGhost;
    for (var i in oVars)
        sHTML = sHTML.replace (new RegExp('{'+i+'}', 'g'), oVars[i]);

    $('#' + this._sResultContainerId).prepend(sHTML).addWebForms();
};

BxDolEventsIntervals.prototype.restoreGhosts = function () {
    var sUrl = this._getUrlWithStandardParams() + '&f=json' + '&a=restore&c=' + this._iContentId + '&_t=' + (new Date());
    var $this = this;

    bx_loading(this._sResultContainerId, true);

    $.getJSON(sUrl, function (aData) {

        bx_loading($this._sResultContainerId, false);
        
        if (typeof $this._sTemplateGhost == 'object') {
            $.each($this._sTemplateGhost, function(iIntervalId, sHTML) {
            	var oIntervalsContainer = $('#' + $this._getIntervalContainerId(iIntervalId));
                if (oIntervalsContainer.length > 0)
                    return;
                
                if ('object' === typeof(aData) && 'undefined' !== aData[iIntervalId])
                    sHTML = $this._replaceVars(sHTML, aData[iIntervalId]);

                $('#' + $this._sResultContainerId).prepend(sHTML).addWebForms();
            });
        }

        if ('object' === typeof(aData)) {

            $.each(aData, function(iIntervalId, oVars) {
            	var oIntervalsContainer = $('#' + $this._getIntervalContainerId(iIntervalId));
                if (oIntervalsContainer.length > 0)
                    return;

                var sHTML;
                if (typeof $this._sTemplateGhost == 'object')
                    sHTML = $this._sTemplateGhost[iIntervalId];
                else
                    sHTML = $this._sTemplateGhost;

                if ("undefined" !== typeof(sHTML)) {
                    sHTML = $this._replaceVars(sHTML, oVars);
                    $('#' + $this._sResultContainerId).prepend(sHTML).addWebForms();
                }
            });
        }
    });
};

BxDolEventsIntervals.prototype.deleteGhost = function (e, iIntervalId) {

    if (0 == parseInt(iIntervalId)) {
        $(e).parents().filter('.bx-events-interval-ghost').slideUp(function () {
            $(this).remove();
        });
    }
    else {

        var sUrl = this._getUrlWithStandardParams() + '&a=delete&id=' + iIntervalId;
        var $this = this;

        var sIntervalContainerId = $this._getIntervalContainerId(iIntervalId);
        bx_loading(sIntervalContainerId, true);

        $.post(sUrl, function (sMsg) {
            bx_loading(sIntervalContainerId, false);
            if ('ok' == sMsg) {
                $('#' + sIntervalContainerId).slideUp('slow', function () {
                    $(this).remove();
                });
            } else {
                $('#' + $this._sResultContainerId).prepend($this._sTemplateErrorGhosts.replace('{error}', sMsg));
            }
        });
    }
};

BxDolEventsIntervals.prototype._replaceVars = function (sHTML, oVars) {

    oVars = $.extend({}, oVars, {js_instance_name: this._sJsInstanceName});

    // replace vars
    for (var i in oVars) 
        sHTML = sHTML.replace (new RegExp('{'+i+'}', 'g'), oVars[i]);

    // set selected values
    var oHTML = $(sHTML);
    for (var i in oVars)
        oHTML.find('select[name="' + i + '[]"] option[value="' + oVars[i] + '"]').attr('selected', 'selected');
    sHTML = $('<div></div>').append(oHTML).html();

    return sHTML;
};

/** @} */
