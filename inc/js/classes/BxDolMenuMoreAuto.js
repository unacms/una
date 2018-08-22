/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolMenuMoreAuto(options)
{
    this._sObject = options.sObject;
    this._aHtmlIds = undefined == options.aHtmlIds ? {} : options.aHtmlIds;

    this._sKeyWidth = 'bx-mma-width';

    this._sClassItem = '.bx-menu-item';
    this._sClassItemMore = this._sClassItem + '.bx-menu-item-more-auto';
    this._sClassItemMoreSubmenu = '.bx-menu-submenu-more-auto';

    this._oMenu = null;
    this._iMenu = 0;
    this._iParent = 0;
    this._oItemMore = null;
    this._iItemMore = 0;
    this._oItemMoreSubmenu = null;

    var $this = this;
    $(document).ready(function () {
        $this.init();

        $(window).on('resize', function() {
           $this.update();
        });
    });
}

BxDolMenuMoreAuto.prototype.init = function() {
    this._getData();

    this._oMenu.css('overflow', 'visible');

    if(this._iMenu < this._iParent)
        return;

    this._moveToSubmenu();
};

BxDolMenuMoreAuto.prototype.update = function()
{
    this._getData();

    if(this._iMenu > this._iParent)
        this._moveToSubmenu();
    if(this._iMenu < this._iParent)
        this._moveFromSubmenu();
};

BxDolMenuMoreAuto.prototype.more = function(oElement)
{
    var oElement = $(oElement);

    oElement.parents('li:first').find('#' + this._aHtmlIds['more_auto_popup']).dolPopup({
        pointer: {
            el: oElement
        }, 
        moveToDocRoot: false
    });
}

BxDolMenuMoreAuto.prototype._moveToSubmenu = function()
{
    var $this = this;

    var bRelocateOthers = false;
    var iWidthTotal = this._iItemMore;
    var oSubmenuItemFirst = this._oItemMoreSubmenu.children(this._sClassItem + ':first');

    this._oMenu.children(this._sClassItem + ':not(' + this._sClassItemMore + ')').each(function() {
        var oItem = $(this);
        var iItem = $this._getWidth(oItem);
        if(bRelocateOthers || iWidthTotal + iItem > $this._iParent) {
            if(!oSubmenuItemFirst.length)
                $this._oItemMoreSubmenu.append(oItem.detach());
            else
                oSubmenuItemFirst.before(oItem.detach());
            bRelocateOthers = true;
            return;
        }

        iWidthTotal += iItem;
    });

    if(this._oItemMoreSubmenu.find('li').length)
        this._oItemMore.show();
   
};

BxDolMenuMoreAuto.prototype._moveFromSubmenu = function()
{
    var $this = this;

    var bStopRelocation = false;
    var iWidthTotal = this._iMenu;
    this._oItemMoreSubmenu.children(this._sClassItem).each(function() {
        if(bStopRelocation) 
            return;

        var oItem = $(this);
        var iItem = $this._getWidth(oItem);
        if(iWidthTotal + iItem > $this._iParent) {
            bStopRelocation = true;
            return;
        }

        $this._oItemMore.before(oItem.detach());
        iWidthTotal += iItem;
    });

    if(!this._oItemMoreSubmenu.find('li').length)
        this._oItemMore.hide();
};

BxDolMenuMoreAuto.prototype._getData = function()
{
    var $this = this;

    this._oMenu = $('.bx-menu-object-' + this._sObject);
    this._oItemMore = this._oMenu.find(this._sClassItemMore);
    this._oItemMoreSubmenu = this._oItemMore.find(this._sClassItemMoreSubmenu);

    this._iMenu = 0;
    this._oMenu.children(this._sClassItem + ':visible').each(function() {
        $this._iMenu += $this._getWidth($(this));
    });
    
    this._iParent = this._oMenu.parent().width();

    this._iItemMore = this._oItemMore.outerWidth(true);
};

BxDolMenuMoreAuto.prototype._getWidth = function(oItem)
{
    var iItem = parseInt(oItem.attr(this._sKeyWidth));
    if(iItem)
        return iItem;

    iItem = oItem.outerWidth(true);
    if(iItem)
        oItem.attr(this._sKeyWidth, iItem);

    return iItem;
}
/** @} */
