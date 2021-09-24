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
}

BxVideosEmbeds.prototype.changeVideoSource = function(sSource) {
	if (sSource == 'upload') {
        $('#bx-form-element-videos').show();
        $('#bx-form-element-video_embed').hide();
    } else {
	    $('#bx-form-element-videos').hide();
        $('#bx-form-element-video_embed').show();
    }
};

BxVideosEmbeds.prototype.onNewEmbedCode = function(sCode) {
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

/** @} */
