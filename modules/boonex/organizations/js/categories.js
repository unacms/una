/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

function BxOrgsCategories(oOptions) {
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxOrgsCategories' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxOrgsCategories.prototype.categoryAdd = function (oButton, sName) {
    var oButton = $(oButton);

    var oCategory = oButton.parents('#bx-form-element-' + sName).find('.bx-form-input-category:first').clone();
    oCategory.find("input[type = 'text']").val('');
    oCategory.find("input[type = 'hidden']").remove();

    oButton.parents('.bx-form-input-category-add:first').before(oCategory);
};

BxOrgsCategories.prototype.categoryAddNew = function (oButton, sName) {
    var oButton = $(oButton);

    var oCategory = oButton.parents('#bx-form-element-' + sName).find('.bx-form-input-category-new:first').clone();
    oCategory.find("input[type = 'text']").val('');
    oCategory.find("input[type = 'hidden']").remove();

    $('.bx-form-input-categories .bx-form-input-category-add:first').before(oCategory);
};

BxOrgsCategories.prototype.categoryDelete = function (oButton) {
    $(oButton).parents('.bx-form-input-category:first').remove();
};

BxOrgsCategories.prototype.categoryDeleteNew = function (oButton) {
    $(oButton).parents('.bx-form-input-category-new:first').remove();
};
