/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

function BxTimelineViewFilters(oOptions) {
    BxTimelineMain.call(this, oOptions);

    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oTimelineViewFilters' : oOptions.sObjName;
    this._sObjNameMenuFeeds = oOptions.sObjNameMenuFeeds == undefined ? 'bx_timeline_menu_feeds' : oOptions.sObjNameMenuFeeds;
    this._sName = oOptions.sName == undefined ? '' : oOptions.sName;
    this._sView = oOptions.sView == undefined ? 'timeline' : oOptions.sView;
    this._sType = oOptions.sType == undefined ? 'public' : oOptions.sType;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
    this._oFiltersPopupOptions = {};
}

BxTimelineViewFilters.prototype = Object.create(BxTimelineMain.prototype);
BxTimelineViewFilters.prototype.constructor = BxTimelineViewFilters;

BxTimelineViewFilters.prototype.changeFeed = function(oLink, sType, oRequestParams)
{
    var $this = this;
    var oViews = $(this._getHtmlId('views_content', this._oRequestParams, {with_type: false})); 
    var oViewActive = oViews.children(':visible');

    var sFilters = this._getHtmlId('filters_popup', jQuery.extend({}, this._oRequestParams, {name: ''}));
    if($(sFilters).length)
        $(sFilters).remove();

    this._oRequestParams.type = sType;
    oRequestParams = jQuery.extend({}, this._oRequestParams, {
        name: '',
        start: 0
    }, oRequestParams);

    var oData = this._getDefaultData(oLink);
    oData = jQuery.extend({}, oData, oRequestParams);

    var sView = $this._getHtmlId('main', oRequestParams);

    var oTab = $(oLink);
    var sTabActive = 'bx-menu-tab-active';
    var sTabActiveBg = 'bx-def-color-bg-active';
    oTab.parents('ul.' + this.sSP + '-menu-feeds').find('li.' + sTabActive).removeClass(sTabActive + ' ' + sTabActiveBg);
    oTab.parents('li:first').addClass(sTabActive + ' ' + sTabActiveBg);

    this.loadingIn(oLink, true);

    jQuery.get (
        this._sActionsUrl + 'get_view',
        oData,
        function(oResponse) {
            if(oLink)
                $this.loadingIn(oLink, false);

            if(!oResponse.content)
                return;

            oViewActive.hide('fast', function() {
                oViews.html('').append(oResponse.content).find(sView).bxProcessHtml();
            });
        },
        'json'
    );

    return false;
};

BxTimelineViewFilters.prototype.changeFeedFilters = function(oLink, oRequestParams)
{
    var $this = this;

    oRequestParams = jQuery.extend({}, this._oRequestParams, {
        start: 0
    }, oRequestParams);

    var sFilters = this._getHtmlId('filters_popup', jQuery.extend({}, oRequestParams, {name: '', type: (this._sName != '' ? this._oRequestParams.type : this._sType)}));
    if($(sFilters).length)
        return $(sFilters).dolPopup(this._oFiltersPopupOptions);

    var oData = this._getDefaultData(oLink);
    if(oRequestParams != undefined)
        oData = jQuery.extend({}, oData, oRequestParams);

    this.loadingIn(oLink, true);

    jQuery.get (
        this._sActionsUrl + 'get_view_filters',
        oData,
        function(oResponse) {
            if(oLink)
                $this.loadingIn(oLink, false);

            if(oResponse && oResponse.popup != undefined) {
                $this._oFiltersPopupOptions = jQuery.extend({}, oResponse.popup.options, {
                    pointer: { 
                        el: $(oLink),
                        align: 'right'
                    }
                });

                oResponse.popup.options = $this._oFiltersPopupOptions;
            }

            processJsonData(oResponse);
        },
        'json'
    );
};

BxTimelineViewFilters.prototype.toggleMenuItemFeeds = function(oSource)
{
    bx_menu_toggle(oSource, this._sObjNameMenuFeeds);
};

BxTimelineViewFilters.prototype.onFilterByModuleChange = function(oSource)
{
    var oModules = $(oSource).parents('.bx-form-element-wrapper:first').siblings('.modules');
    oModules.find("input[name='modules[]']:checked").removeAttr('checked');
    oModules.bx_anim($(oSource).val() == 'selected' ? 'show' : 'hide');
};

BxTimelineViewFilters.prototype.onFilterByMediaChange = function(oSource)
{
    var oModules = $(oSource).parents('.bx-form-element-wrapper:first').siblings('.media');
    oModules.find("input[name='media[]']:checked").removeAttr('checked');
    oModules.bx_anim($(oSource).val() == 'selected' ? 'show' : 'hide');
};

BxTimelineViewFilters.prototype.onFilterApply = function(oSource)
{
    var $this = this;

    oRequestParams = jQuery.extend({}, this._oRequestParams, {
        name: '',
        start: 0
    });

    var sView = this._getHtmlId('main', oRequestParams); 
    var oFilters = $(oSource).parents('.bx-tl-view-filters:first');

    //--- Apply feed Types
    var oContext = oFilters.find("select[name='by_context']");
    if(oContext.length) {
        var sType = this._sType;
        var iContext = 0;

        var sContext = oContext.val();
        if(sContext && sContext.indexOf('|') != -1) {
            var aContext = sContext.split('|');
            sType = aContext[0];
            iContext = parseInt(aContext[1]);
        }

        this._oRequestParams.type = sType;

        oRequestParams.type = sType;
        oRequestParams.context = iContext;
    }

    //--- Apply feed Filters
    oRequestParams.modules = [];
    if(oFilters.find("input[name='by_module']:checked").val() == 'selected')
        oFilters.find("input[name='modules[]']:checked").each(function() {
            oRequestParams.modules.push($(this).val());
        });

    oRequestParams.media = [];
    if(oFilters.find("input[name='by_media']:checked").val() == 'selected')
        oFilters.find("input[name='media[]']:checked").each(function() {
            oRequestParams.media.push($(this).val());
        });

    var oData = this._getDefaultData(oSource);
    if(oRequestParams != undefined)
        oData = jQuery.extend({}, oData, oRequestParams);

    this.loadingIn(oSource, true);

    jQuery.get (
        this._sActionsUrl + 'get_view',
        oData,
        function(oResponse) {
            if(oSource)
                $this.loadingIn(oSource, false);

            if(!oResponse.content)
                return;

            $('.bx-popup-applied:visible').dolPopupHide();

            $(sView).replaceWith(oResponse.content);
            $(sView).bxProcessHtml();
        },
        'json'
    );
};