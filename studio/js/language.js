/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioLanguage(oOptions) {
    BxDolStudioModule.call(this, oOptions);

    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioLanguage' : oOptions.sObjName;
}

BxDolStudioLanguage.prototype = Object.create(BxDolStudioModule.prototype);
BxDolStudioLanguage.prototype.constructor = BxDolStudioLanguage;

/** @} */
