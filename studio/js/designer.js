/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioDesigner(oOptions) {
    this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioDesigner' : oOptions.sObjName;
    this.sParamPrefix = oOptions.sParamPrefix == undefined ? 'dsg' : oOptions.sParamPrefix;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.sCodeMirror = oOptions.sCodeMirror == undefined ? '' : oOptions.sCodeMirror;

    var $this = this;
    $(document).ready (function () {
    	if($this.sCodeMirror != '')
    		$this.initCodeMirror($this.sCodeMirror);
    });
}

BxDolStudioDesigner.prototype.initCodeMirror = function(sSelector) {
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

BxDolStudioDesigner.prototype.makeDefault = function(sUri) {
    var $this = this;
    var oDate = new Date();
    var aParams = {_t: oDate.getTime()};
    aParams[this.sParamPrefix + '_action'] = 'make_default';
    aParams[this.sParamPrefix + '_value'] = sUri;

    $.post(
        this.sActionsUrl,
        aParams,
        function(oData) {
            if(oData.code != 0 && oData.message.length > 0) {
                bx_alert(oData.message);
                return;
            }

            document.location.href = document.location.href; 
        },
        'json'
    );
};

BxDolStudioDesigner.prototype.deleteLogo = function() {
    var oDate = new Date();
    var aParams = {_t: oDate.getTime()};
    aParams[this.sParamPrefix + '_action'] = 'delete_logo';

    $.post(
        this.sActionsUrl,
        aParams,
        function(oData) {
            processJsonData(oData);
        },
        'json'
    );
};

BxDolStudioDesigner.prototype.deleteMark = function() {
    var oDate = new Date();
    var aParams = {_t: oDate.getTime()};
    aParams[this.sParamPrefix + '_action'] = 'delete_mark';

    $.post(
        this.sActionsUrl,
        aParams,
        function(oData) {
            processJsonData(oData);
        },
        'json'
    );
};

BxDolStudioDesigner.prototype.deleteCover = function(sType, iId) {
    var $this = this;
    var oDate = new Date();
    var aParams = {_t: oDate.getTime()};
    aParams[this.sParamPrefix + '_action'] = 'delete_cover';
    aParams[this.sParamPrefix + '_value'] = sType;

    $.post(
        this.sActionsUrl,
        aParams,
        function(oData) {
            if(oData.code != 0 && oData.message.length > 0) {
                bx_alert(oData.message);
                return;
            }

            $('#bx-dsg-cover-' + iId).bx_anim('hide', $this.sAnimationEffect, $this.iAnimationSpeed, function() {
                $(this).remove();
            });
        },
        'json'
    );
};
/** @} */
