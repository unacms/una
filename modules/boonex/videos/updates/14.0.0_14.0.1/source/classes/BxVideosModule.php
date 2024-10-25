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

/**
 * Videos module
 */
class BxVideosModule extends BxBaseModFilesModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function actionParseEmbedLink() {
        $sCode = bx_get('code');
        $aRes = $this->parseEmbedLink($sCode);
        if ($aRes) echo $aRes['embed'];
    }
    
    public function actionEmbed($iContentId, $sUnitTemplate = '', $sAddCode = '')
    {
        return parent::actionEmbed($iContentId, 'unit_gallery_embed.html');
    }

    public function parseEmbedLink($sLink) {
        $aEmbedProviders = $this->_oDb->getEmbedProviders();
        if ($aEmbedProviders) foreach ($aEmbedProviders as $aEmbedProvider) {
            if (!$aEmbedProvider['class_name']) continue;

            if ($aEmbedProvider['class_file'])
                require_once(BX_DIRECTORY_PATH_ROOT . $aEmbedProvider['class_file']);

            $oLinkParser = new $aEmbedProvider['class_name']($this);
            if ($mResult = $oLinkParser->parseLink($sLink))
                return $mResult;
        }

        return false;
    }

    /**
     * @page service Service Calls
     * @section bx_videos Videos
     * @subsection bx_videos-page_blocks Page Blocks
     * @subsubsection bx_videos-entity_video_block entity_video_block
     * 
     * @code bx_srv('bx_videos', 'entity_video_block', [...]); @endcode
     * 
     * Get page block with video player.
     *
     * @param $iContentId (optional) video ID. If empty value is provided, an attempt to get it from GET/POST arrays will be performed.
     * @return HTML string with block content to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxVideosModule::serviceEntityVideoBlock
     */
    /** 
     * @ref bx_videos-entity_video_block "entity_video_block"
     */
    public function serviceEntityVideoBlock ($iContentId = 0)
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;
        $s = $this->_oTemplate->entryVideo($aContentInfo);

        return $this->_bIsApi ? [bx_api_get_block('entity_text', $s)] : $s;
    }

	/**
     * @page service Service Calls
     * @section bx_videos Videos
     * @subsection bx_videos-page_blocks Page Blocks
     * @subsubsection bx_videos-entity_rating entity_rating
     * 
     * @code bx_srv('bx_videos', 'entity_rating', [...]); @endcode
     * 
     * Get page block with Stars based video's rating.
     *
     * @param $iContentId (optional) video ID. If empty value is provided, an attempt to get it from GET/POST arrays will be performed.
     * @return HTML string with block content to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxVideosModule::serviceEntityRating
     */
    /** 
     * @ref bx_videos-entity_rating "entity_rating"
     */
    public function serviceEntityRating($iContentId = 0)
    {
    	return $this->_serviceTemplateFunc ('entryRating', $iContentId);
    }

    public function serviceGetThumb ($iContentId, $sTranscoder = '') 
    {
        $CNF = &$this->_oConfig->CNF;
        
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        if($this->_bIsApi) {
            $aImage = bx_api_get_image($CNF['OBJECT_STORAGE'], (int)$aContentInfo[$CNF['FIELD_THUMB']]);
            if(!$aImage)
                $aImage = bx_api_get_image([$CNF['OBJECT_STORAGE_VIDEOS'], $CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']], (int)$aContentInfo[$CNF['FIELD_VIDEO']]);
            
            return $aImage;
        }

        list($iImageId, $sImageThumb, $sImageGallery, $sImageCover) = $this->_oTemplate->getUnitImages($aContentInfo);
        return $sImageCover ? $sImageCover : $sImageGallery;
    }

    public function serviceGetVideo ($iContentId) 
    {
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        return $this->getVideoData($aContentInfo);
    }

    public function getVideoData($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['OBJECT_STORAGE_VIDEOS']) || empty($CNF['OBJECT_VIDEOS_TRANSCODERS']))
            return [];

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_VIDEOS']);

        $iFile = (int)$aContentInfo[$CNF['FIELD_VIDEO']];
        $aFile = $oStorage->getFile($iFile);
        if(empty($aFile) || !is_array($aFile) || strncmp('video/', $aFile['mime_type'], 6) !== 0)
            return [];

        $oTcvPoster = BxDolTranscoder::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']);
        $oTciPoster = BxDolTranscoder::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_POSTER']);
        $oTcvMp4 = BxDolTranscoder::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']);
        $oTcvMp4Hd = BxDolTranscoder::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4_hd']);
        if(!($oTcvPoster || $oTciPoster) || !$oTcvMp4 || !$oTcvMp4Hd)
            return [];

        $sPosterSrc = !empty($CNF['FIELD_POSTER']) ? $CNF['FIELD_POSTER'] : $CNF['FIELD_THUMB'];
        if(!empty($sPosterSrc) && !empty($aContentInfo[$sPosterSrc]) && $oTciPoster)
            $sPoster = $oTciPoster->getFileUrl($aContentInfo[$sPosterSrc]);
        else 
            $sPoster = $oTcvPoster->getFileUrl($iFile);

        $sVideoUrl = $oStorage->getFileUrlById($iFile);
        $aVideoFile = $oStorage->getFile($iFile);

        $sVideoUrlHd = '';
        if (!empty($aVideoFile['dimensions']) && $oTcvMp4Hd->isProcessHD($aVideoFile['dimensions']))
            $sVideoUrlHd = $oTcvMp4Hd->getFileUrl($iFile);

        return [
            'id' => $iFile,
            'src_poster' => $sPoster, 
            'src_mp4' => $oTcvMp4->getFileUrl($iFile), 
            'src_mp4_hd' => $sVideoUrlHd
        ];
    }
            
    protected function _getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams = array())
    {
        $aResult = parent::_getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams);
        if($this->_bIsApi)
            return $aResult;

        if ($aContentInfo['video_source'] == 'embed' && !empty($aContentInfo['video_embed_data'])) {
            if (!is_array($aContentInfo['video_embed_data'])) $aContentInfo['video_embed_data'] = unserialize($aContentInfo['video_embed_data']);
            if (isset($aContentInfo['video_embed_data']['embed']) && !empty($aContentInfo['video_embed_data']['embed'])) {
                $aResult['images'] = array();
                $aResult['top_raw'] = $this->_oTemplate->getResponsiveEmbed($aContentInfo['video_embed_data']['embed'], isset($aBrowseParams['dynamic_mode']) ? $aBrowseParams['dynamic_mode'] : false);
                return $aResult;
            }
        }

        if(!empty($aResult['videos']) && is_array($aResult['videos']))
            $aResult['images'] = array();

        return $aResult;
    }
    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        if($this->_bIsApi) {
            $aImage = bx_api_get_image($CNF['OBJECT_STORAGE'], (int)$aContentInfo[$CNF['FIELD_THUMB']]);
            if(!$aImage)
                $aImage = bx_api_get_image([$CNF['OBJECT_STORAGE_VIDEOS'], $CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']], (int)$aContentInfo[$CNF['FIELD_VIDEO']]);

            return $aImage ? [$aImage] : [];
        }

        list($iImageId, $sImageThumb, $sImageGallery, $sImageCover) = $this->_oTemplate->getUnitImages($aContentInfo);
        if(empty($sImageGallery) && !empty($sImageThumb))
            $sImageGallery = $sImageThumb;

        if(empty($sImageGallery))
            return array();

        return array(
            array('id' => $iImageId, 'url' => $sUrl, 'src' => $sImageGallery, 'src_orig' => $sImageCover),
        );
    }

    protected function _getVideosForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = [])
    {
        $aResult = $this->getVideoData($aContentInfo);
        if(!$aResult)
            return $aResult;

        return $this->_bIsApi ? [$aResult] : [
            $aResult['id'] => $aResult
        ];
    }
}

/** @} */
