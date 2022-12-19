/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

function bx_base_general_select_thumb(eCheckbox) {
	bx_base_general_select_ghost(eCheckbox, 'thumb');
}

function bx_base_general_select_ghost(eCheckbox, sName) {
	var eCont = $(eCheckbox).parents('.bx-form-input-files-result');
    if (!eCont.length)
        return;

    eCont.find('.bx-base-general-use-as-' + sName + ' input:checked').each (function () {        
        if($(this).prop('id') != $(eCheckbox).prop('id'))
        	$(this).prop('checked', false);
    });
}

function bx_base_general_delete_ghost (iFileId, sFileUrl, sFileIcon, aEditors, oUploaderInstance) {
    if ('undefined' !== typeof(bx_editor_remove_img)) {
        bx_editor_remove_img (
            aEditors,
            ['img[src="' + sFileIcon + '"]', 'img[src="' + sFileUrl + '"]', '#bx-base-general-img-' + iFileId, '.bx-base-general-icon-' + iFileId]
        );
    }
    oUploaderInstance.deleteGhost(iFileId);
}

function bx_base_general_insert_to_post (iFileId, sFileUrl, sEditorId, sMimeType, sEmbedBaseUrl) {
    if ('undefined' !== typeof(sMimeType) && sMimeType.startsWith('video/') && 'undefined' !== typeof(sEmbedBaseUrl))
        bx_base_general_insert_video_embed (sEditorId, 'bx-base-general-img-' + iFileId, sEmbedBaseUrl + iFileId, 'bx-base-general-img');
    else if ('undefined' !== typeof(sMimeType) && sMimeType.startsWith('audio/') && 'undefined' !== typeof(sEmbedBaseUrl))
        bx_base_general_insert_audio_embed (sEditorId, 'bx-base-general-img-' + iFileId, sEmbedBaseUrl + iFileId, 'bx-base-general-img');
    else
        bx_editor_insert_img (sEditorId, 'bx-base-general-img-' + iFileId, sFileUrl, 'bx-base-general-img');
}

function bx_base_general_insert_video_embed (sEditorId, sHtmlId, sEmbedUrl, sClass)
{    
    bx_editor_insert_html (sEditorId, sHtmlId, '<iframe width="560" height="315" src="' + sEmbedUrl + '" frameborder="0" allow="autoplay; picture-in-picture" allowfullscreen></iframe>');
}

function bx_base_general_insert_audio_embed (sEditorId, sHtmlId, sEmbedUrl, sClass)
{    
    bx_editor_insert_html (sEditorId, sHtmlId, '<iframe width="320" height="52" src="' + sEmbedUrl + '" frameborder="0" allow="autoplay; picture-in-picture" allowfullscreen></iframe>');
}

/** @} */
