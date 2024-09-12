/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

function BxBaseModGroupsMain(oOptions) {
    this._sModule = oOptions.sModule;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxBaseModGroupsMain' : oOptions.sObjName;
    this._sObjNameGrid = oOptions.sObjNameGrid == undefined ? '' : oOptions.sObjNameGrid;
    
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;

    this._oBrowsingFiltersPopupOptions = {};
    this._oBrowsingFiltersPopupOptionsDefaults = {};
}

BxBaseModGroupsMain.prototype.changeBrowsingFilters = function(oElement, sSelector, oRequestParams) {
    if($(sSelector).length)
        return $(sSelector).dolPopup(this._oBrowsingFiltersPopupOptions);

    var $this = this;
    var oData = jQuery.extend({mode: ''}, this._getDefaultData());
    if(oRequestParams != undefined)
        oData = jQuery.extend({}, oData, oRequestParams);

    this.loadingInButton(oElement, true);

    jQuery.get (
        this._sActionsUrl + 'get_browsing_filters',
        oData,
        function(oResponse) {
            if(oElement)
                $this.loadingInButton(oElement, false);

            if(oResponse && oResponse.popup != undefined) {
                $this._oBrowsingFiltersPopupOptions = jQuery.extend($this._oBrowsingFiltersPopupOptionsDefaults, oResponse.popup.options, {
                    pointer: { 
                        el: $(oElement),
                        align: 'right'
                    }
                });

                oResponse.popup.options = $this._oBrowsingFiltersPopupOptions;
            }

            processJsonData(oResponse);
        },
        'json'
    );
};

BxBaseModGroupsMain.prototype.applyBrowsingFilter = function(oElement, oRequestParams) {
    var $this = this;

    var oData = this._getDefaultData();
    if(oRequestParams != undefined)
        oData = jQuery.extend({}, oData, oRequestParams);

    this.loadingInButton(oElement, true);

    jQuery.get (
        this._sActionsUrl + 'apply_browsing_filters',
        oData,
        function(oResponse) {
            $this.loadingInButton(oElement, false);

            $(oElement).parents('.bx-popup-applied:visible:first').dolPopupHide();

            processJsonData(oResponse);
        },
        'json'
    );
};

BxBaseModGroupsMain.prototype.onApplyBrowsingFilter = function(oData) {
    if(!oData.content)
        return;

    var oContainer = $('.bx-db-content').has('#' + $(oData.content).find('.bx-search-result-block:first').attr('id'));
    if(!oContainer || oContainer.length == 0)
        return;

    oContainer.html(oData.content).bxProcessHtml();
};

BxBaseModGroupsMain.prototype.onChangeMembersFilter = function(oFilter) {
    var $this = this;
    var oFilter1 = $('#bx-grid-frole-' + this._sObjNameGrid);
    var sValueFilter1 = oFilter1.length > 0 ? oFilter1.val() : '';

    var oSearch = $('#bx-grid-search-' + this._sObjNameGrid);
    var sValueSearch = oSearch.length > 0 ? oSearch.val() : '';
    if(sValueSearch == _t('_sys_grid_search'))
        sValueSearch = '';

    clearTimeout($this._iSearchTimeoutId);
    $this._iSearchTimeoutId = setTimeout(function () {
        glGrids[$this._sObjNameGrid].setFilter(sValueFilter1 + $this._sParamsDivider + sValueSearch, true);
    }, 500);
};

BxBaseModGroupsMain.prototype.connAction = function(oElement, sObject, sAction, iContentId) {
    var $this = this;
    var oParams = this._getDefaultData();
    oParams['s'] = 'main';
    oParams['o'] = sObject;
    oParams['a'] = sAction;
    oParams['cpi'] = iContentId;

    if(oElement)
        this.loadingInButton(oElement, true);

    jQuery.get (
        this._sActionsUrl + 'get_questionnaire',
        oParams,
        function(oData) {
            if(oElement)
                $this.loadingInButton(oElement, false);

            oData.element = oElement;
            processJsonData(oData);
        },
        'json'
    );
};

BxBaseModGroupsMain.prototype.connActionPerformed = function(oData) {
    var sClass = this._sModule.replace('_', '-') + '-unit-' + oData.ci;
    
    bx_conn_action($('.' + sClass + ' .bx-btn'), oData.o, oData.a, oData.cpi, false, function(oData, eLink) {
        $(eLink).each(function() {
            $(this).parents('.bx-menu-item:first').remove();
        });
    });
};

BxBaseModGroupsMain.prototype.onClickSetRole = function(iFanProfileId, iRole) {
    $('.bx-popup-applied:visible').dolPopupHide();

    if(glGrids[this._sObjNameGrid] != undefined)
        glGrids[this._sObjNameGrid].actionWithId(iFanProfileId, 'set_role_submit', {role: iRole}, '', false, false);
};

BxBaseModGroupsMain.prototype.onClickSetRoleMulti = function(oElement, iFanProfileId, iRole) {
    if(!glGrids[this._sObjNameGrid])
        return;

    var oSiblings = $(oElement).parents('.bx-base-group-sr-role:first').siblings();
    if(iRole == 0)
        oSiblings.find('input:checked').removeAttr('checked');
    else
        oSiblings.find("input[value = '0']:checked").removeAttr('checked');

    var sRoles = $(oElement).parents('.bx-base-group-sr-roles:first').find('input:checked').serialize();
    glGrids[this._sObjNameGrid].actionWithId(iFanProfileId, 'set_role_submit', {}, sRoles, false, false);
};

BxBaseModGroupsMain.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);	
};

BxBaseModGroupsMain.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

/** @} */
