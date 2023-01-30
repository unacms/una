<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * This class transcodes videos and generates video thumbnails.
 *
 * To generate video which plays in all moders browsers along with video poster, 
 * you need to create 3 different video transcoding objects which will generate .mp4, .webm videos and video poster.
 * Video is converting upon first access, so it is probably better to force video conversion by calling @see BxDolTranscoderVideo::getFileUrl just after video uploading.
 * Video for conversion is queued and when cron is run video conversion is performed.
 * While video is pending for conversion or in the process then 
 * @see BxDolTranscoderVideo::getFileUrl methods returns empty string for video and predefined image for video poster.
 *
 * Transcoder object and other params are the same as in @see BxDolTranscoderImage, but it is highly recommended to disable 'atime_pruning' and 'atime_tracking', 
 * or set it to fairly big value, since video transcoding is not performed on the fly and takes some time. 
 *
 * Video conversion can be performed on separate server or multiple servers, to do it:
 * - install the script on separate server(s), but connect to the same DB which your main site is using
 * - enable 'sys_transcoder_queue_files' option (when it is enabled it takes a little longer to convert videos)
 * - add the following code to the begining of inc/header.inc.php file on the main site, where your actual site in installed:
 *   @code
 *     define('BX_TRANSCODER_PROCESS_COMPLETED', '');
 *   @endcode
 * - if you don't want your main site to convert videos, so all conversion will be performed on the separate server, add the following code to the begining of inc/header.inc.php file on the main site:
 *   @code
 *     define('BX_TRANSCODER_NO_TRANSCODING', '');
 *   @endcode
 * - all servers must have different host name
 * - only main server must be used as site, additional sites are just for video conversion, don't perform any action on these sites
 *
 *
 * Available filters:
 * - Mp4 - this filter convert video into .mp4 format along with resizing, the parameters are the following:
 *     - h - height of resulted video (360px by default), for video it is highly recommended to specify only height parameter (without width parameter)
 *     - video_bitrate - video bitrate (512k by default)
 *     - audio_bitrate - video bitrate (128k by default)
 *     - ffmpeg_options - additional command line options for ffmepeg, as key => value array (empty by default)
 * - Webm - this filter convert video into .webm format along with resizing, the parameters are the same as for Mp4 filter
 * - Poster - this filter generates video thumbnail, it tries to get poster at 0, 3 and 5 seconds from the beginning, it gets first not fully black/white thumb
 *
 *
 * Example of usage:
 * @code
 * // transcoder objects which generate .mp4, .webm videos and image poster
 * $oTranscoderMp4 = BxDolTranscoder::getObjectInstance('bx_video_mp4'); 
 * $oTranscoderWebm = BxDolTranscoder::getObjectInstance('bx_video_webm');
 * $oTranscoderPoster = BxDolTranscoder::getObjectInstance('bx_video_poster');
 *
 * // make sure to call it only once (for example: during module installation), before the first usage, no need to call it every time
 * $oTranscoderMp4->registerHandlers(); 
 * $oTranscoderWebm->registerHandlers(); 
 * $oTranscoderPoster->registerHandlers(); 
 *
 * // get URLs of transcoded videos and video thumbnail, 33 is ID of original video file stored in specified storage object
 * $sUrlMp4 = $oTranscoderMp4->getFileUrl(33);
 * $sUrlWebM = $oTranscoderWebm->getFileUrl(33);
 * $sUrlPoster = $oTranscoderPoster->getFileUrl(33);
 *
 * echo 'My cat:' . BxTemplFunctions::getInstance()->videoPlayer($sUrlPoster, $sUrlMP4, $sUrlWebM); 
 * @endcode
 *
 * Also @see transcoder_videos sample for complete example.
 */
class BxDolTranscoderVideo extends BxDolTranscoder implements iBxDolFactoryObject
{
    protected function __construct($aObject, $oStorage)
    {
        parent::__construct($aObject, $oStorage);
        $this->_sQueueStorage = getParam('sys_transcoder_queue_storage') ? 'sys_transcoder_queue_files' : '';
        $this->_oDb = new BxDolTranscoderVideoQuery($aObject);
        $this->_sQueueTable = $this->_oDb->getQueueTable();
    }

    public static function getObjectAbstract()
    {
        if (isset($GLOBALS['bxDolClasses'][__CLASS__ . '!Abstract']))
            return $GLOBALS['bxDolClasses'][__CLASS__ . '!Abstract'];

        $aObject = array('object' => 'abstract');
        $o = new BxDolTranscoderVideo ($aObject, null);
        return ($GLOBALS['bxDolClasses'][__CLASS__ . '!Abstract'] = $o);
    }

