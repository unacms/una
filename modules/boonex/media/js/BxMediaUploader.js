/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MediaManager MediaManager
 * @ingroup     UnaModules
 */

/**
 * Simple Uploader js class
 */
function BxMediaUploader(sUploaderObject, sStorageObject, sUniqId, sActionsUri, options) {
    this.init(sUploaderObject, sStorageObject, sUniqId, sActionsUri, options);
    this._sIframeId = 'bx-form-input-files-' + sUniqId + '-iframe';
    this._eForm = null;
}

BxMediaUploader.prototype.init = function (sUploaderObject, sStorageObject, sUniqId, sActionsUri, options) {
    this._isUploadsInProgress = false;

    this._sUploaderObject = sUploaderObject;
    this._sStorageObject = sStorageObject;

    this._sUniqId = sUniqId;

    this._sActionsUri = sActionsUri;

    this._sUploaderJsInstance = 'glUploader_' + sUniqId + '_' + this._sUploaderObject;
    this._sUploadInProgressContainerId = 'bx-form-input-files-' + sUniqId + '-upload-in-progress-' + this._sUploaderObject;
    this._sPopupContainerId = 'bx-form-input-files-' + sUniqId + '-popup-wrapper-' + this._sUploaderObject;

    this._sResultContainerId = 'bx-form-input-files-' + sUniqId + '-upload-result';
    this._sErrorsContainerId = 'bx-form-input-files-' + sUniqId + '-errors'; 

    this._sFormContainerId = 'bx-form-input-files-' + sUniqId + '-form-cont';

    this._sTemplateGhost = options.template_ghost ? options.template_ghost : '<div id="' + this._getFileContainerId('{file_id}') + '"><input type="hidden" name="f[]" value="{file_id}" />{file_name} (<a href="javascript:void(0);" onclick="{js_instance_name}.deleteGhost(\'{file_id}\')">delete</a>)</div>';
    this._sTemplateError = options.template_error_msg ? options.template_error_msg : '<div>{error}</div>' ;
    this._sTemplateErrorGhosts = options.template_error_ghosts ? options.template_error_ghosts : this._sTemplateError;

    this._isMultiple = undefined == options.multiple || !options.multiple ? false : true;

    this._iContentId = undefined == options.content_id || '' == options.content_id ? '' : parseInt(options.content_id);

    this._sImagesTranscoder = options.images_transcoder ? options.images_transcoder : '';

    this._isPrivate = undefined == options.storage_private || parseInt(options.storage_private) ? 1 : 0;

    this._sUploaderInstanceName = options.uploader_instance_name ? options.uploader_instance_name : '';

    this._isErrorShown = false;
}

BxMediaUploader.prototype.isMultiple = function () {
    return this._isMultiple;
}

BxMediaUploader.prototype.getCurrentFilesCount = function () {
    return $('#' + $this._sResultContainerId + ' .bx-uploader-ghost').length;
}

BxMediaUploader.prototype.showUploaderForm = function () {
    var $this = this;
    var sUrl = this._getUrlWithStandardParams() + '&a=show_uploader_form&m=' + (this._isMultiple ? 1 : 0) + '&c=' + this._iContentId + '&p=' + this._isPrivate + '&_t=' + escape(new Date());

    $(window).dolPopupAjax({
        url: sUrl,
        id: {force: true, value: this._sPopupContainerId},
        onBeforeShow: function() {
            if ($this.isMultiple())
                $('#' + $this._sPopupContainerId + ' .bx-uploader-add-more-files').show();
            else
                $('#' + $this._sPopupContainerId + ' .bx-uploader-add-more-files').hide();
            $('#' + $this._sPopupContainerId + ' .bx-popup-element-close').click(function() {
                $this.onClickCancel();
            });
            if ('undefined' !== typeof($this.onBeforeShowPopup))
                $this.onBeforeShowPopup();
        },
        onShow: function () {
            if ('undefined' !== typeof($this.onShowPopup))
                $this.onShowPopup();
        },
        closeElement: false,
        closeOnOuterClick: false
    });
}

BxMediaUploader.prototype.onClickCancel = function () {
    var $this = this;
    if (this._isUploadsInProgress) {
        bx_confirm(_t('_sys_uploader_confirm_close_popup'), function() {
            $this.cancelAll();
            $('#' + $this._sPopupContainerId).dolPopupHide({});
        });
    } else {
        $('#' + this._sPopupContainerId).dolPopupHide();
    }
}

BxMediaUploader.prototype.onBeforeUpload = function (params) {

    this._eForm = params;

    this._loading(true, false);
        
    this._isUploadsInProgress = true;
    this._lockPageFromLeaving();
    this._clearErrors();
}

BxMediaUploader.prototype.onStartCopy = function (url) {
    var $this = this;

    $.post($this._sActionsUri + 'CopyFile/', { FileName: url, StorageObject: this._sStorageObject, UniqId: this._sUniqId, UploaderInstanceName: this._sUploaderInstanceName, isPrivate: this._isPrivate, ContentId: this._iContentId, isMultiple: this._isMultiple ? 1 : 0 }, function (data, textStatus) {
        eval(jQuery(data).text())
    });
}

