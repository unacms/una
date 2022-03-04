<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxMarketTemplate extends BxBaseModTextTemplate
{
	protected $_aCurrency;

    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_market';

        parent::__construct($oConfig, $oDb);

        $this->_aCurrency = $this->_oConfig->getCurrency();
    }

    public function entryRating($aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $sVotes = '';
        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $aData['id']);
        if($oVotes) {
            $sVotes = $oVotes->getElementBlock(array('show_counter' => true, 'show_legend' => true));
            if(!empty($sVotes))
                $sVotes = $this->parseHtmlByName('entry-rating.html', array(
                'content' => $sVotes,
            ));
        }

        return $sVotes; 
    }

    public function getTmplVarsText($aData)
    {
        $oModule = $this->getModule();
        $CNF = &$oModule->_oConfig->CNF;

        $aVars = parent::getTmplVarsText($aData);

        $sIcon = '';
        $mixedIcon = $oModule->getEntryImageData($aData, 'FIELD_THUMB', array('OBJECT_IMAGES_TRANSCODER_THUMB', 'OBJECT_IMAGES_TRANSCODER_GALLERY'));
        if($mixedIcon !== false) {
            if(!empty($mixedIcon['object']))
                $o = BxDolStorage::getObjectInstance($mixedIcon['object']);
            else if(!empty($mixedIcon['transcoder']))
                $o = BxDolTranscoder::getObjectInstance($mixedIcon['transcoder']);

            if($o)
                $sIcon = $o->getFileUrlById($mixedIcon['id']);
        }

        $aVars['bx_if:show_icon'] = array(
            'condition' => !empty($sIcon),
            'content' => array(
                'entry_icon' => $sIcon
            )
        );

        $sScreenshots = $this->getScreenshots($aData);
        $bScreenshots = !empty($sScreenshots);
        if($bScreenshots) {
            $this->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'flickity/|flickity.css');
            $this->addJs('flickity/flickity.pkgd.min.js');
        }

        $aVars['bx_if:show_screenshots'] = array(
            'condition' => $bScreenshots,
            'content' => array(
                'screenshots' => $sScreenshots
            )
        );
        
        return $aVars;
    }

    public function getScreenshots($aData)
    {
    	$oModule = $this->getModule();

    	$CNF = &$oModule->_oConfig->CNF;
    	$aPhotos = $oModule->serviceGetScreenshots($aData[$CNF['FIELD_ID']]);
		if(empty($aPhotos) || !is_array($aPhotos))
    		return '';

		return $this->parseHtmlByName('entry-screenshots.html', array(
    		'bx_repeat:items' => $aPhotos
    	));
    }
    
    public function getAuthorAddon ($aData, $oProfile)
    {
        $aAccount = $oProfile->getAccountObject()->getInfo();

        $sContent = $this->parseHtmlByName('entry-author.html', array(
    		'joined' => bx_time_js($aAccount['added']),
	    	'last_active' => bx_time_js($aAccount['logged']),
	    	'installs' => '?',
    	));

    	return $sContent . parent::getAuthorAddon ($aData, $oProfile);
    }

    public function getGhostTemplateFileOptions($sField, $aFile, $aVersions)
    {
    	$bField = !empty($aFile) && is_array($aFile) && isset($aFile[$sField]);
    	
    	$aTmplVarsVersions = array();
    	foreach ($aVersions as $aVersion) {
    		if($aVersion['type'] != 'version' || empty($aVersion['version']))
    			continue;

			$aTmplVarsVersions[] = array(
				'value' => $aVersion['version'],
				'title' =>  $aVersion['version'],
				'bx_if:selected' => array(
					'condition' => $bField && $aFile[$sField] == $aVersion['version'],
					'content' => array()
				)
			);
    	}

    	return $this->parseHtmlByName('form_ghost_template_file_options.html', array(
    		'bx_repeat:versions' => $aTmplVarsVersions
    	));    	
    }

    protected function getUnit ($aData, $aParams = array())
    {
        $bTmplVarsSectionAuthor = true;
        $bTmplVarsSectionPricing = false;
        $bTmplVarsSectionVoting = true;

        $oModule = $this->getModule();
    	$CNF = &$oModule->_oConfig->CNF;

    	$aUnit = parent::getUnit($aData, $aParams);
        $oPayment = BxDolPayments::getInstance();
        $oPermalinks = BxDolPermalinks::getInstance();

        list($sAuthorName, $sAuthorUrl, $sAuthorIcon, $sAuthorUnit, $sAuthorUnitShort) = $oModule->getUserInfo($aData[$CNF['FIELD_AUTHOR']]);

        $aTmplVarsSectionAuthor = array();
        if($bTmplVarsSectionAuthor) {
            //--- Author Info
            $bAuthorIcon = !empty($sAuthorIcon);
            $sAuthorName = $this->parseHtmlByName('author_link.html', array(
                'href' => $sAuthorUrl,
                'title' => bx_html_attribute($sAuthorName),
                'content' => $sAuthorName,
                'bx_if:show_account' => array(
                    'condition' => false,
                    'content' => array()
                )
            ));

            //--- Actions
            $sActions = '';
            $oActions = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_ACTIONS_SNIPPET']);
            if($oActions) {
                $oActions->setContentId($aData[$CNF['FIELD_ID']]);
                $sActions = $oActions->getCode();
            }

            $sDate = $this->parseHtmlByName('snippet-date.html', array(
                'date' => bx_time_js($aData[$CNF['FIELD_ADDED']])
            ));

            $aTmplVarsSectionAuthor = array(
                'actions' => $sActions,
                'author_unit' => $sAuthorUnitShort,
                'author_width_date' => _t('_bx_market_txt_author_added_product', $sAuthorName, $sDate),
            );
        }

        //--- Main Info
        $sUrl = BX_DOL_URL_ROOT . $oPermalinks->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]);
        $sLinkMore = ' <a title="' . bx_html_attribute(_t('_sys_read_more', $aData[$CNF['FIELD_TITLE']])) . '" href="' . $sUrl . '"><i class="sys-icon ellipsis-h"></i></a>';
        $sSummary = strmaxtextlen($aData[$CNF['FIELD_TEXT']], (int)getParam($CNF['PARAM_CHARS_SUMMARY']), $sLinkMore);
        $sSummaryPlain = BxTemplFunctions::getInstance()->getStringWithLimitedLength(strip_tags($sSummary), (int)getParam($CNF['PARAM_CHARS_SUMMARY_PLAIN']));
        

        //--- Icon Info
        $sIconUrl = '';
        if(!empty($CNF['FIELD_THUMB']) && $aData[$CNF['FIELD_THUMB']]) {
            $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_ICON']);
            if($oImagesTranscoder)
                $sIconUrl = $oImagesTranscoder->getFileUrl($aData[$CNF['FIELD_THUMB']]);

            if(empty($sIconUrl)) {
                $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_THUMB']);
                if($oImagesTranscoder)
                    $sIconUrl = $oImagesTranscoder->getFileUrl($aData[$CNF['FIELD_THUMB']]);
            }
        }

        //--- Cover Info
        $sCoverUrl = '';
        if(!empty($CNF['FIELD_COVER']) && $aData[$CNF['FIELD_COVER']]) {
            $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']);
                if($oImagesTranscoder)
                    $sCoverUrl = $oImagesTranscoder->getFileUrl($aData[$CNF['FIELD_COVER']]);

            if(empty($sCoverUrl)) {
                $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_COVER']);
                if($oImagesTranscoder)
                    $sCoverUrl = $oImagesTranscoder->getFileUrl($aData[$CNF['FIELD_COVER']]);
            }
        }

        $aTmplVarsSectionPricing = array();
        if($bTmplVarsSectionPricing) {
            //--- Price Single
            $bTmplVarsSingle = (float)$aData[$CNF['FIELD_PRICE_SINGLE']] != 0;
            $aTmplVarsSingle = array();
            if($bTmplVarsSingle) {
                $aJsSingle = $oPayment->getAddToCartJs($aData[$CNF['FIELD_AUTHOR']], $this->_oConfig->getName(), $aData[$CNF['FIELD_ID']], 1, true);
                if(!empty($aJsSingle) && is_array($aJsSingle)) {
                    list($sJsCode, $sSingleOnclick) = $aJsSingle;

                    $aTmplVarsSingle = array(
                        'price_single_onclick' => $sSingleOnclick,
                        'price_single' => _t('_bx_market_txt_price_single', $this->_aCurrency['sign'], $aData[$CNF['FIELD_PRICE_SINGLE']])
                    );
                }
                else 
                    $bTmplVarsSingle = false;
            }

            //--- Price Recurring
            $bTmplVarsRecurring = !$oPayment->isCreditsOnly() && (float)$aData[$CNF['FIELD_PRICE_RECURRING']] != 0;
            $aTmplVarsRecurring = array();
            if($bTmplVarsRecurring) {
                $aJsRecurring = $oPayment->getSubscribeJs($aData[$CNF['FIELD_AUTHOR']], '', $this->_oConfig->getName(), $aData[$CNF['FIELD_ID']], 1);
                if(!empty($aJsRecurring) && is_array($aJsRecurring)) {
                    list($sJsCode, $sRecurringOnclick) = $aJsRecurring;

                    $aTmplVarsRecurring = array(
                        'price_recurring_onclick' => $sRecurringOnclick,
                        'price_recurring' => _t('_bx_market_txt_price_recurring', $this->_aCurrency['sign'], $aData[$CNF['FIELD_PRICE_RECURRING']], _t($CNF['T']['txt_per_' . $aData[$CNF['FIELD_DURATION_RECURRING']] . '_short']))
                    );
                }
                else 
                    $bTmplVarsRecurring = false;
            }

            $aTmplVarsSectionPricing = array(
                'bx_if:show_single' => array(
                    'condition' => $bTmplVarsSingle,
                    'content' => $aTmplVarsSingle
                ),
                'bx_if:show_recurring' => array(
                    'condition' => $bTmplVarsRecurring,
                    'content' => $aTmplVarsRecurring
                ),
                'bx_if:show_free' => array(
                    'condition' => !$bTmplVarsSingle && !$bTmplVarsRecurring,
                    'content' => array(
                        'price_free_href' => $oPermalinks->permalink('page.php?i=' . $CNF['URI_DOWNLOAD_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']])
                    )
                )
            );
        }

        $aTmplVarsSectionVoting = array();
        if($bTmplVarsSectionVoting) {
            $sVoting = '';
            $oVoting = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $aData[$CNF['FIELD_ID']]);
            if($oVoting)
                $sVoting = $oVoting->getElementBlock(array('show_counter' => false));

            $sPricing = "";
            if((float)$aData[$CNF['FIELD_PRICE_RECURRING']] != 0) {
                $sDuration = 'txt_per_' . $aData[$CNF['FIELD_DURATION_RECURRING']] . '_short';
                $sDuration = _t(!empty($CNF['T'][$sDuration]) ? $CNF['T'][$sDuration] : '_bx_market_txt_per_' . $aData[$CNF['FIELD_DURATION_RECURRING']] . '_short');

                $sPricing = _t('_bx_market_txt_price_recurring_short', $this->_aCurrency['sign'], $aData[$CNF['FIELD_PRICE_RECURRING']], $sDuration);
            }

            if(empty($sPricing) && (float)$aData[$CNF['FIELD_PRICE_SINGLE']] != 0)
                $sPricing = _t('_bx_market_txt_price_single_short', $this->_aCurrency['sign'], $aData[$CNF['FIELD_PRICE_SINGLE']]);

            if(empty($sPricing))
                 $sPricing = _t('_bx_market_txt_price_free');

            $aTmplVarsSectionVoting = array(
                'voting' => $sVoting,
                'pricing' => $sPricing
            );
        }

    	$aUnit = array_merge($aUnit, array(
    	    'bx_if:show_section_author' => array(
    	        'condition' => $bTmplVarsSectionAuthor,
    	        'content' => $aTmplVarsSectionAuthor
    	    ),
            'bx_if:show_icon' => array (
                'condition' => $sIconUrl,
                'content' => array(
                	'icon_url' => $sIconUrl,
                ),
            ),
            'bx_if:show_icon_empty' => array (
                'condition' => !$sIconUrl,
                'content' => array(
                    'icon' => $CNF['ICON']
                ),
            ),
            'bx_if:show_cover' => array (
                'condition' => $sCoverUrl,
                'content' => array (
                    'summary_attr' => bx_html_attribute($sSummaryPlain),
                    'content_url' => $sUrl,
                    'cover_url' => $sCoverUrl,
                    'strecher' => str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', 40),
                ),
            ),
            'bx_if:show_cover_empty' => array (
                'condition' => !$sCoverUrl,
                'content' => array (
                    'summary_plain' => $sSummaryPlain,
                    'strecher' => mb_strlen($sSummaryPlain) > 240 ? '' : str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', round((240 - mb_strlen($sSummaryPlain)) / 6)),
                ),
            ),
            'author_name' => $sAuthorName,
            'content_url' => $sUrl,
    		'bx_if:show_section_pricing' => array(
                'condition' => $bTmplVarsSectionPricing,
                'content' => $aTmplVarsSectionPricing
            ),
            'bx_if:show_section_voting' => array(
            	'condition' => $bTmplVarsSectionVoting,
                'content' => $aTmplVarsSectionVoting
            )
    	));

    	return $aUnit;
    }

    protected function getAttachments($sStorage, $aData, $aParams = array())
    {
        $mixedAttachments = parent::getAttachments($sStorage, $aData, $aParams);
        if(empty($mixedAttachments) || !is_array($mixedAttachments))
            return $mixedAttachments;

        $aAttachments = [];
        foreach($mixedAttachments as $aAttachment)
            $aAttachments[(int)$aAttachment['id']] = $aAttachment;       

        $aFiles = $this->_oDb->getFile(['type' => 'content_id_key_file_id', 'content_id' => $aData['id']]);

        $aResults = [];
        $sCaptionType = "";
        foreach($aFiles as $iAttachmentId => $aFile) {
            $aAttachments[$iAttachmentId]['class'] = (int)$aData['package'] == $iAttachmentId ? ' bx-market-attachment-main' : '';
            $aAttachments[$iAttachmentId]['bx_if:main'] = [
                'condition' => (int)$aData['package'] == $iAttachmentId,
                'content' => []
            ];

            $bSeparatorNeeded = false;
            if ($aFiles[$iAttachmentId]['type'] != $sCaptionType){
                $bSeparatorNeeded = true;
                $sCaptionType = $aFiles[$iAttachmentId]['type'];
            }

            if ((int)$aData['package'] == $iAttachmentId){
                $bSeparatorNeeded = true;
                $sCaptionType = 'latest';
            }

            $bAttachment = !empty($aFiles[$iAttachmentId]);
            $aAttachments[$iAttachmentId]['bx_if:not_image']['content'] = array_merge($aAttachments[$iAttachmentId]['bx_if:not_image']['content'], [
                'file_type' => $bAttachment ? _t('_bx_market_form_entry_input_files_type_' . $aFiles[$iAttachmentId]['type']) : '',
                'file_name_attr' => bx_html_attribute($aAttachments[$iAttachmentId]['file_name']),
                'bx_if:show_version' => [
                    'condition' => $bAttachment && $aFiles[$iAttachmentId]['type'] == BX_MARKET_FILE_TYPE_VERSION,
                    'content' => [
                        'file_version' => $bAttachment ? $aFiles[$iAttachmentId]['version'] : ''
                    ]
                ],
                'bx_if:show_update' => [
                    'condition' => $bAttachment && $aFiles[$iAttachmentId]['type'] == BX_MARKET_FILE_TYPE_UPDATE,
                    'content' => [
                        'file_version_from_to' => $bAttachment ? _t('_bx_market_form_entry_input_files_version_from_x_to_y', $aFiles[$iAttachmentId]['version'], $aFiles[$iAttachmentId]['version_to']) : ''
                    ]
                ]
            ]);
            $aAttachments[$iAttachmentId]['bx_if:show_separator'] = [
                'condition' => $bSeparatorNeeded, 
                'content' => [
                    'type' => _t('_bx_market_form_entry_input_files_version_caption_' . $sCaptionType)
                ]
            ];

            $aFiles[$iAttachmentId] = $aAttachments[$iAttachmentId];
            $aResults[$iAttachmentId] = $aAttachments[$iAttachmentId];
        }

        return array_intersect_key($aFiles, $aResults);
    }

    protected function getAttachmentsImagesTranscoders ($sStorage = '')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        $oTranscoderPreview = isset($CNF['OBJECT_IMAGES_TRANSCODER_PICTURE']) && $CNF['OBJECT_IMAGES_TRANSCODER_PICTURE'] ? BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PICTURE']) : null;

        return array($oTranscoder, $oTranscoderPreview);
    }

    protected function _getHeaderImage($aData)
    {
        return $this->getModule()->getEntryImageData($aData, 'FIELD_COVER');
    }
}

/** @} */