    public static function getDuration($sFile)
    {
        $sCommand = escapeshellcmd(BX_SYSTEM_FFMPEG) . " -i " . escapeshellarg($sFile) . " 2>&1";
        $sResult = `$sCommand`;

        $aMatch = array();
        if(!preg_match("/[Dd]uration:\s([0-9]{2}):([0-9]{2}):([0-9]{2})\.([0-9]{2})/i", $sResult, $aMatch))
            return false;

        $aDuration = array_slice($aMatch, 1, -1);
        return 3600 * (int)$aDuration[0] + 60 * (int)$aDuration[1] + (int)$aDuration[2];
    }

    public function getDevicePixelRatioHandlerSuffix ()
    {
        return '';
    }

    /**
     * check if transcoder suppors given file mime type
     */ 
    public function isMimeTypeSupported($sMimeType)
    {
        if (0 === strncmp($sMimeType, 'video/', 6))
            return true;

        return false;
    }
    
    /**
     * If video isn't processed yet then empty string is returned for video, or predefined image is returned for video poster
     */
    public function getFileUrlNotReady($mixedHandler)
    {
        return false === $this->getFilterParams('Poster') ? '' : BxDolTemplate::getInstance()->getImageUrl('video-na.png');
    }

    /**
     * Convert video to .jpg format - video poster
     */
    protected function applyFilter_Poster ($sFile, $aParams)
    {
    	$oImage = BxDolImageResize::getInstance();

        $sFileOut = $this->getTmpFilename('.jpg');
        $bRet = false;
        $aSeconds = array (0, 3, 5, 8, 1);
        $sSize = $this->_getOptionSizeForVideo ($sFile, $aParams);
        foreach ($aSeconds as $iSecond) {
            $bRet = $this->_convertVideo($sFile, $sFileOut, '.jpg', $aParams, array (
                's' => $sSize,
                'ss' => $iSecond,
                'vframes' => 1,
                'f' => 'image2',
                'an' => ' ',
            ));
            if (!$bRet)
                continue;

            $aRgb = $oImage->getAverageColor($sFileOut);
		    $fRgb = ($aRgb['r'] + $aRgb['g'] + $aRgb['b']) / 3;
            if ($fRgb > 32 && $fRgb < 224)
    			break;
        }

        if (!$bRet) {
            @unlink($sFileOut);
            return false;
        }

        return rename($sFileOut, $sFile);
    }

    /**
     * Convert video to .mp4 format
     */
    protected function applyFilter_Mp4 ($sFile, $aParams)
    {
        return $this->_convertVideo($sFile, $sFile, '.mp4', $aParams, array (
            'strict' => 'experimental',
            'vcodec' => 'libx264',
            's' => $this->_getOptionSizeForVideo ($sFile, $aParams),
            'b:v' => isset($aParams['video_bitrate']) ? $aParams['video_bitrate'] . 'k' : '512k',
            'movflags' => '+faststart',
            'acodec' => 'aac',
            'ar' => '44100',
            'b:a' => isset($aParams['audio_bitrate']) ? $aParams['audio_bitrate'] . 'k' : '128k',
            'pix_fmt' => 'yuv420p',
        ));
    }

    /**
     * Convert video to .webm format
     */
    protected function applyFilter_Webm ($sFile, $aParams)
    {
        return $this->_convertVideo($sFile, $sFile, '.webm', $aParams, array (
            's' => $this->_getOptionSizeForVideo ($sFile, $aParams),
            'b:v' => isset($aParams['video_bitrate']) ? $aParams['video_bitrate'] . 'k' : '512k',
            'acodec' => 'libvorbis',
            'ar' => '44100',
            'b:a' => isset($aParams['audio_bitrate']) ? $aParams['audio_bitrate'] . 'k' : '128k',
            'pix_fmt' => 'yuv420p',
        ));
    }

