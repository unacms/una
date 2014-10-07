<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralTemplate');

/**
 * Module representation.
 */
class BxBaseModTextTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    function unit ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html')
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        if ($isCheckPrivateContent && CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $oModule->checkAllowedView($aData))) {
            $aVars = array (
                'summary' => $sMsg,
            );
            return $this->parseHtmlByName('unit_private.html', $aVars);
        }

        // get thumb url
        $sPhotoThumb = '';
        if ($aData[$CNF['FIELD_THUMB']]) {
            bx_import('BxDolImageTranscoder');
            $oImagesTranscoder = BxDolImageTranscoder::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
            if ($oImagesTranscoder)
                $sPhotoThumb = $oImagesTranscoder->getImageUrl($aData[$CNF['FIELD_THUMB']]);
        }

        // get entry url
        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]);

        bx_import('BxDolProfile');
        $oProfile = BxDolProfile::getInstance($aData[$CNF['FIELD_AUTHOR']]);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }

        // get summary
        $sLinkMore = ' <a title="' . bx_html_attribute(_t('_sys_read_more', $aData[$CNF['FIELD_TITLE']])) . '" href="' . $sUrl . '"><i class="sys-icon ellipsis-h"></i></a>';
        $sSummary = strmaxtextlen($aData[$CNF['FIELD_TEXT']], (int)getParam($CNF['PARAM_CHARS_SUMMARY']), $sLinkMore);
        $sSummaryPlain = BxTemplFunctions::getInstance()->getStringWithLimitedLength(strip_tags($sSummary), (int)getParam($CNF['PARAM_CHARS_SUMMARY_PLAIN']));

        // generate html
        $aVars = array (
            'id' => $aData[$CNF['FIELD_ID']],
            'content_url' => $sUrl,
            'title' => bx_process_output($aData[$CNF['FIELD_TITLE']]),
            'summary' => $sSummary,
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
                ),
            ),
            'bx_if:no_thumb' => array (
                'condition' => !$sPhotoThumb,
                'content' => array (
                    'content_url' => $sUrl,
                    'summary_plain' => $sSummaryPlain,
                ),
            ),
        );

        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function entryText ($aData, $sTemplateName = 'entry-text.html')
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $aVars = $aData;
        $aVars['entry_title'] = isset($aData[$CNF['FIELD_TITLE']]) ? $aData[$CNF['FIELD_TITLE']] : '';
        $aVars['entry_text'] = $aData[$CNF['FIELD_TEXT']];
        $aVars['entry_location'] = '';

        if (!empty($CNF['OBJECT_METATAGS'])) {
            bx_import('BxDolMetatags');
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            $aVars['text'] = $oMetatags->keywordsParse($aData[$CNF['FIELD_ID']], $aVars['text']);
            $aVars['entry_location'] = $oMetatags->locationsString($aData[$CNF['FIELD_ID']]);
        }

        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function entryAuthor ($aData, $sTemplateName = 'author.html')
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $oProfile = BxDolProfile::getInstance($aData[$CNF['FIELD_AUTHOR']]);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }
        if (!$oProfile)
            return '';

        $aVars = array (
            'author_url' => $oProfile->getUrl(),
            'author_thumb_url' => $oProfile->getThumb(),
            'author_title' => $oProfile->getDisplayName(),
            'author_desc' => $this->getAuthorDesc($aData),
        );
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function getAuthorDesc ($aData)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        return bx_time_js($aData[$oModule->_oConfig->CNF['FIELD_ADDED']], BX_FORMAT_DATE);
    }

    function entryAttachments ($aData)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        bx_import('BxTemplFunctions');
        bx_import('BxDolStorage');
        bx_import('BxDolImageTranscoder');

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        $oTranscoder = BxDolImageTranscoder::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);

        $aGhostFiles = $oStorage->getGhosts ($aData[$CNF['FIELD_AUTHOR']], $aData[$CNF['FIELD_ID']]);
        if (!$aGhostFiles)
            return false;

        foreach ($aGhostFiles as $k => $a) {

            $isImage = $oTranscoder && (0 == strncmp('image/', $a['mime_type'], 6)); // preview for images only and transcoder object for preview must be defined
            $sUrlOriginal = $oStorage->getFileUrlById($a['id']);
            $sImgPopupId = 'bx-messages-atachment-popup-' . $a['id'];

            // images are displayed with preview and popup upon clicking
            $aGhostFiles[$k]['bx_if:image'] = array (
                'condition' => $isImage,
                'content' => array (
                    'url_original' => $sUrlOriginal,
                    'attr_file_name' => bx_html_attribute($a['file_name']),
                    'popup_id' => $sImgPopupId,
                    'url_preview' => $oTranscoder->getImageUrl($a['id']),
                    'popup' =>  BxTemplFunctions::getInstance()->transBox($sImgPopupId, '<img src="' . $sUrlOriginal . '" />', true, true),
                ),
            );

            // non-images are displayed as text links to original file
            $aGhostFiles[$k]['bx_if:not_image'] = array (
                'condition' => !$isImage,
                'content' => array (
                    'url_original' => $sUrlOriginal,
                    'attr_file_name' => bx_html_attribute($a['file_name']),
                    'file_name' => bx_process_output($a['file_name']),
                ),
            );
        }

        $aVars = array(
            'bx_repeat:attachments' => $aGhostFiles,
        );
        return $this->parseHtmlByName('attachments.html', $aVars);
    }
}

/** @} */
