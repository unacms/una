/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioDesign(oOptions) {
    BxDolStudioModule.call(this, oOptions);

    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioDesign' : oOptions.sObjName;
    this.sCodeMirror = oOptions.sCodeMirror == undefined ? '' : oOptions.sCodeMirror;

    var $this = this;
    $(document).ready (function () {
    	if($this.sCodeMirror != '')
            $this.initCodeMirror($this.sCodeMirror);
    });

    if($this.sCodeMirror != '')
        $('#adm-settings-form-categorized .bx-form-collapsable').on('bx_show', function() {
            $this.refreshCodeMirror();
        });
}

BxDolStudioDesign.prototype = Object.create(BxDolStudioModule.prototype);
BxDolStudioDesign.prototype.constructor = BxDolStudioDesign;

BxDolStudioDesign.prototype.initCodeMirror = function(sSelector) {
    var oSelector = $(sSelector);
    for(var i = 0; i < oSelector.length; i++) {
        var e = CodeMirror.fromTextArea(oSelector.get(i), {
            lineNumbers: true,
            mode: "htmlmixed",
            htmlMode: true,
            matchBrackets: true
        });
    }
};

BxDolStudioDesign.prototype.refreshCodeMirror = function() {
    $('.CodeMirror').each(function() {
        this.CodeMirror.refresh();
    });
};

/** @} */
