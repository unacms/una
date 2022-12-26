/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

function BxVideosEmbeds(oOptions) {
	this._sActionsUrl = oOptions.sActionUrl;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

    this._hTypingTimer = null;
    this._sLastCodeChecked = '';
}

BxVideosEmbeds.prototype.changeVideoSource = function(oObj) {
    sSource = $(oObj).val();
	if (sSource == 'upload') {
        $(oObj).parents('form').find('#bx-form-element-videos').show();
        $(oObj).parents('form').find('#bx-form-element-video_embed').hide();
    } else {
	    $(oObj).parents('form').find('#bx-form-element-videos').hide();
        $(oObj).parents('form').find('#bx-form-element-video_embed').show();
    }
};

BxVideosEmbeds.prototype.onNewEmbedCode = function() {
    if (this._hTypingTimer) {
        clearTimeout(this._hTypingTimer);
        this._hTypingTimer = null;
    }

    var sCode = $('#bx-video-embed-link').val();

    if (this._sLastCodeChecked && this._sLastCodeChecked == sCode) return;
    this._sLastCodeChecked = sCode;
	$.get(this._sActionsUrl + 'parse_embed_link/', {code: sCode}, function(sEmbed){
	    if (!sEmbed.length) {
            $('#bx-videos-input-embed-preview').fadeOut(function () {
                $('#bx-videos-input-embed-preview > div.bx-videos-iframe-aspect-ratio').html('');
            });
        } else {
            $('#bx-videos-input-embed-preview > div.bx-videos-iframe-aspect-ratio').html(sEmbed);
            $('#bx-videos-input-embed-preview').fadeIn();
        }
    });
};

BxVideosEmbeds.prototype.onNewEmbedCodeTyping = function(iDelay) {
	if (this._hTypingTimer) clearTimeout(this._hTypingTimer);
	var $this = this;
	this._hTypingTimer = setTimeout(function(){
	    $this.onNewEmbedCode();
    }, iDelay);
};

/** @} */