    /**
     * Convert video using ffmpeg binary. Video conversion otput is written to the 'log' variable.
     * @param $sFile input file for conversion
     * @param $sFileOut output file for conversion, can be the same as input file
     * @param $sExt output file extension, in the format '.ext'
     * @param $aParams filter params
     * @param $aOptions ffmpeg options as array
     * @return false on error, or true on success
     */
    protected function _convertVideo ($sFile, $sFileOut, $sExt, $aParams, $aOptions)
    {
        if (!empty($aParams['ffmpeg_options']) && is_array($aParams['ffmpeg_options']))
            $aOptions = array_merge($aOptions, $aParams['ffmpeg_options']);

        $sOptions = '';
        foreach ($aOptions as $k => $v)
            if ($v)
                $sOptions .= "-{$k} {$v} ";

        $bRename = false;
        if ($sFileOut == $sFile) { // if output file is the same as input - generate new tmp file
            $sFileOut = $this->getTmpFilename($sExt);
            $bRename = true;
        }
        elseif (file_exists($sFileOut)) {
            @unlink($sFileOut);
        }

        $sCommand = escapeshellcmd(BX_SYSTEM_FFMPEG) . ' -y ' . $this->_getFfmpegThreadsParams() . ' -i ' . escapeshellarg($sFile) . ' ' . $this->_getFfmpegThreadsParams() . ' ' . $sOptions . ' ' . escapeshellarg($sFileOut) . ' 2>&1';
        $sOutput = `$sCommand`;
        $this->addToLog("\n---\n{$sCommand}\n{$sOutput}\n");

        if (!file_exists($sFileOut) || 0 == filesize($sFileOut)) {
            bx_log('sys_transcoder', "[{$this->_aObject['object']}] ERROR: _convertVideo failed for file ({$sFile}):\n{$sCommand}\n{$sOutput}\n");
            return false;
        }

        if ($bRename && !rename($sFileOut, $sFile)) { // rename tmp file, if tmp file was generated
            bx_log('sys_transcoder', "[{$this->_aObject['object']}] ERROR: _convertVideo failed, final rename from {$sFileOut} to {$sFile} failed");
            return false;
        }

        return true;
    }

    public function isProcessHD ($sOrigVideoDim)
    {
        $iHeight = $this->getMaxResizeDimention('Mp4', 'h');
        return ($aDim = explode('x', $sOrigVideoDim)) && isset($aDim[1]) && (int)$aDim[1] >= $iHeight ? true : false;
    }

    /**
     * Get video size. 
     * It generated image from the video and returns size of the image.
     * @param $sFile path to the video file
     * @return array where 'w' key is width and 'h' key is height
     */
    public function getSize ($sFile)
    {
        $sFileOut = $this->getTmpFilename('.jpg');

        $bRet = $this->_convertVideo($sFile, $sFileOut, '.jpg', array(), array (
            'ss' => 0,
            'vframes' => 1,
            'f' => 'image2',
            'an' => ' ',
        ));

        if (!$bRet) {
            @unlink($sFileOut);
            return false;
        }

    	$oImage = BxDolImageResize::getInstance();
        $aSize = $oImage->getImageSize($sFileOut);

        @unlink($sFileOut);

        return $aSize;
    }

    /**
     * Get output video size as 'WxH' string.
     * It check filter params for 'w' and 'h' options and original video ratio and return the resulted video size.
     * - If only height specified in params (desired behavior) or no width or height is specified (default height is 360), 
     * then width is automatically calculated using original video size ratio.
     * - If only width specified in params, then height is automatically calculated using original video size ratio.
     * - If width and height specified in params then these values are returned, but the resulted video size can be unproportionate.
     * @param $sFile original video file path (to check original video size ratio)
     * @param $aParams filter params
     * @return WxH string
     */
    protected function _getOptionSizeForVideo ($sFile, $aParams) 
    {
        if (isset($aParams['w']) && isset($aParams['h']))
            return $aParams['w'] . 'x' . $aParams['h'];

        if (!isset($aParams['w']) && !isset($aParams['h']))
            $aParams['h'] = '360';

        $fRatio = 16/9;
        $aSize = $this->getSize($sFile);
        if ($aSize)
            $fRatio = $aSize['w'] / $aSize['h'];

        if (isset($aParams['h']))
            return round($fRatio * $aParams['h'] / 2) * 2 . 'x' . $aParams['h'];

        if (isset($aParams['w']))
            return  $aParams['w'] . 'x' . round($aParams['w'] / $fRatio / 2) * 2;

        return '640x360'; // should never happen
    }

    protected function _getFfmpegThreadsParams ()
    {
        $s = '';

        // optionally limit number of threads
        if (defined('BX_SYSTEM_FFMPEG_THREAD') && ($iThreads = (int)constant('BX_SYSTEM_FFMPEG_THREAD'))) {
            $s .= '-threads ' . $iThreads;
        }

        return $s;
    }
}

/** @} */
