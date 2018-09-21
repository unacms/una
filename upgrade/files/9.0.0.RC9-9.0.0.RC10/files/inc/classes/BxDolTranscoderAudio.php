<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * This class transcodes audio files.
 *
 * To generate audio which plays in all moders browsers, 
 * you need to generate .mp3 audio.
 * Audio is converting upon first access, so it is probably better to force audio conversion by calling @see BxDolTranscoderAudio::getFileUrl just after video uploading.
 * Audio for conversion is queued and when cron is run video conversion is performed.
 *
 * Transcoder object and other params are the same as in @see BxDolTranscoderImage, but it is highly recommended to disable 'atime_pruning' and 'atime_tracking', 
 * or set it to fairly big value, since audio transcoding is not performed on the fly and takes some time. 
 *
 * Available filters:
 * - Mp3 - this filter convert audio into .mp3 format, the parameters are the following:
 *     - audio_bitrate - audio bitrate in kb (128k by default)
 *     - ffmpeg_options - additional command line options for ffmepeg, as key => value array (empty by default)
 *
 *
 * Example of usage:
 * @code
 * // transcoder object which generate .mp3 file
 * $oTranscoderMp3 = BxDolTranscoder::getObjectInstance('bx_audio_mp3'); 
 *
 * // make sure to call it only once (for example: during module installation), before the first usage, no need to call it every time
 * $oTranscoderMp3->registerHandlers(); 
 *
 * // get URLs of transcoded videos and video thumbnail, 33 is ID of original video file stored in specified storage object
 * $sUrlMp3 = $oTranscoderMp3->getFileUrl(33);
 *
 * echo 'My voice: <audio controls><source src="' . $sUrlMp3 . '" type="audio/mpeg"></audio>'; 
 * @endcode
 *
 * Also @see transcoder_audio sample for complete example.
 */
class BxDolTranscoderAudio extends BxDolTranscoderVideo implements iBxDolFactoryObject
{
    protected function __construct($aObject, $oStorage)
    {
        parent::__construct($aObject, $oStorage);
        $this->_oDb = new BxDolTranscoderAudioQuery($aObject);
        $this->_sQueueTable = $this->_oDb->getQueueTable();
    }

    /**
     * check if transcoder suppors given file mime type
     */ 
    public function isMimeTypeSupported($sMimeType)
    {
        if (0 === strncmp($sMimeType, 'audio/', 6))
            return true;

        return false;
    }

    /**
     * Convert video to .mp4 format
     */
    protected function applyFilter_Mp3 ($sFile, $aParams)
    {
        return $this->_convertVideo($sFile, $sFile, '.mp3', $aParams, array (
            'acodec' => 'libmp3lame',
            'ar' => '44100',
            'b:a' => isset($aParams['audio_bitrate']) ? $aParams['audio_bitrate'] . 'k' : '128k',
        ));
    }
}

/** @} */
