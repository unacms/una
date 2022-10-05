function BxArtificerUtils(oOptions)
{
    this._sActionUri = oOptions.sActionUri;
    this._sActionUrl = oOptions.sActionUrl;
    this._sObject = oOptions.sObject;
    this._aHtmlIds = undefined == oOptions.aHtmlIds ? {} : oOptions.aHtmlIds;  
    this._sColorScheme = undefined == oOptions.sColorScheme ? 'auto' : oOptions.sColorScheme;

    this.init();
}

BxArtificerUtils.prototype.init = function()
{
    var $this = this;

    switch(this._sColorScheme) {
        case 'auto':
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                $this.setColorSchemeIcon();
                $this.setColorSchemeHtml();
            });
            break;

        case 'light_only':
            this.setColorScheme(1);
            break;

        case 'dark_only':
            this.setColorScheme(2);
            break;
    }    

    this.setColorSchemeHtml();

    $(document).ready(function() {
        $this.setColorSchemeIcon();
    });
};

BxArtificerUtils.prototype.setColorScheme = function(iCode)
{
    switch(iCode) {
      case 0:
        localStorage.removeItem('theme');
        break;

      case 1:
        localStorage.theme = 'sun'
        break;

      case 2:
        localStorage.theme = 'dark'
        break;
    }

    this.setColorSchemeIcon();
    this.setColorSchemeHtml();
};

BxArtificerUtils.prototype.setColorSchemeHtml = function()
{
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) 
        $('html').addClass('dark')
    else
        $('html').removeClass('dark')
};

BxArtificerUtils.prototype.setColorSchemeIcon = function() 
{
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        $('.bx-sb-theme-switcher .sys-icon').addClass('moon').removeClass('sun');
    }

    if (localStorage.theme === 'sun' || (!('theme' in localStorage) && !window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        $('.bx-sb-theme-switcher .sys-icon').addClass('sun').removeClass('moon');
    }
};

BxArtificerUtils.prototype.getColorSchemeMenu = function() {
    $('#bx-sb-theme-switcher-menu').dolPopup({
        pointer: {
            el: $('.bx-sb-theme-switcher')
        },
        moveToDocRoot: true,
        cssClass: 'bx-popup-menu'
    });
};

BxArtificerUtils.prototype.getAddContentMenu = function(sMenu, e, sPosition) {
    $('.bx-popup-applied:visible').dolPopupHide();

    var sSidebar = 'account';
    if(bx_sidebar_active(sSidebar))
        bx_sidebar_toggle(sSidebar);

    bx_menu_popup_inline(sMenu, e, {});
};