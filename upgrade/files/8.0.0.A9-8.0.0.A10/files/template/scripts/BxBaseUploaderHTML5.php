<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import ('BxDolUploader');

/**
 * Upload files using AJAX uploader with multiple files selection support (without flash),
 * it works in Firefox and WebKit(Safari, Chrome) browsers only, but has fallback for other browsers (IE, Opera).
 * @see BxDolUploader
 */
class BxBaseUploaderHTML5 extends BxDolUploader
{
    protected $_sDivId; ///< div id where upload button will be placed

    function __construct ($aObject, $sStorageObject, $sUniqId)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId);

        $this->_sDivId = 'bx-form-input-files-' . $sUniqId . '-div-' . $this->_aObject['object'];
        $this->_sButtonTemplate = 'uploader_button_html5.html';
    }

    /**
     * add necessary js, css files and js translations
     */ 
    public function addCssJs ()
    {
        parent::addCssJs ();
        $this->_oTemplate->addJs('fileuploader.js');
    }

    /**
     * Get uploader button title
     */
    public function getUploaderButtonTitle($mixed = false)
    {
        if (is_string($mixed))
            return $mixed;
        elseif (is_array($mixed) && isset($mixed['HTML5']))
            return $mixed['HTML5'];
        else
            return _t('_sys_uploader_html5_button_name');
    }

    /**
     * Show uploader form.
     * @return HTML string
     */
    public function getUploaderForm($isMultiple = true, $iContentId = false)
    {
        return $this->_oTemplate->parseHtmlByName('uploader_form_html5.html', array(
            'form_container_id' => $this->_sFormContainerId,
            'errors_container_id' => $this->_sErrorsContainerId,
            'uploader_instance_name' => $this->getNameJsInstanceUploader(),
            'restrictions_text' => $this->getRestrictionsText(),
            'div_id' => $this->_sDivId,
            'content_id' => $iContentId,
        ));
    }

    /**
     * Handle uploads here.
     * @param $mixedFiles as usual $_FILES['some_name'] array, but maybe some other params depending on the uploader
     * @return nothing, but if some files failed to upload, the actual error message can be determined by calling BxDolUploader::getUploadErrorMessages()
     */
    public function handleUploads ($iProfileId, $mixedFiles, $isMultiple = true, $iContentId = false)
    {
        bx_import('BxDolStorage');
        $oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);

        $iProfileId = bx_get_logged_profile_id();

        if (!$isMultiple)
            $this->cleanupGhostsForProfile($iProfileId, $iContentId);

        if (bx_get('qqfile'))
            $iId = $oStorage->storeFileFromXhr(bx_get('qqfile'), true, $iProfileId, $iContentId);
        else
            $iId = $oStorage->storeFileFromForm($_FILES['qqfile'], true, $iProfileId, $iContentId);

        if ($iId) {
            $aResponse = array ('success' => 1);
        } else {
            $this->appendUploadErrorMessage(_t('_sys_uploader_err_msg', isset($_FILES['qqfile']['name']) ? $_FILES['qqfile']['name'] : bx_get('qqfile'), $oStorage->getErrorString()));
            $aResponse = array ('error' => $this->getUploadErrorMessages());
        }

        echo htmlspecialchars(json_encode($aResponse), ENT_NOQUOTES);
    }
}

/** @} */
