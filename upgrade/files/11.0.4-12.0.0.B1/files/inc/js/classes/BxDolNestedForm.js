/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolNestedForm(options) {

    this.init(options);
    this._eForm = null;
};


BxDolNestedForm.prototype.init = function (options) {
	this._sUniqId = options.uniq_id;
    this._sJsInstanceName = options.js_instance_name;
    this._sResultContainerId = 'bx-form-input-nested-form-forms-' + this._sUniqId;
	this._sNestedType = options.nested_type ? options.nested_type : '';
   	this._sTemplateGhost = (options.template_ghost && typeof options.template_ghost == "string") ? options.template_ghost.replace(/{nested_type}/gi, options.nested_type) : '';
    this._sTemplateErrorGhosts = options.template_error ? options.template_error : '<div class="bx-informer-msg-error bx-def-padding-sec bx-def-round-corners bx-def-margin-sec-top">{error}</div>';
    this._sFormName = options.form_name ? options.form_name : '';
    this._sActionsUri = options.action_uri ? options.action_uri : '';
	this._iContentId = undefined == options.content_id || '' == options.content_id ? '' : parseInt(options.content_id);	
};

BxDolNestedForm.prototype._getUrlWithStandardParams = function () {
    return sUrlRoot + this._sActionsUri + 'Nested?uid=' + this._sUniqId + '&s=' + this._sFormName + '&t=' + this._sNestedType
};

BxDolNestedForm.prototype._getContainerId = function (iItemId) {
	return 'bx-nested-form-' + this._sNestedType + '-' + iItemId;
};

BxDolNestedForm.prototype.addEmptyGhost = function () {
    var oVars = {
        js_instance_name: this._sJsInstanceName,
        file_id: 0,
        item_id: 0,
    }
    var sHTML = this._sTemplateGhost;
    for (var i in oVars)
        sHTML = sHTML.replace (new RegExp('{'+i+'}', 'g'), oVars[i]);
	
	$.each(this._aFields, function( index, value ) {
		sHTML = sHTML.replace (new RegExp('{'+value+'}', 'g'), '');
	});

    $('#' + this._sResultContainerId).append(sHTML).addWebForms();
};

BxDolNestedForm.prototype.deleteGhost = function (e, sSelector) {

	iItemId = 0;
	$(e).closest('.bx-nested-form-ghost-form').each(function() {
  		iItemId = $( this ).find("input[name='"+sSelector+"[]']").val()
	});
	
	if (0 == parseInt(iItemId) || typeof(iItemId) == "undefined") {
        $(e).closest('.bx-nested-form-ghost').slideUp(function () {
            $(this).remove();
        });
    }
    else {

        var sUrl = this._getUrlWithStandardParams() + '&a=delete&id=' + iItemId;
        var $this = this;

        var sContainerId = $this._getContainerId(iItemId);
        bx_loading(sContainerId, true);
		
		$.post(sUrl, function (sMsg) {
            bx_loading(sContainerId, false);
            if ('ok' == sMsg) {
                $('#' + sContainerId).slideUp('slow', function () {
                    $(this).remove();
                });
            } else {
                $('#' + $this._sResultContainerId).prepend($this._sTemplateErrorGhosts.replace('{error}', sMsg));
            }
        });
    }
};

BxDolNestedForm.prototype._replaceVars = function (sHTML, oVars) {

    oVars = $.extend({}, oVars, {js_instance_name: this._sJsInstanceName});
	
    // replace vars
    for (var i in oVars) {
        sHTML = sHTML.replace (new RegExp('{'+i+'}', 'g'), oVars[i]);
	}

    // set selected values
    var oHTML = $(sHTML);
    for (var i in oVars)
        oHTML.find('select[name="' + i + '[]"] option[value="' + oVars[i] + '"]').attr('selected', 'selected');
    sHTML = $('<div></div>').append(oHTML).html();

    return sHTML;
};
/** @} */
