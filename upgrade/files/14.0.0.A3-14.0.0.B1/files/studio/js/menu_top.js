/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioMenuTop(oOptions) {
    this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioMenuTop' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxDolStudioMenuTop.prototype.clickEdit = function(oItem) {
    $('.bx-popup-applied:visible').dolPopupHide();

    var oParent = $(oItem).parent();
    if(oParent.hasClass('bx-menu-tab-active')) {
        oParent.removeClass('bx-menu-tab-active');
        oBxDolStudioLauncher.disableJitter();
    }
    else {
        oParent.addClass('bx-menu-tab-active');
        oBxDolStudioLauncher.enableJitter();
    }
};

BxDolStudioMenuTop.prototype.clickFeatured = function(oItem) {
    $('.bx-popup-applied:visible').dolPopupHide();

    var oParent = $(oItem).parent();
    if(oParent.hasClass('bx-menu-tab-active')) {
        oParent.removeClass('bx-menu-tab-active');
        oBxDolStudioLauncher.disableFeatured();
    }
    else {
        oParent.addClass('bx-menu-tab-active');
        oBxDolStudioLauncher.enableFeatured();
    }
};

BxDolStudioMenuTop.prototype.clickLogout = function(oItem) {
    $(oItem).parent().toggleClass('bx-menu-tab-active');
};

BxDolStudioMenuTop.prototype.searchWidget = function(oEvent) {
    setTimeout(function () {
        var sSearch = $(oEvent.target).val().toLowerCase(); 
        if(!!sSearch.length) {
            $('.bx-std-widgets > .bx-std-widget:not([data-name^="' + sSearch + '"])').hide();
            $('.bx-std-widgets > .bx-std-widget[data-name^="' + sSearch + '"]:hidden').show();
        }
        else
            $('.bx-std-widgets > .bx-std-widget:hidden').show();
    }, 100);
};

/** @} */
