<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxAlbumsTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_albums';
        parent::__construct($oConfig, $oDb);
    }

    function unit ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html', $aParams = array())
    {
        if ('unit_media.html' == $sTemplateName || 'unit_media_live_search.html' == $sTemplateName)
            return $this->unitMedia($aData, $isCheckPrivateContent, $sTemplateName, $aParams);

        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        if ($s = $this->checkPrivacy ($aData, $isCheckPrivateContent, $oModule))
            return $s;

        // get entry url        
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aData[$CNF['FIELD_ID']]);

        $oProfile = BxDolProfile::getInstance($aData[$CNF['FIELD_AUTHOR']]);
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_TRANSCODER_BROWSE']);

        $aBrowseUnits = array ();
        $aMediaList = $oModule->_oDb->getMediaListByContentId($aData[$CNF['FIELD_ID']]);
        foreach ($aMediaList as $k => $a) {
            $aBrowseUnits[] = array (
                'img_url' => $oTranscoder->getFileUrl($a['file_id']),
                'url' => $this->getViewMediaUrl($CNF, $a['id']),
                'title_attr' => bx_html_attribute($a['title']),
            );
        }

        // generate html
        $aVars = array (
            'id' => $aData[$CNF['FIELD_ID']],
            'content_url' => $sUrl,
            'title' => bx_process_output($aData[$CNF['FIELD_TITLE']]),
            'author' => $oProfile->getDisplayName(),
            'author_url' => $oProfile->getUrl(),
            'entry_posting_date' => bx_time_js($aData[$CNF['FIELD_ADDED']], BX_FORMAT_DATE),
            'module_name' => _t($CNF['T']['txt_sample_single']),
            'ts' => $aData[$CNF['FIELD_ADDED']],
            'bx_repeat:browse' => $aBrowseUnits,

            'bx_if:thumb' => array (
                'condition' => $aBrowseUnits,
                'content' => array (
                    'title' => bx_process_output($aData[$CNF['FIELD_TITLE']]),
                    'summary_attr' => '',
                    'content_url' => $sUrl,
                    'thumb_url' => $aBrowseUnits ? $aBrowseUnits[0]['img_url'] : '',
                    'gallery_url' => '',
                    'strecher' => '',
                ),
            ),
            'bx_if:no_thumb' => array (
                'condition' => !$aBrowseUnits,
                'content' => array (
                    'content_url' => $sUrl,
                    'summary_plain' => '',
                    'strecher' => '',
                ),
            ),

        );

        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function unitMedia ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html', $aParams = array())
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        if (!($aMediaInfo = $oModule->_oDb->getMediaInfoById($aData['id'])))
            return '';

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        if (!($aFile = $oStorage->getFile($aMediaInfo['file_id'])))
            return '';

        $aVars = $this->mediaVars($aMediaInfo, $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW'], $CNF['OBJECT_VIDEOS_TRANSCODERS']['poster_preview'], $aParams);
    
        $aVarsTmp = $aVars['bx_if:image']['condition'] ? $aVars['bx_if:image']['content'] : $aVars['bx_if:video']['content'];

        $aVars = array_merge($aVars, array (
            'title' => $this->getMediaTitle($aData),
            'module_name' => _t($CNF['T']['txt_media_single']),
            'content_url' => $aVarsTmp['url'],
            'ts' => $aFile['added'],
            'actions' => $oModule->serviceMediaSocialSharing($aData['id'], true, false),
            'bx_if:thumb' => array (
                'condition' => $aData['file_id'],
                'content' => array (
                    'title' => bx_process_output($aData['title']),
                    'summary_attr' => '',
                    'content_url' => $aVarsTmp['url'],
                    'thumb_url' => $aVarsTmp['url_img'],
                    'gallery_url' => '',
                    'strecher' => '',
                ),
            ),
            'bx_if:no_thumb' => array (
                'condition' => false,
                'content' => array (),
            ),

        ));

        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function entryMediaView ($iMediaId, $mixedContext = false)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        if (!($aMediaInfo = $oModule->_oDb->getMediaInfoById($iMediaId)))
            return '';

        if (!($aAlbumInfo = $oModule->_oDb->getContentInfoById($aMediaInfo['content_id'])))
            return '';        

        $sUrlAlbum = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aAlbumInfo[$CNF['FIELD_ID']]);
         
        $sText = $this->getMediaTitle($aMediaInfo);

        $iProfileId = $aAlbumInfo[$CNF['FIELD_AUTHOR']];
        $oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        $aVars = array(
            'title' => $sText,
            'album' => _t('_bx_albums_txt_media_album_link', $sUrlAlbum,  bx_process_output($aAlbumInfo[$CNF['FIELD_TITLE']]), $oProfile->getUrl(), $oProfile->getDisplayName()),
        );

        $aNextPrev = array (
            'next' => $this->getNextPrevMedia($aMediaInfo, true, $mixedContext),
            'prev' => $this->getNextPrevMedia($aMediaInfo, false, $mixedContext),
        );

        foreach ($aNextPrev as $k => $a) {
            $aVars['bx_if:' . $k] = array (
                'condition' => $a,
                'content' => $a,
            );
            $aVars['bx_if:' . $k . '-na'] = array (
                'condition' => !$a,
                'content' => array(),
            );
        }

        $aVars = array_merge($aVars, $this->mediaVars($aMediaInfo, $CNF['OBJECT_IMAGES_TRANSCODER_BIG'], $CNF['OBJECT_VIDEOS_TRANSCODERS']['poster'], array('context' => $mixedContext)));

        return $this->parseHtmlByName('media-view.html', $aVars);
    }

    protected function mediaVars ($aMediaInfo, $sImageTranscoder = false, $sVideoPosterTranscoder = false, $aParams = array())
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        $oTranscoder = BxDolTranscoderImage::getObjectInstance($sImageTranscoder);
        $aTranscodersVideo = false;

        if ($CNF['OBJECT_VIDEOS_TRANSCODERS'])
            $aTranscodersVideo = array (
                'poster' => BxDolTranscoderImage::getObjectInstance($sVideoPosterTranscoder),
                'mp4' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']),
                'webm' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['webm']),
            );
        
        $aFileInfo = $oStorage->getFile ($aMediaInfo['file_id']);
        if (!$aFileInfo)
            return '';

        $isImage = 0 == strncmp('image/', $aFileInfo['mime_type'], 6); // preview for images, transcoder object for preview must be defined
        $isVideo = $aTranscodersVideo && (0 == strncmp('video/', $aFileInfo['mime_type'], 6)); // preview for videos, transcoder object for video must be defined

        $sMediaTitle = bx_process_output($aMediaInfo['title']);
        $sMediaTitleAttr = bx_html_attribute($aMediaInfo['title']);
        $mixedContext = isset($aParams['context']) ? $aParams['context'] : '';
        $sUrl = $this->getViewMediaUrl($CNF, $aMediaInfo['id'], $mixedContext);
        $aSize = $aMediaInfo['data'] ? explode('x', $aMediaInfo['data']) : array(0, 0);

        $aVars = array(
            'bx_if:image' => array (
                'condition' => $isImage,
                'content' => array (
                    'title_attr' => $sMediaTitleAttr,
                    'title' => $sMediaTitle,
                    'url' => $sUrl,
                    'url_img' => $oTranscoder ? $oTranscoder->getFileUrl($aFileInfo['id']) : $oStorage->getFileUrlById($aFileInfo['id']),
                    'media_id' => $aMediaInfo['id'],
                    'w' => $aSize[0],
                    'h' => $aSize[1],
                    'context' => $mixedContext,
                ),
            ),
            'bx_if:video' => array (
                'condition' => $isVideo,
                'content' => array (
                    'title_attr' => $sMediaTitleAttr,
                    'title' => $sMediaTitle,
                    'url' => $sUrl,
                    'url_img' => $isVideo ? $aTranscodersVideo['poster']->getFileUrl($aMediaInfo['file_id']) : '',
                    'video' => $isVideo && $aTranscodersVideo ? BxTemplFunctions::getInstance()->videoPlayer(
                        $aTranscodersVideo['poster']->getFileUrl($aMediaInfo['file_id']), 
                        $aTranscodersVideo['mp4']->getFileUrl($aMediaInfo['file_id']), 
                        $aTranscodersVideo['webm']->getFileUrl($aMediaInfo['file_id']),
                        false, 'max-height:' . $CNF['OBJECT_VIDEO_TRANSCODER_HEIGHT']
                    ) : '',
                ),
            ),
        );

        return $aVars;
    }

    public function getViewMediaUrl($CNF, $iMediaId, $sContext = '')
    {
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $iMediaId . (!empty($sContext) ? '&context=' . $sContext : ''));
    }

    public function getNextPrevMedia($aMediaInfo, $isNext, $sContext, $aParamsSearchResult = array())
    {
        if (!$sContext) {
            $sContext = 'album';
            $aParamsSearchResult = array('album_id' => $aMediaInfo['content_id']);
        }

        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        bx_import('SearchResultMedia', $oModule->_aModule);
        $sClass = $oModule->_aModule['class_prefix'] . 'SearchResultMedia';
        $o = new $sClass($sContext, $aParamsSearchResult);
        if ($o->isError)
            return false;

        $o->setUnitParams(array('context' => $sContext));

        $a = $o->getNextPrevItem($aMediaInfo, $isNext);
    
        if ($a) {

            $aVars = $this->mediaVars($a, $CNF['OBJECT_IMAGES_TRANSCODER_BIG'], $CNF['OBJECT_VIDEOS_TRANSCODERS']['poster'], array('context' => $sContext));
            $aMedia = $aVars['bx_if:image']['condition'] ? $aVars['bx_if:image']['content'] : $aVars['bx_if:video']['content'];

            $a['url'] = $aMedia['url'];
            $a['url_img'] = $aMedia['url_img'];
            $a['title_attr'] = $aMedia['title_attr'];
            $a['title_js_string'] = bx_js_string($aMedia['title']);
            $a['w'] = $aVars['bx_if:image']['condition'] ? $aVars['bx_if:image']['content']['w'] : 0;
            $a['h'] = $aVars['bx_if:image']['condition'] ? $aVars['bx_if:image']['content']['h'] : 0;
            $a['html'] = $aVars['bx_if:video']['condition'] ? '<div class="pswp__video">' . $aVars['bx_if:video']['content']['video'] . '</div>': 0;
        }

        return $a;
    }

    function entryAttachments ($aData)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        return $oModule->_serviceBrowse ('album', array('unit_view' => 'gallery', 'album_id' => $aData[$CNF['FIELD_ID']]), BX_DB_PADDING_DEF, true, true, 'SearchResultMedia');
    }

    function mediaAuthor ($aMediaInfo, $iProfileId = false, $sFuncAuthorDesc = '', $sTemplateName = '') 
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        if (!($aAlbumInfo = $oModule->_oDb->getContentInfoById($aMediaInfo['content_id'])))
            return '';

        return $this->entryAuthor ($aMediaInfo, $aAlbumInfo[$CNF['FIELD_AUTHOR']], 'getMediaDesc');
    }

    function getMediaDesc ($aData)
    {
        return _t('_sys_txt_n_by', bx_time_js($aData['added'], BX_FORMAT_DATE));
    }

    function getMediaTitle ($aMediaInfo)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;
         
        $sText = bx_process_output($aMediaInfo['title']);
        if (!empty($CNF['OBJECT_METATAGS_MEDIA'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA']);
    
            if ($oMetatags->keywordsIsEnabled())
                $sText = $oMetatags->keywordsParse($aMediaInfo['id'], $sText);
        }

        return $sText;
    }

    function mediaExif ($aMediaInfo, $iProfileId = false, $sFuncAuthorDesc = '', $sTemplateName = 'media-exif.html') 
    {
        if (!$aMediaInfo['exif'])
            return '';

        $a = unserialize($aMediaInfo['exif']);

        $s = '';
        if (isset($a['Make'])) {
            $oModule = BxDolModule::getInstance($this->MODULE);
            $CNF = &$oModule->_oConfig->CNF;

            $sCamera = BxDolMetatags::keywordsCameraModel($a);
            if (!empty($CNF['OBJECT_METATAGS_MEDIA_CAMERA'])) {
                $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA_CAMERA']);
                if ($oMetatags->keywordsIsEnabled()) {
                    $sCamera = $oMetatags->keywordsParseOne($aMediaInfo['id'], $sCamera);
                }
            }       
            $s .= $this->parseHtmlByName('media-exif-value.html', array(
                'key' => _t('_bx_albums_txt_media_album_camera'), 
                'val' => $sCamera,
            ));
        }

        if (isset($a['FocalLength']))
            $s .= $this->parseHtmlByName('media-exif-value.html', array(
                'key' => _t('_bx_albums_txt_media_album_focal_length'),
                'val' => _t('_bx_albums_txt_media_album_focal_length_value', $a['FocalLength']),
            ));

        if (isset($a['COMPUTED']['ApertureFNumber']))
            $s .= $this->parseHtmlByName('media-exif-value.html', array(
                'key' => _t('_bx_albums_txt_media_album_aperture'),
                'val' => $a['COMPUTED']['ApertureFNumber'],
            ));

        if (isset($a['ExposureTime']))
            $s .= $this->parseHtmlByName('media-exif-value.html', array(
                'key' => _t('_bx_albums_txt_media_album_shutter_speed'),
                'val' => _t('_bx_albums_txt_media_album_shutter_speed_value', $a['ExposureTime']),
            ));

        if (isset($a['ISOSpeedRatings']))
            $s .= $this->parseHtmlByName('media-exif-value.html', array(
                'key' => _t('_bx_albums_txt_media_album_iso'),
                'val' => $a['ISOSpeedRatings'],
            ));

        if (empty($s))
            return '';

        return $this->parseHtmlByName($sTemplateName, array('content' => $s));
    }

}

/** @} */
