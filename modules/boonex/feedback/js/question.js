/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Feedback Feedback
 * @ingroup     UnaModules
 *
 * @{
 */

function BxFdbQuestion(oOptions) {
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxFdbQuestion' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxFdbQuestion.prototype.answerOnType = function(oEvent) {
    var oText = $(oEvent.target);
    oText.parents('.bx-form-input-answer:first').find("input[type = 'checkbox']").val(oText.val().trim());
};

BxFdbQuestion.prototype.answerOnSelect = function(oElement, iQuestion) {
    var $this = this;

    var fOnOk = function(oPopup) {
        var oParams = jQuery.extend({}, $this._getDefaultParams(), {
            question_id: iQuestion, 
            answer_id: $(oElement).val(),
            text: $(oPopup).find("input[type = 'text']").val()
        });

        $.get(
            $this._sActionsUrl + 'answer/',
            oParams,
            function(oData){
                processJsonData(oData);
            },
            'json'
        );
    };

    bx_prompt(_t('_bx_feedback_txt_enter_text'), '', fOnOk);
};

BxFdbQuestion.prototype.answerAdd = function(oButton, sName) {
    var oButton = $(oButton);

    var oSubentry = oButton.parents('#bx-form-element-' + sName).find('.bx-form-input-answer:first').clone();
    oSubentry.find("input[type = 'text']").val('');
    oSubentry.find("input[type = 'checkbox']").val('1').removeAttr('checked');
    oSubentry.find("input[type = 'hidden']").remove();

    oButton.parents('.bx-form-input-answer-add:first').before(oSubentry);
};

BxFdbQuestion.prototype.answerDelete = function(oButton) {
    $(oButton).parents('.bx-form-input-answer:first').remove();
};

BxFdbQuestion.prototype._getDefaultParams = function() {
    var oDate = new Date();
    return {_t:oDate.getTime()};
};