BxMediaUploader.prototype.onStartEdit = function (params) {
    alert('not implemented')
}

BxMediaUploader.prototype.onProgress = function (params) {

}

BxMediaUploader.prototype.onUploadCompleted = function (sErrorMsg) {

    this._isUploadsInProgress = false;
    this._unlockPageFromLeaving();
    this._loading(false, true);

    if (sErrorMsg.length) {
        this.restoreGhosts();        
        this._showError(sErrorMsg);
    } else {        
        this.restoreGhosts();
        $('#' + this._sPopupContainerId).dolPopupHide({});
        eval("oObj" + this._sUploaderInstanceName + ".removeFiles()");
    }
}

BxMediaUploader.prototype.cancelAll = function () {
    $('#' + this._sIframeId).attr('src', 'javascript:false;');
    this.onUploadCompleted(_t('_sys_uploader_upload_canceled'));
}

BxMediaUploader.prototype.restoreGhosts = function () {
    var sUrl = this._getUrlWithStandardParams() + '&img_trans=' + this._sImagesTranscoder + '&a=restore_ghosts&f=json' + '&c=' + this._iContentId + '&_t=' + (new Date());
    var $this = this;

    bx_loading(this._sResultContainerId, true);

    $.getJSON(sUrl, function (aData) {
        bx_loading($this._sResultContainerId, false);
   
        if (!$this.isMultiple())
            $('#' + $this._sResultContainerId + ' .bx-uploader-ghost').remove();

        if ('object' === typeof(aData)) {
            $.each(aData, function(iFileId, oVars) {
                var oFileContainer = $('#' + $this._getFileContainerId(iFileId));
                if (oFileContainer.length > 0)
                    return;
                var sHTML;
                if (typeof $this._sTemplateGhost == 'object')
                    sHTML = $this._sTemplateGhost[iFileId];
                else
                    sHTML = $this._sTemplateGhost;
                for (var i in oVars)
                    sHTML = sHTML.replace (new RegExp('{'+i+'}', 'g'), oVars[i]);
                
                $('#' + $this._sResultContainerId).prepend(sHTML);

                oFileContainer.find('.bx-uploader-ghost-preview img').hide().fadeIn(1000);
            });
        }
    });
};

BxMediaUploader.prototype.deleteGhost = function (iFileId) {
    var sUrl = this._getUrlWithStandardParams() + '&a=delete&id=' + iFileId;
    var $this = this;

    var sFileContainerId = $this._getFileContainerId(iFileId);
    bx_loading(sFileContainerId, true);

    $.post(sUrl, {_t: new Date()}, function (sMsg) {
        bx_loading(sFileContainerId, false);
        if ('ok' == sMsg) {
            $('#' + sFileContainerId).slideUp('slow', function () {
                $(this).remove();
            });
        } else {
            $('#' + this._sResultContainerId).prepend($this._sTemplateErrorGhosts.replace('{error}', sMsg));
        }        
    });
};

BxMediaUploader.prototype._clearErrors = function () {
    $('#' + this._sPopupContainerId + ' #' + this._sErrorsContainerId).html('');
    this._isErrorShown = false;
}

BxMediaUploader.prototype._showError = function (s, bAppend) {
    if (s == undefined || !s.length)
        return;
    if (!bAppend)
        $('#' + this._sPopupContainerId + ' #' + this._sErrorsContainerId).html(this._sTemplateError.replace('{error}', s));
    else
        $('#' + this._sPopupContainerId + ' #' + this._sErrorsContainerId).prepend(this._sTemplateError.replace('{error}', s));
    this._isErrorShown = true;
};

BxMediaUploader.prototype._getFileContainerId = function (iFileId) {
    return 'bx-uploader-file-' + this._sStorageObject + '-' + iFileId;
};

BxMediaUploader.prototype._getUrlWithStandardParams = function () {
    return sUrlRoot + 'storage_uploader.php?uo=' + this._sUploaderObject + '&so=' + this._sStorageObject + '&uid=' + this._sUniqId;
}

BxMediaUploader.prototype._lockPageFromLeaving = function () {
    $(window).bind('beforeunload', function (e) {
        var e = e || window.event;
        // for ie, ff
        e.returnValue = _t('_sys_uploader_confirm_leaving_page');
        // for webkit
        return _t('_sys_uploader_confirm_leaving_page');
    });
}

BxMediaUploader.prototype._unlockPageFromLeaving = function () {
    $(window).unbind('beforeunload');
}

BxMediaUploader.prototype._loading = function (bShowProgress, bShowForm) {

    var eForm = $('#' + this._sFormContainerId + ' .bx-uploader-files-list');
    var eBtn = $('#' + this._sFormContainerId + ' .bx-btn-primary');

    if (bShowForm) {
        if (null != this._eForm) {
            eForm.find('.bx-uploader-simple-file').filter(':not(:first)').remove();
            if ('undefined' !== typeof(this._eForm.reset)) 
                this._eForm.reset();
        }
        eForm.show();
        eBtn.show();
    } else {
        eForm.hide();
        eBtn.hide();
    }

    bx_loading($('#' + this._sFormContainerId + ' .bx-uploader-loading').get(0), bShowProgress);
}

