/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolCreatePost(oOptions) {
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDolCreatePost' : oOptions.sObjName;
    this._sRootUrl = oOptions.sRootUrl == undefined ? sUrlRoot : oOptions.sRootUrl;
    this._sDefault = oOptions.sDefault == undefined ? '' : oOptions.sDefault;
    this._iContextId = oOptions.iContextId == undefined ? 0 : oOptions.iContextId;
    this._oPreloadingList = oOptions.oPreloadingList == undefined ? [] : oOptions.oPreloadingList;
    this._oCustom = oOptions.oCustom == undefined ? {} : oOptions.oCustom;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;

    var $this = this;
    $(document).ready(function() {
        $this.init();
    });
}

BxDolCreatePost.prototype.init = function () {
    var oDate = new Date();

    for(var sKey in this._oPreloadingList) {
        var sForm = '.sys-cpf-form.sys-cpf-' + sKey;
        if($(sForm).length != 0)
            continue;

        $.get(
            this._sRootUrl + 'modules/?r=' + this._oPreloadingList[sKey] + '/get_create_post_form/', {
                ajax_mode: true,
                dynamic_mode: true,
                absolute_action_url: true,
                context_id: this._iContextId,
                custom: this._oCustom,
                _t:oDate.getTime()
            },
            function(oData) {
                if(!oData || !oData.content) 
                    return;

                $('.sys-cpf-close').before($('<div class="sys-cpf-form sys-cpf-' + oData.module + '">' + oData.content + '</div>').hide());
            }, 
            'json'
        );
    }
};

BxDolCreatePost.prototype.getForm = function (sModuleName, sModuleUri, oElement) {
    var $this = this;

    var oTab = $(oElement);
    var sTabActive = 'bx-menu-tab-active';
    oTab.parents('.bx-db-menu:first').find('li.' + sTabActive).removeClass(sTabActive);
    oTab.parents('li:first').addClass(sTabActive);
    oTab.parents('.bx-popup-applied:visible').dolPopupHide();

    $('.sys-cpf-form:visible').hide({
        duration: 0,
        complete: function() {
            $('.sys-cpf-close:visible').hide();

            if(!$('.sys-cpf-form.sys-cpf-' + sModuleName + ' form').length) {
                var oDate = new Date();

                $this._placeholderInBlock(oElement, true);

                $.get(
                    $this._sRootUrl + 'modules/?r=' + sModuleUri + '/get_create_post_form/', {
                        ajax_mode: true,
                        dynamic_mode: true,
                        absolute_action_url: true,
                        context_id: $this._iContextId,
                        custom: $this._oCustom,
                        _t: oDate.getTime()
                    },
                    function(oData) {
                        $this._placeholderInBlock(oElement, false);

                        if(!oData || !oData.content) 
                            return;

                        var sForm = '.sys-cpf-form.sys-cpf-' + oData.module;
                        if($(sForm).length == 0)
                            $('.sys-cpf-close').before($('<div class="sys-cpf-form sys-cpf-' + oData.module + '"></div>').hide());

                        $(sForm).html(oData.content).show().siblings('.sys-cpf-close:hidden').show();
                    }, 
                    'json'
                );
            }
            else 
                $('.sys-cpf-form.sys-cpf-' + sModuleName).show().siblings('.sys-cpf-close:hidden').show();
        }
    });

    return false;
};

BxDolCreatePost.prototype.hideForm = function(oElement) {
    $(oElement).parents('.sys-cpf-close:first').hide();

    var sTabActive = 'bx-menu-tab-active';
    var sTabDefault = 'bx-menu-item-' + this._sDefault;

    var oMiActive = $(oElement).parents('.bx-db-container:first').find('ul > li.' + sTabActive);
    var bMiChange = !oMiActive.hasClass(sTabDefault);

    if(bMiChange)
        oMiActive.removeClass(sTabActive);

    $('.sys-cpf-form:visible').hide({
        duration: 0,
        complete: function() {
            $('.sys-cpf-form.sys-cpf-default').show();

            if(bMiChange)
                $('.' + sTabDefault).addClass(sTabActive);
        }
    });
};

BxDolCreatePost.prototype._loading = function(e, bShow) {
    var oParent = $(e).length ? $(e) : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolCreatePost.prototype._loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolCreatePost.prototype._placeholderInBlock = function(e, bShow) {
    var oPlaceholder = null;
    if($(e).length) 
        oPlaceholder = $(e).parents('.bx-db-container:first').find('.sys-cpf-form.sys-cpf-loading'); 

    if(!oPlaceholder)
        return _loadingInBlock(e, bShow);

    if(bShow)
        oPlaceholder.show();
    else
        oPlaceholder.hide();
};

/** @} */
