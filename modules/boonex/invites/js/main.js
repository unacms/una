function BxInvMain(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oInvMain' : oOptions.sObjName;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sObjNameGrid = oOptions.sObjNameGrid;
    this._sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;
}

BxInvMain.prototype.initRequestForm = function(sFormId) {
    var oForm = $('#' + sFormId);
    if(!oForm.length)
        return;

    oForm.ajaxForm({
        dataType: 'json',
        beforeSubmit: function (formData, jqForm, options) {
            bx_loading(oForm, true);
        },
        success: function (oData) {
            bx_loading(oForm, false);

            processJsonData(oData);
        }
    });
};

BxInvMain.prototype.onRequestFormSubmit = function(oData) {
    var $this = this;

    if(!oData || !oData.content || !oData.content_id)
        return;

    $('#' + oData.content_id).bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
        $(this).replaceWith(oData.content);

        $this.initRequestForm(oData.content_id);
    });
};

BxInvMain.prototype.initAcceptForm = function(sFormId) {
    var oForm = $('#' + sFormId);
    if(!oForm.length)
        return;

    oForm.ajaxForm({
        dataType: 'json',
        beforeSubmit: function (formData, jqForm, options) {
            bx_loading(oForm, true);
        },
        success: function (oData) {
            bx_loading(oForm, false);

            processJsonData(oData);
        }
    });
};

BxInvMain.prototype.showLinkPopup = function(oElement) {
    var $this = this;

    this.loadingInButton(oElement, true);

    jQuery.get(
        this._sActionsUrl + 'get_link/',
        this._getDefaultData(),
        function(oData) {
            $this.loadingInButton(oElement, false);

            if(oData && oData.popup != undefined) {
                var oPopup = $(oData.popup);
                var sPopupId = oPopup.attr('id');
                var oClipboard = null;

                $('#' + sPopupId).remove();
                oPopup.hide().prependTo('body').dolPopup({
                    onShow: function () {
                        oClipboard = new ClipboardJS('#' + $this._aHtmlIds['link_popup'] + ' .bx-btn[name = "clipboard"]', {
                            target: function(oTrigger) {
                                return $('#' + sPopupId).find('[name = "link"]').get(0);
                            }
                        });
                        oClipboard.on('success', function(oObject) {
                            $this.hideLinkPopup();
                        });
                    },
                    onHide: function () {
                        if(oClipboard)
                            oClipboard.destroy();
                    }
                });
            }

            if(oData && oData.message != undefined)
                    bx_alert(oData.message);
        },
        'json'
    );
};

BxInvMain.prototype.showCodePopup = function(oElement) {
    var $this = this;

    this.loadingInButton(oElement, true);

    jQuery.get(
        this._sActionsUrl + 'get_code/',
        this._getDefaultData(),
        function(oData) {
            $this.loadingInButton(oElement, false);

            if(oData && oData.popup != undefined) {
                var oPopup = $(oData.popup);
                var sPopupId = oPopup.attr('id');
                var oClipboard = oClipboardLink = null;

                $('#' + sPopupId).remove();
                oPopup.hide().prependTo('body').dolPopup({
                    onShow: function () {
                        oClipboard = new ClipboardJS('#' + $this._aHtmlIds['code_popup'] + ' .bx-btn[name = "clipboard"]', {
                            target: function(oTrigger) {
                                return $('#' + sPopupId).find('[name = "code"]').get(0);
                            }
                        });
                        oClipboard.on('success', function(oObject) {
                            $this.hideCodePopup();
                        });
                        
                        oClipboardLink = new ClipboardJS('#' + $this._aHtmlIds['code_popup'] + ' .bx-btn[name = "clipboard_link"]', {
                            target: function(oTrigger) {
                                return $('#' + sPopupId).find('[name = "link"]').get(0);
                            }
                        });
                        oClipboardLink.on('success', function(oObject) {
                            $this.hideCodePopup();
                        });
                    },
                    onHide: function () {
                        if(oClipboard)
                            oClipboard.destroy();
                        if(oClipboardLink)
                            oClipboardLink.destroy();
                    }
                });
            }

            if(oData && oData.message != undefined)
                bx_alert(oData.message);
        },
        'json'
    );
};

BxInvMain.prototype.hideLinkPopup = function() {
    $('#' + this._aHtmlIds['link_popup']).dolPopupHide();	
};

BxInvMain.prototype.hideCodePopup = function() {
    $('#' + this._aHtmlIds['code_popup']).dolPopupHide();	
};

BxInvMain.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);
};

BxInvMain.prototype._loading = function(e, bShow) {
    var oParent = $(e).length ? $(e) : $('body'); 
    bx_loading(oParent, bShow);
};

BxInvMain.prototype._getDefaultData = function () {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

BxInvMain.prototype.onChangeFilter = function (oFilter) {
    var $this = this;
	var oFilter1 = $('#bx-grid-filter1-' + this._sObjNameGrid);
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

BxInvMain.prototype.setFilter = function (sFilter, isReload) {

    if (this._sFilter == sFilter)
        return;

    this._sFilter = sFilter;

    if (isReload) {
        if (sFilter.length > 0)
            this.reload(0);
        else
            this.reload();
    }
};
