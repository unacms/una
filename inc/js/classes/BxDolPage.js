/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */
function BxDolPage(oOptions) {
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDolPage' : oOptions.sObjName;
    this._sObject = oOptions.sObject; // page object

    this._sActionsUri = 'page.php';
    this._sActionsUrl = oOptions.sRootUrl; // actions url address
    this._aHtmlIds = oOptions.aHtmlIds;

    this._isStickyColumns = oOptions.isStickyColumns == undefined ? false : oOptions.isStickyColumns;
    this._iLastSc = 0;

    var $this = this;
    $(document).ready(function() {
        $this.init();
    });
}

BxDolPage.prototype.init = function() {
    var $this = this;
    if ($this._isStickyColumns && !$('html').hasClass('bx-media-phone')) {
        $('.bx-layout-col').theiaStickySidebar({
            additionalMarginTop: 70
        });
    }
    // process embeds
    $(".bx-embed-link").each(function() {
        $(this).html($(this).attr('source'));
        $.getJSON(sUrlRoot + '/embed.php?', {a: 'get_link', l: $(this).attr('source')}, $this.embededCallback($(this)));
    });
    
    bx_process_links();
};

BxDolPage.prototype.embededCallback = function(item)
{
    return function(oData) {
        item.html(oData.code)
        if (item.find('a').length > 0){
            bx_embed_link(item.find('a')[0]);
        }
    };
}

BxDolPage.prototype.share = function(oLink, sUrl)
{
    var oData = this._getDefaultParams();
    oData['a'] = 'get_share';
    oData['url'] = sUrl;
    $(".bx-popup-applied:visible").dolPopupHide();

    $(window).dolPopupAjax({
        url: bx_append_url_params(this._sActionsUri, oData),
        displayMode: 'box',
        closeOnOuterClick: false,
        id: {value: 'sys_share_popup', force: true}
    });
}

BxDolPage.prototype.showHelp = function(oLink, iBlockId)
{
    var oData = this._getDefaultParams();
    oData['a'] = 'get_help';
    oData['block_id'] = iBlockId;

    $(oLink).dolPopupAjax({
        id: {value:this._aHtmlIds['help_popup'] + iBlockId, force:1}, 
        url: bx_append_url_params(this._sActionsUri, oData),
        closeOnOuterClick: true,
        removeOnClose: true,
        onBeforeShow: function(oPopup) {
            oPopup.addClass('bx-popup-help');
        }
    });
};
BxDolPage.prototype._getDefaultParams = function() 
{
    var oDate = new Date();
    return {
        o: this._sObject,
        _t: oDate.getTime()
    };
};

/** @} */
