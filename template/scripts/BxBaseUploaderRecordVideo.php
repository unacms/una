<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Upload files using WebRTC webcam capture.
 * @see BxDolUploader
 */
define('BX_UPLOADER_AUDIO_BITRATE', 128000); //128Kbit for audio
define('BX_UPLOADER_VIDEO_BITRATE', 1000000); //1Mbit for video

class BxBaseUploaderRecordVideo extends BxBaseUploaderSimple
{
    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);

        $this->_sButtonTemplate = 'uploader_button_record_video.html';
        $this->_sJsTemplate = 'uploader_button_record_video_js.html';
        $this->_sUploaderFormTemplate = 'uploader_form_record_video.html';

        $this->addJs([
            'RecordRTC.min.js'
        ]);
    }

    public function getUploaderButtonTitle($mixed = false)
    {
        return _t('_sys_uploader_record_video_button_name');
    }

    public function getUploaderJs($mixedGhostTemplate, $isMultiple = true, $aParams = array(), $bDynamic = false)
    {
        if(!$aParams) 
            $aParams = [];

        return parent::getUploaderJs($mixedGhostTemplate, $isMultiple, array_merge($aParams, [
            'audio_bitrate' => BX_UPLOADER_AUDIO_BITRATE,
            'video_bitrate' => BX_UPLOADER_VIDEO_BITRATE
        ]), $bDynamic);
    }

    public function addCssJs ($bDynamic = false)
    {
        $s = parent::addCssJs($bDynamic);
        $s .= $this->_oTemplate->addJsTranslation([
            '_sys_uploader_camera_capture_failed',
            '_sys_uploader_record_video_mb',
            '_sys_uploader_unsupported_browser',
        ], $bDynamic);
        return $bDynamic ? $s : '';
    }

    protected function getRestrictionsText ()
    {
        $sTextRestrictions = '';
        $oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);
        if (!$oStorage)
            return '';

        $sTextRestrictions = $oStorage->getRestrictionsTextFileSize(bx_get_logged_profile_id());

        if ($sTextRestrictions)
            $sTextRestrictions = '<div class="bx-uploader-msg-info bx-def-font-grayed">' . $sTextRestrictions . '</div>';

        return $sTextRestrictions;
    }

    /**
     * Handle uploads here.
     * @param $mixedFiles as usual $_FILES['some_name'] array, but maybe some other params depending on the uploader
     * @return nothing, but if some files failed to upload, the actual error message can be determined by calling BxDolUploader::getUploadErrorMessages()
     */
    public function handleUploads ($iProfileId, $mixedFiles, $isMultiple = true, $iContentId = false, $bPrivate = true)
    {
        //suppress any output here, because we are not posting to an iframe anymore and thus we must avoid a call to a window.parent as in parent::handleUploads
        //instead we are posting via AJAX to pass a recorded video as a blob object via a FormData object
        ob_start();
        parent::handleUploads($iProfileId, $mixedFiles, $isMultiple, $iContentId, $bPrivate);
        ob_end_clean();
        echo bx_js_string($this->getUploadErrorMessages(), BX_ESCAPE_STR_APOS);
    }
}

/** @} */
