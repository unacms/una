<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolTranscoder');
bx_import('BxDolTranscoderVideoQuery');

/**
 * @page objects
 * @section transcoder_videos Videos Transcoder
 * @ref BxDolTranscoderVideo
 */


class BxDolTranscoderVideo extends BxDolTranscoder implements iBxDolFactoryObject
{
    protected function __construct($aObject, $oStorage)
    {
        parent::__construct($aObject, $oStorage);
        $this->_sQueueStorage = ''; // TODO:
        $this->_oDb = new BxDolTranscoderVideoQuery($aObject);
        $this->_sQueueTable = $this->_oDb->getQueueTable();
    }

    public function getDevicePixelRatioHandlerSuffix ()
    {
        return '';
    }

    /**
     * If video not processed yet then empty string is returned for video, and predefined image is returned for video poster
     */
    public function getFileUrlNotReady($mixedHandler)
    {
        return $this->_isPosterFilter() ? BxDolTemplate::getInstance()->getImageUrl('video-na.png') : '';
    }

    /**
     * Convert video to .jpg format - video poster
     */
    protected function applyFilter_Poster ($sFile, $aParams)
    {
	    bx_import('BxDolImageResize');
    	$oImage = BxDolImageResize::getInstance();

        $sFileOut = $this->getTmpFilename('.jpg');
        $bRet = false;
        $aSeconds = array (0, 3, 5, 0);
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
            unlink($sFileOut);
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
            'b:v' => isset($aParams['video_bitrate']) ? $aParams['video_bitrate'] . 'k' : '',
            'acodec' => 'aac',
            'ar' => '44100',
            'b:a' => isset($aParams['audio_bitrate']) ? $aParams['audio_bitrate'] . 'k' : '128k',
        ));
    }

    /**
     * Convert video to .webm format
     */
    protected function applyFilter_Webm ($sFile, $aParams)
    {
        return $this->_convertVideo($sFile, $sFile, '.webm', $aParams, array (
            's' => $this->_getOptionSizeForVideo ($sFile, $aParams),
            'b:v' => isset($aParams['video_bitrate']) ? $aParams['video_bitrate'] . 'k' : '',
            'acodec' => 'libvorbis',
            'ar' => '44100',
            'b:a' => isset($aParams['audio_bitrate']) ? $aParams['audio_bitrate'] . 'k' : '128k',
        ));
    }

    /**
     * Convert video using ffmpeg binary. Video conversion otput is written to the log variable.
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

        $sCommand = BX_SYSTEM_FFMPEG . ' -y -i "' . escapeshellcmd($sFile) . '" ' . $sOptions . ' ' . $sFileOut;
        $sOutput = `$sCommand`;
        $this->addToLog("\n---\n{$sCommand}\n{$sOutput}\n");

        if (!file_exists($sFileOut) || 0 == filesize($sFileOut))
            return false;

        if ($bRename && !rename($sFileOut, $sFile)) // rename tmp file, if tmp file was generated
            return false;

        return true;
    }

    /**
     * Get video size. 
     * It generated image from the video and returns size of this image.
     * @param $sFile path to video file
     * @return array where 'w' is width and 'h' is height
     */
    protected function _getVideoSize ($sFile)
    {
        $sFileOut = $this->getTmpFilename('.jpg');

        $bRet = $this->_convertVideo($sFile, $sFileOut, '.jpg', array(), array (
            'ss' => 0,
            'vframes' => 1,
            'f' => 'image2',
            'an' => ' ',
        ));

        if (!$bRet)        
            return false;

	    bx_import('BxDolImageResize');
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
        $aSize = $this->_getVideoSize($sFile);
        if ($aSize)
            $fRatio = $aSize['w'] / $aSize['h'];

        if (isset($aParams['h']))
            return round($fRatio * $aParams['h'] / 2) * 2 . 'x' . $aParams['h'];

        if (isset($aParams['w']))
            return  $aParams['w'] . 'x' . round($aParams['w'] / $fRatio / 2) * 2;

        return '640x360'; // should never happen
    }

    /**
     * Checks if 'Poster' filter is enabled for curent transcoder
     */
    protected function _isPosterFilter ()
    {
        $this->initFilters ();
        foreach ($this->_aObject['filters'] as $aParams)
            if ('Poster' == $aParams['filter'])
                return true;
        return false;
    }
}

/** @} */
