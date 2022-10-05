/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

function BxMarketForm(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxMarketForm' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'fast' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
    
    var $this = this;
    jQuery(document).ready(function() {
        $this.init();
    });
}

BxMarketForm.prototype.init = function() {
    jQuery("textarea[name='cover_raw']").each(function() {
        CodeMirror.fromTextArea(this, {
            lineNumbers: true,
            mode: "htmlmixed",
            htmlMode: true,
            matchBrackets: true
        });
    });
};

BxMarketForm.prototype.initGhost = function(oGhost, iFileId, iThumbnailId, iCoverId) {
    oGhost.find('.bx-base-general-use-as-thumb input[value="' + iThumbnailId + '"]').attr('checked', true);
    if(iCoverId != undefined)
        oGhost.find('.bx-base-general-use-as-cover input[value="' + iCoverId + '"]').attr('checked', true);

    oGhost.find('.bx-base-general-intert-to-post-link').show();

    // disable images drag&drop, because resized images address can be reset after some time
    oGhost.find(".bx-base-general-icon-" + iFileId).bind('dragstart', function() {
        return false; 
    });

    oGhost.parent().children('.bx-uploader-ghost').each(function(iIndex, oElement) {
        if(iIndex > 0 && !$(oElement).hasClass('closed'))
            $(oElement).addClass('closed').find('.bx-uploader-ghost-header:first').addClass('bx-def-color-bg-hl').siblings('.bx-uploader-ghost-body:first').hide();
    });
};

BxMarketForm.prototype.toggleGhost = function(oLink) {
    $(oLink).parents('.bx-uploader-ghost:first').toggleClass('closed').find('.bx-uploader-ghost-body').resize(function() {
        $(this).parents('.bx-form-input-files-result:first').bx_show_more_check_overflow();
    }).bx_anim('toggle', this._sAnimationEffect, this._iAnimationSpeed, function() {
        $(this).siblings('.bx-uploader-ghost-header').toggleClass('bx-def-color-bg-hl');
    });
};

BxMarketForm.prototype.checkName = function(sTitleId, sNameId, iId) {
    var oDate = new Date();

    var oName = jQuery("[name='" + sNameId + "']");
    var sName = oName.val();
    var bName = sName.length != 0;

    var oTitle = jQuery("[name='" + sTitleId + "']");
    var sTitle = oTitle.val();
    var bTitle = sTitle.length != 0;

    if(!bName && !bTitle)
        return;

    var sTitleCheck = '';
    if(bName)
        sTitleCheck = sName;
    else if(bTitle) {
        sTitleCheck = sTitle;

        sTitle = sTitle.replace(/[^A-Za-z0-9_]/g, '-');
        sTitle = sTitle.replace(/[-]{2,}/g, '-');
        oName.val(sTitle);
    }

    jQuery.get(
        this._sActionsUrl + 'check_name',
        {
            title: sTitleCheck,
            id: iId && parseInt(iId) > 0 ? iId : 0,
            _t: oDate.getTime()
        },
        function(oData) {
            if(!oData || oData.name == undefined)
                return;

            oName.val(oData.name);
        },
        'json'
    );
};

BxMarketForm.prototype.changeFileType = function(oSelect) {
    var sValue = jQuery(oSelect).val();

    jQuery(oSelect).parents('.bx-uploader-ghost:first').find('.bx-uploader-ghost-type-rel:visible').bx_anim('hide', 'fade', 'fast', function() {
        jQuery(this).parent().find('.bx-uploader-ghost-type-' + sValue).bx_anim('show', 'fade', 'fast');

        jQuery(oSelect).parents('.bx-form-input-files-result:first').bx_show_more_check_overflow();
    });
};

/** @} */
