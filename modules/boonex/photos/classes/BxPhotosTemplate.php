<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxPhotosTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    public function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_photos';
        parent::__construct($oConfig, $oDb);
    }

    public function entryPhoto ($aContentInfo, $mixedContext = false)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        if(empty($aContentInfo[$CNF['FIELD_THUMB']]))
            return '';

        $iImage = (int)$aContentInfo[$CNF['FIELD_THUMB']];

        $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_COVER']);
        if($oImagesTranscoder)
            $sImage = $oImagesTranscoder->getFileUrl($iImage);
        
        if(empty($sImage)) {
            $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_PHOTOS']);
            if($oStorage)
                $sImage = $oStorage->getFileUrl($iImage);
        }

        if(empty($sImage))
            return '';

        $sTitle = bx_process_output($aContentInfo['title']);
        $sTitleAttr = bx_html_attribute($aContentInfo['title']);

        return $this->parseHtmlByName('entry-photo.html', array(
            'title' => $sTitle,
            'title_attr' => $sTitleAttr,
            'src' => $sImage
        ));
    }

    public function entryRating($aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

    	$sVotes = '';
        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_STARS'], $aData['id']);
        if($oVotes) {
			$sVotes = $oVotes->getElementBlock(array('show_counter' => true, 'show_legend' => true));
			if(!empty($sVotes))
				$sVotes = $this->parseHtmlByName('entry-rating.html', array(
		    		'content' => $sVotes,
		    	));
        }

    	return $sVotes; 
    }
}

/** @} */
