<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxVideosTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    public function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_videos';
        parent::__construct($oConfig, $oDb);
    }

    public function entryVideo ($aContentInfo, $mixedContext = false)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_VIDEOS']);

        $aTranscodersVideo = false;
        if($CNF['OBJECT_VIDEOS_TRANSCODERS'])
            $aTranscodersVideo = array (
                'poster' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']),
                'mp4' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']),
                'webm' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['webm']),
            );

        $iFile = (int)$aContentInfo[$CNF['FIELD_VIDEO']];
        $aFile = $oStorage->getFile($iFile);
        if(empty($aFile) || !is_array($aFile) || strncmp('video/', $aFile['mime_type'], 6) !== 0)
            return '';

        $sTitle = bx_process_output($aContentInfo['title']);
        $sTitleAttr = bx_html_attribute($aContentInfo['title']);

        $this->_checkDuration($aContentInfo);

        return $this->parseHtmlByName('entry-video.html', array(
            'img_title' => $sTitle,
            'img_title_attr' => $sTitleAttr,
            'img_src' => $aTranscodersVideo['poster']->getFileUrl($iFile),
            'video' => BxTemplFunctions::getInstance()->videoPlayer(
                $aTranscodersVideo['poster']->getFileUrl($iFile), 
                $aTranscodersVideo['mp4']->getFileUrl($iFile), 
                $aTranscodersVideo['webm']->getFileUrl($iFile),
                false, 'max-height:' . $CNF['OBJECT_VIDEO_TRANSCODER_HEIGHT']
            )
        ));
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

    protected function getUnit($aData, $aParams = array())
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $this->_checkDuration($aData);

        $aUnit = parent::getUnit($aData, $aParams);
        $aUnit['entry_views'] = _t('_bx_videos_txt_n_views', $aData[$CNF['FIELD_VIEWS']]);
        $aUnit['bx_if:thumb']['content']['duration'] = _t_format_duration($aData[$CNF['FIELD_DURATION']]);

        return $aUnit;
    }

    /**
     * Use Gallery image for both because currently there is no Unit types with small thumbnails.
     */
    protected function getUnitThumbAndGallery ($aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $sImage = $this->_getUnitThumbAndGallery($CNF['FIELD_THUMB'], $CNF['OBJECT_IMAGES_TRANSCODER_GALLERY'], $aData);
        if(!empty($sImage))
            return array($sImage, $sImage);

        $sImage = $this->_getUnitThumbAndGallery($CNF['FIELD_VIDEO'], $CNF['OBJECT_VIDEOS_TRANSCODERS']['poster_gallery'], $aData);
        if(!empty($sImage))
            return array($sImage, $sImage);

        return array('', '');
    }
    
    protected function _getUnitThumbAndGallery ($sField, $sTranscoder, &$aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $sImage = '';
        if(empty($sField) || empty($aData[$sField])) 
            return $sImage;

        $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($sTranscoder);
        if($oImagesTranscoder)
            $sImage = $oImagesTranscoder->getFileUrl($aData[$sField]);

        return $sImage;
    }

    protected function _checkDuration(&$aContentInfo) 
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $iVideo = (int)$aContentInfo[$CNF['FIELD_VIDEO']];
        if(empty($iVideo) || !empty($aContentInfo[$CNF['FIELD_DURATION']]))
            return;

        $sFile = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4'])->getFileUrl($iVideo);
        if(empty($sFile))
            return;

        $iDuration = BxDolTranscoderVideo::getDuration($sFile);
        if(empty($iDuration))
            return;

        $this->_oDb->updateEntries(array($CNF['FIELD_DURATION'] => $iDuration), array($CNF['FIELD_ID'] => $aContentInfo[$CNF['FIELD_ID']]));
    }

}

/** @} */
