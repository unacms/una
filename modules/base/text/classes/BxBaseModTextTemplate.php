<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Module representation.
 */
class BxBaseModTextTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    function unit ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html', $aParams = array())
    {
    	$sResult = $this->checkPrivacy ($aData, $isCheckPrivateContent, $this->getModule());
    	if($sResult)
            return $sResult;

		return $this->parseHtmlByName($sTemplateName, $this->getUnit($aData, $aParams));
    }

    function entryAuthor ($aData, $iProfileId = false, $sFuncAuthorDesc = 'getAuthorDesc', $sTemplateName = 'author.html', $sFuncAuthorAddon = 'getAuthorAddon')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        if (!$iProfileId)
            $iProfileId = $aData[$CNF['FIELD_AUTHOR']];

        $oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        if (!$oProfile)
            return '';

        $sAddon = $sFuncAuthorAddon ? $this->$sFuncAuthorAddon($aData, $oProfile) : '';

        $aVars = array (
            'author_url' => $oProfile->getUrl(),
            'author_thumb_url' => $oProfile->getThumb(),
            'author_title' => $oProfile->getDisplayName(),
            'author_desc' => $sFuncAuthorDesc ? $this->$sFuncAuthorDesc($aData) : '',
            'bx_if:addon' => array (
                'condition' => (bool)$sAddon,
                'content' => array (
                    'content' => $sAddon,
                ),
            ),
        );
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function getAuthorDesc ($aData)
    {
        return '';
    }

    function getAuthorAddon ($aData, $oProfile)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        $sUrl = 'page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id=' . $oProfile->id();
        $sUrl = BxDolPermalinks::getInstance()->permalink($sUrl);
        return _t($CNF['T']['txt_all_entries_by'], $sUrl, $oProfile->getDisplayName(), $this->getModule()->_oDb->getEntriesNumByAuthor($oProfile->id()));
    }

    function entryAttachments ($aData)
    {
        if (!($a = $this->getAttachments($this->getModule()->_oConfig->CNF['OBJECT_STORAGE'], $aData)))
            return '';
    	return $this->parseHtmlByName('attachments.html', array(
            'bx_repeat:attachments' => $a,
        ));
    }

	function entryAttachmentsByStorage ($sStorage, $aData)
    {
        if (!($a = $this->getAttachments($sStorage, $aData)))
            return '';
    	return $this->parseHtmlByName('attachments.html', array(
            'bx_repeat:attachments' => $a,
        ));
    }

    function entryAllActions ($sActionsEntity, $sActionsSocial)
    {
        $aVars = array (
            'actions_entity' => $sActionsEntity,
            'actions_social' => $sActionsSocial,
        );
        return $this->parseHtmlByName('entry-all-actions.html', $aVars);
    }

    protected function checkPrivacy ($aData, $isCheckPrivateContent, $oModule)
    {
        if ($isCheckPrivateContent && CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $oModule->checkAllowedView($aData))) {
            $aVars = array (
                'summary' => $sMsg,
            );
            return $this->parseHtmlByName('unit_private.html', $aVars);
        }

        return '';
    }

	protected function getUnit ($aData, $aParams = array())
    {
        $CNF = &BxDolModule::getInstance($this->MODULE)->_oConfig->CNF;

        // get thumb url
        $sPhotoThumb = '';
        $sPhotoGallery = '';
        if ($aData[$CNF['FIELD_THUMB']]) {

            $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
            if ($oImagesTranscoder)
                $sPhotoThumb = $oImagesTranscoder->getFileUrl($aData[$CNF['FIELD_THUMB']]);

            $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']);
            if ($oImagesTranscoder)
                $sPhotoGallery = $oImagesTranscoder->getFileUrl($aData[$CNF['FIELD_THUMB']]);
            else
                $sPhotoGallery = $sPhotoThumb;
        }

        // get entry url
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]);

        $oProfile = BxDolProfile::getInstance($aData[$CNF['FIELD_AUTHOR']]);
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        // get summary
        $sLinkMore = ' <a title="' . bx_html_attribute(_t('_sys_read_more', $aData[$CNF['FIELD_TITLE']])) . '" href="' . $sUrl . '"><i class="sys-icon ellipsis-h"></i></a>';
        $sSummary = strmaxtextlen($aData[$CNF['FIELD_TEXT']], (int)getParam($CNF['PARAM_CHARS_SUMMARY']), $sLinkMore);
        $sSummaryPlain = BxTemplFunctions::getInstance()->getStringWithLimitedLength(strip_tags($sSummary), (int)getParam($CNF['PARAM_CHARS_SUMMARY_PLAIN']));

        $sText = $aData[$CNF['FIELD_TEXT']];
        if (!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
    
            // keywords
            if ($oMetatags->keywordsIsEnabled())
                $sText = $oMetatags->keywordsParse($aData[$CNF['FIELD_ID']], $sText);
        }

        // generate html
        return array (
            'id' => $aData[$CNF['FIELD_ID']],
            'content_url' => $sUrl,
            'title' => bx_process_output($aData[$CNF['FIELD_TITLE']]),
            'summary' => $sSummary,
            'text' => $sText,
            'author' => $oProfile->getDisplayName(),
            'author_url' => $oProfile->getUrl(),
            'entry_posting_date' => bx_time_js($aData[$CNF['FIELD_ADDED']], BX_FORMAT_DATE),
            'module_name' => _t($CNF['T']['txt_sample_single']),
            'ts' => $aData[$CNF['FIELD_ADDED']],
            'bx_if:thumb' => array (
                'condition' => $sPhotoThumb,
                'content' => array (
                    'title' => bx_process_output($aData[$CNF['FIELD_TITLE']]),
                    'summary_attr' => bx_html_attribute($sSummaryPlain),
                    'content_url' => $sUrl,
                    'thumb_url' => $sPhotoThumb ? $sPhotoThumb : '',
                    'gallery_url' => $sPhotoGallery ? $sPhotoGallery : '',
                    'strecher' => str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', 40),
                ),
            ),
            'bx_if:no_thumb' => array (
                'condition' => !$sPhotoThumb,
                'content' => array (
                    'content_url' => $sUrl,
                    'summary_plain' => $sSummaryPlain,
                    'strecher' => mb_strlen($sSummaryPlain) > 240 ? '' : str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', round((240 - mb_strlen($sSummaryPlain)) / 6)),
                ),
            ),
        );
    }

	protected function getAttachments ($sStorage, $aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $oStorage = BxDolStorage::getObjectInstance($sStorage);
        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        $aTranscodersVideo = false;

        if ($CNF['OBJECT_VIDEOS_TRANSCODERS'])
            $aTranscodersVideo = array (
                'poster' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']),
                'mp4' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']),
                'webm' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['webm']),
            );

        $aGhostFiles = $oStorage->getGhosts ($aData[$CNF['FIELD_AUTHOR']], $aData[$CNF['FIELD_ID']]);
        if (!$aGhostFiles)
            return false;

        foreach ($aGhostFiles as $k => $a) {
            // don't show thumbnail in attachments
            if ($a['id'] == $aData[$CNF['FIELD_THUMB']])
                unset($aGhostFiles[$k]);
        }
        if (!$aGhostFiles)
            return false;

        foreach ($aGhostFiles as $k => $a) {

            $isImage = $oTranscoder && (0 == strncmp('image/', $a['mime_type'], 6)); // preview for images, transcoder object for preview must be defined
            $isVideo = $aTranscodersVideo && (0 == strncmp('video/', $a['mime_type'], 6)); // preview for videos, transcoder object for video must be defined
            $sUrlOriginal = $oStorage->getFileUrlById($a['id']);
            $sImgPopupId = 'bx-messages-atachment-popup-' . $a['id'];

            // images are displayed with preview and popup upon clicking
            $aGhostFiles[$k]['bx_if:image'] = array (
                'condition' => $isImage,
                'content' => array (
                    'url_original' => $sUrlOriginal,
                    'attr_file_name' => bx_html_attribute($a['file_name']),
                    'popup_id' => $sImgPopupId,
                    'url_preview' => $isImage ? $oTranscoder->getFileUrl($a['id']) : '',
                    'popup' =>  BxTemplFunctions::getInstance()->transBox($sImgPopupId, '<img src="' . $sUrlOriginal . '" />', true, true),
                ),
            );

            // videos are displayed inline
            $aGhostFiles[$k]['bx_if:video'] = array (
                'condition' => $isVideo,
                'content' => array (
                    'video' => $isVideo && $aTranscodersVideo ? BxTemplFunctions::getInstance()->videoPlayer(
                        $aTranscodersVideo['poster']->getFileUrl($a['id']), 
                        $aTranscodersVideo['mp4']->getFileUrl($a['id']), 
                        $aTranscodersVideo['webm']->getFileUrl($a['id']),
                        false, ''
                    ) : '',
                ),
            );

            // non-images are displayed as text links to original file
            $aGhostFiles[$k]['bx_if:not_image'] = array (
                'condition' => !$isImage && !$isVideo,
                'content' => array (
                    'url_original' => $sUrlOriginal,
                    'attr_file_name' => bx_html_attribute($a['file_name']),
                    'file_name' => bx_process_output($a['file_name']),
                ),
            );
        }

        return $aGhostFiles;
    }
}

/** @} */
