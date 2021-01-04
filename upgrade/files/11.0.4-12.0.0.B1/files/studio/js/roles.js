/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioRoles(oOptions) {
    this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioRoles' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.sPage = oOptions.sPage == undefined ? 'general' : oOptions.sPage;
}
/** @} */
