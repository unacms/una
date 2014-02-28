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
class BxNotesTemplate extends BxDolModuleTemplate 
{
    protected static $MODULE = 'bx_notes';

    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb) 
    {
        parent::__construct($oConfig, $oDb);

        $this->addCss ('main.css');
    }

    function unit ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html') 
    {
        $oModule = BxDolModule::getInstance(self::$MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        if ($isCheckPrivateContent && CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $oModule->isAllowedView($aData))) {
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

        $sSummary = $aData[$CNF['FIELD_SUMMARY']];
        if (!$sSummary) {
			$iLimitChars = (int)getParam($CNF['PARAM_CHARS_SUMMARY']);
			$sSummary = trim(strip_tags($aData[$CNF['FIELD_TEXT']]));
            $sLinkMore = '';
            if (mb_strlen($sSummary) > $iLimitChars) {
                $sSummary = mb_substr($sSummary, 0, $iLimitChars);
                $sLinkMore = ' <a title="' . bx_html_attribute(_t('_sys_read_more', $aData[$CNF['FIELD_TITLE']])) . '" href="' . $sUrl . '"><i class="sys-icon ellipsis-horizontal"></i></a>';
            }
            $sSummary = htmlspecialchars_adv($sSummary) . $sLinkMore;
        }

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
        $oModule = BxDolModule::getInstance(self::$MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $aVars = $aData;
        $aVars['entry_title'] = $aData[$CNF['FIELD_TITLE']];
        $aVars['entry_text'] = $aData[$CNF['FIELD_TEXT']];

        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function entryAuthor ($aData, $oProfile, $sTemplateName = 'author.html') 
    {
        if (!$oProfile)
            return '';

        $oModule = BxDolModule::getInstance(self::$MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $aVars = array (
            'author_url' => $oProfile->getUrl(),
            'author_thumb_url' => $oProfile->getThumb(),
            'author_title' => $oProfile->getDisplayName(),
            'entry_posting_date' =>  bx_time_js($aData[$CNF['FIELD_ADDED']], BX_FORMAT_DATE),
        );
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

}

/** @} */ 

