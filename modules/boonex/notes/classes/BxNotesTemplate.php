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

    function unit ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html') {

        $oModule = BxDolModule::getInstance('bx_notes');
        if ($isCheckPrivateContent && CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $oModule->isAllowedView($aData))) {
            $aVars = array (
                'summary' => $sMsg,
            );
            return $this->parseHtmlByName('unit_private.html', $aVars);
        }

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
            'entry_posting_date' => bx_time_js($aData['added'], BX_FORMAT_DATE),
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

        //TODO: Rebuild using menus engine when it will be ready for such elements like Vote, Share, etc. 
        bx_import('BxDolVote');
        $oVotes = BxDolVote::getObjectInstance(BxNotesConfig::$OBJECT_VOTES, $aData[BxNotesConfig::$FIELD_ID]);
        $aVars['entry_actions'] = $oVotes->getElementBlock();

        if(BxDolRequest::serviceExists('bx_timeline', 'get_share_element_block'))
        	$aVars['entry_actions'] .= BxDolService::call('bx_timeline', 'get_share_element_block', array(bx_get_logged_profile_id(), 'bx_notes', 'added', $aData[BxNotesConfig::$FIELD_ID]));

        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function entryAuthor ($aData, $oProfile, $sTemplateName = 'author.html') {
        if (!$oProfile)
            return '';
        $aVars = array (
            'author_url' => $oProfile->getUrl(),
            'author_thumb_url' => $oProfile->getThumb(),
            'author_title' => $oProfile->getDisplayName(),
            'entry_posting_date' =>  bx_time_js($aData['added'], BX_FORMAT_DATE),
        );
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

}

/** @} */ 

