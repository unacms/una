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
    this._oCustom = oOptions.oCustom == undefined ? {} : oOptions.oCustom;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
}

BxDolCreatePost.prototype.getForm = function (sModuleName, sModuleUri, oElement) {
    var $this = this;
    var oDate = new Date();

    var oTab = $(oElement);
    var sTabActive = 'bx-menu-tab-active';
    oTab.parents('.bx-db-menu:first').find('li.' + sTabActive).removeClass(sTabActive);
    oTab.parents('li:first').addClass(sTabActive);
    oTab.parents('.bx-popup-applied:visible').dolPopupHide();

    if($('.sys-cpf-form.sys-cpf-' + sModuleName + ' form').length > 0) {
        $('.sys-cpf-form:visible').bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
            $('.sys-cpf-form.sys-cpf-' + sModuleName).show().siblings('.sys-cpf-close:hidden').show();
        });

        return false;
    }

    this._loadingInBlock(oElement, true);

    $.get(
        this._sRootUrl + 'modules/?r=' + sModuleUri + '/get_create_post_form/', {
            ajax_mode: true,
            dynamic_mode: true,
            absolute_action_url: true,
            context_id: this._iContextId,
            custom: this._oCustom,
            _t:oDate.getTime()
        },
        function(oData) {
            $this._loadingInBlock(oElement, false);

            if(!oData || !oData.content) 
                return;

            $('.sys-cpf-form:visible').bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
                $this._loadingInBlock(oElement, true);

                $('.sys-cpf-close:visible').hide();

                var sForm = '.sys-cpf-form.sys-cpf-' + oData.module;
                if($(sForm).length == 0)
                    $('.sys-cpf-close').before($('<div class="sys-cpf-form sys-cpf-' + oData.module + '"></div>').hide());

                $(sForm).html(oData.content).show().siblings('.sys-cpf-close:hidden').show();

                $this._loadingInBlock(oElement, false);
            });
        }, 
        'json'
    );

    return false;
};

BxDolCreatePost.prototype.hideForm = function(oElement) {
    $(oElement).parents('.sys-cpf-close:first').bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed);

    var sTabActive = 'bx-menu-tab-active';
    var sTabDefault = 'bx-menu-item-' + this._sDefault;
    
    var oMiActive = $('.sys-cpf-submenu li.' + sTabActive);
    var bMiChange = oMiActive.not('.' + sTabDefault);

    if(bMiChange)
        oMiActive.removeClass(sTabActive);

    $('.sys-cpf-form:visible').bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
        $('.sys-cpf-form.sys-cpf-default').show();

        if(bMiChange)
            $('.' + sTabDefault).addClass(sTabActive);
    });
};

BxDolCreatePost.prototype._loading = function(e, bShow) {
    var oParent = $(e).length ? $(e) : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolCreatePost.prototype._loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-content:first') : $('body'); 
    bx_loading(oParent, bShow);
};

/** @} */
