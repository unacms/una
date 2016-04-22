<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Market Market
 * @ingroup     TridentModules
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

    public function entryInfo($aData)
    {
    	$aCategory = array();
    	$oCategory = BxTemplCategory::getObjectInstance('bx_market_cats');
    	$aCategories = BxDolForm::getDataItems('bx_market_cats');
    	if($oCategory && $aCategories && isset($aCategories[$aData['cat']]))
    		$aCategory = array(
    			'category_url' => $oCategory->getCategoryUrl($aData['cat']),
    			'category_title' => $aCategories[$aData['cat']]
    		);

    	return $this->parseHtmlByName('entry-info.html', array(
    		'bx_if:show_category' => array(
    			'condition' => !empty($aCategory),
    			'content' => $aCategory
    		),
    		'category' => '',
    		'released' => bx_time_js($aData['added']),
	    	'updated' => bx_time_js($aData['changed']),
	    	'installs' => '?',
    	));
    }

    public function entryRating($aData)
    {
        $CNF = &BxDolModule::getInstance($this->MODULE)->_oConfig->CNF;

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
    
    public function entryText ($aData, $sTemplateName = 'entry-text.html')
    {
    	$sScreenshots = $this->getScreenshots($aData);

    	return $this->parseHtmlByContent(parent::entryText($aData), array(
    		'bx_if:show_screenshots' => array(
    			'condition' => !empty($sScreenshots),
    			'content' => array(
    				'screenshots' => $sScreenshots
    			)
    		)
    	));
    }

    public function setCover($aData)
    {
		$CNF = &BxDolModule::getInstance($this->MODULE)->_oConfig->CNF;

        $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_COVER']);
		if(empty($oImagesTranscoder))
			return;

		$sCoverUrl = $oImagesTranscoder->getFileUrl($aData[$CNF['FIELD_COVER']]);
		if(empty($sCoverUrl))
			return;

		$oCover = BxDolCover::getInstance($this);
		$oCover->setCoverImageUrl($sCoverUrl);

		$mixedOptions = BxDolMenu::getObjectInstance('sys_site_submenu')->getParamsForCover();
        if(empty($mixedOptions) || !is_array($mixedOptions))
        	return;

		$oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
		if(empty($oImagesTranscoder))
			return;

		$sThumbnailUrl = $oImagesTranscoder->getFileUrl($aData[$CNF['FIELD_THUMB']]);
		if(empty($sThumbnailUrl))
			return;

		$oCover->set(array_merge($mixedOptions, array(
			'bx_if:image' => array (
                'condition' => true,
                'content' => array('icon_url' => $sThumbnailUrl),
            ),
            'bx_if:icon' => array (
                'condition' => false,
                'content' => array(),
            ),
            'bx_if:bg' => array (
                'condition' => true,
                'content' => array('image_url' => $sCoverUrl),
            ),
		)));
    }

    public function getScreenshots($aData)
    {
    	$CNF = &BxDolModule::getInstance($this->MODULE)->_oConfig->CNF;

    	$aPhotos = $this->_oDb->getPhoto(array('type' => 'content_id', 'content_id' => $aData[$CNF['FIELD_ID']], 'except' => array($aData[$CNF['FIELD_THUMB']], $aData[$CNF['FIELD_COVER']])));
    	if(empty($aPhotos) || !is_array($aPhotos))
    		return '';

		$oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
    	$oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_SCREENSHOT']);

    	$aTmplVarsPhotos = array();
    	foreach($aPhotos as $aPhoto) 
    		$aTmplVarsPhotos[] = array(
    			'item_url_sm' => $oImagesTranscoder ? $oImagesTranscoder->getFileUrl($aPhoto['file_id']) : '',
    			'item_url_bg' => $oStorage ? $oStorage->getFileUrlById($aPhoto['file_id']) : ''
    		);

    	return $this->parseHtmlByName('entry-screenshots.html', array(
    		'bx_repeat:items' => $aTmplVarsPhotos
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

    protected function getUnit ($aData, $aParams = array())
    {
    	$CNF = &BxDolModule::getInstance($this->MODULE)->_oConfig->CNF;

    	$aUnit = parent::getUnit($aData, $aParams);
        $oPayment = BxDolPayments::getInstance();

    	$bTmplVarsSingle = (float)$aData[$CNF['FIELD_PRICE_SINGLE']] != 0;
    	$aTmplVarsSingle = array();
    	if($bTmplVarsSingle) {
    		list($sJsCode, $sSingleOnclick) = $oPayment->getAddToCartJs($aData[$CNF['FIELD_AUTHOR']], $this->_oConfig->getName(), $aData[$CNF['FIELD_ID']], 1);

    		$aTmplVarsSingle = array(
    			'entry_price_single_onclick' => $sSingleOnclick,
				'entry_price_single' => _t('_bx_market_txt_price_single', $this->_aCurrency['sign'], $aData[$CNF['FIELD_PRICE_SINGLE']])
			);
    	}

    	$bTmplVarsRecurring = (float)$aData[$CNF['FIELD_PRICE_RECURRING']] != 0;
    	$aTmplVarsRecurring = array();
    	if($bTmplVarsRecurring) {
        	list($sJsCode, $sRecurringOnclick) = $oPayment->getSubscribeJs($aData[$CNF['FIELD_AUTHOR']], '', $this->_oConfig->getName(), $aData[$CNF['FIELD_ID']], 1);

        	$aTmplVarsRecurring = array(
        		'entry_price_recurring_onclick' => $sRecurringOnclick,
				'entry_price_recurring' => _t('_bx_market_txt_price_recurring', $this->_aCurrency['sign'], $aData[$CNF['FIELD_PRICE_RECURRING']], _t('_bx_market_txt_per_' . $aData[$CNF['FIELD_DURATION_RECURRING']] . '_short'))
			);
    	}

    	$oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $aData['id']);
        if($oVotes)
			$sVotes = $oVotes->getElementBlock(array('show_counter' => false));

    	$aUnit = array_merge($aUnit, array(
    		'entry_votes' => $sVotes,
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
    			'content' => array()
    		),
    	));

    	return $aUnit;
    }

    protected function getAttachments($sStorage, $aData)
    {
    	$aFiles = $this->_oDb->getFile(array('type' => 'content_id_key_file_id', 'content_id' => $aData['id']));

    	$aAttachments = parent::getAttachments($sStorage, $aData);
    	foreach($aAttachments as $iIndex => $aAttachment) {
    		$iAttachmentId = (int)$aAttachment['id'];

    		$aAttachments[$iIndex]['bx_if:main'] = array(
    			'condition' => (int)$aData['package'] == $iAttachmentId,
    			'content' => array()
    		);

    		$aAttachments[$iIndex]['bx_if:not_image']['content']['file_version'] = !empty($aFiles[$iAttachmentId]) ? $aFiles[$iAttachmentId]['version'] : '';
    	}

    	return $aAttachments;
    }
}

/** @} */
