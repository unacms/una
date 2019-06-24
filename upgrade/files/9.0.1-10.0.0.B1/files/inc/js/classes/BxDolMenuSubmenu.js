/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolMenuSubmenu(oOptions)
{
    this._sObject = oOptions.sObject;
    this._aHtmlIds = oOptions.aHtmlIds != undefined ? oOptions.aHtmlIds : {};  

    this._sClassBar = oOptions.sClassBar != undefined ? oOptions.sClassBar : 'bx-menu-main-bar';
    this._sClassBarWrapper = oOptions.sClassBarWrapper != undefined ? oOptions.sClassBarWrapper : 'bx-menu-main-bar-wrapper';
    this._sClassMenu = oOptions.sClassMenu != undefined ? oOptions.sClassMenu : 'bx-menu-main-submenu';
    this._sClassHideShadowAfter = 'bx-menu-main-bar-hide-shadow-after';
    this._sClassHideShadowBefore = 'bx-menu-main-bar-hide-shadow-before';
}

BxDolMenuSubmenu.prototype.init = function() {
    var $this = this;

    $(document).ready(function() {
        //--- Initialize Scrolling Effects ---//
        $('.' + $this._sClassBar).bind('scroll', function () {
            var oMenuBarWrapper = $(this).parents('.' + $this._sClassBarWrapper);
            var oMenu = $(this).find('.' + $this._sClassMenu);

            if(Math.abs(oMenu.position().left) >= (oMenu.width() - $(this).width() - 1))
                oMenuBarWrapper.addClass($this._sClassHideShadowAfter);
            else
                oMenuBarWrapper.removeClass($this._sClassHideShadowAfter);

            if(oMenu.position().left >= 0)
                oMenuBarWrapper.addClass($this._sClassHideShadowBefore);
            else
                oMenuBarWrapper.removeClass($this._sClassHideShadowBefore);
        });
    });
};

/** @} */
