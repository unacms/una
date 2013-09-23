<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleTemplate');

/*
 * Notes module representation.
 */
class BxNotesTemplate extends BxDolModuleTemplate {

    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb) {
        parent::__construct($oConfig, $oDb);        
        $this->addCss ('main.css');
    }

    function unit ($aData, $sTemplateName = 'unit.html') {

        // get thumb url
        $sPhotoThumb = '';
        if ($aData[BxNotesConfig::$FIELD_THUMB]) {
            bx_import('BxDolImageTranscoder');
            $oImagesTranscoder = BxDolImageTranscoder::getObjectInstance(BxNotesConfig::$OBJECT_IMAGES_TRANSCODER_PREVIEW);
            if ($oImagesTranscoder)
                $sPhotoThumb = $oImagesTranscoder->getImageUrl($aData[BxNotesConfig::$FIELD_THUMB]);
        }

        // get note url
        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-note&id=' . $aData['id']);

        bx_import('BxDolProfile');
        $oProfile = BxDolProfile::getInstance($aData['author']);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }

        $sSummary = $aData['summary'];
        if (!$sSummary) {
			$iLimitChars = (int)getParam('bx_notes_summary_chars');
			$sSummary = trim(strip_tags($aData['text']));
            $sLinkMore = '';
            if (mb_strlen($sSummary) > $iLimitChars) {
                $sSummary = mb_substr($sSummary, 0, $iLimitChars);
                $sLinkMore = ' <a title="' . htmlspecialchars_adv(_t('_bx_notes_txt_read_more')) . '" href="' . $sUrl . '">&hellip;</a>';
            }
            $sSummary = htmlspecialchars_adv($sSummary) . $sLinkMore;
        }

        // generate html
        $aVars = array (
            'id' => $aData['id'],            
            'content_url' => $sUrl,
            'title' => bx_process_output($aData['title']),
            'summary' => $sSummary,
            'author' => $oProfile->getDisplayName(),
            'author_url' => $oProfile->getUrl(),
            'when' => defineTimeInterval($aData['added']),
            'bx_if:thumb' => array (
                'condition' => $sPhotoThumb,
                'content' => array (
                    'title' => bx_process_output($aData['title']),
                    'thumb_url' => $sPhotoThumb ? $sPhotoThumb : '',
                ),
            ),
        );

        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function entryText ($aData, $sTemplateName = 'entry-text.html') {
        $aVars = $aData;
        $aVars['entry_title'] = $aData[BxNotesConfig::$FIELD_TITLE];
        $aVars['entry_text'] = $aData[BxNotesConfig::$FIELD_TEXT];
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

}

/** @} */ 

