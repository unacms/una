<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Upload files using AJAX uploader with multiple files selection support (without flash),
 * it works in Firefox and WebKit(Safari, Chrome) browsers only, but has fallback for other browsers (IE, Opera).
 * @see BxDolUploader
 */
class BxBaseUploaderHTML5 extends BxDolUploader
{
    protected $_sDivId; ///< div id where upload button will be placed
    protected $_sUploaderFormTemplate = 'uploader_form_html5.html';

    function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);

        $this->_sDivId = 'bx-form-input-files-' . $sUniqId . '-div-' . $this->_aObject['object'];
        $this->_sFocusDivId = 'bx-form-input-files-' . $sUniqId . '-focus-' . $this->_aObject['object'];
        $this->_sButtonTemplate = 'uploader_button_html5.html';
    }

    /**
     * add necessary js, css files and js translations
     */ 
    public function addCssJs ($bDynamic = false)
    {
        $s = parent::addCssJs ($bDynamic);
        $s .= $this->_oTemplate->addJs('dropzone/dropzone.min.js', $bDynamic);
        $s .= $this->_oTemplate->addCss(array(
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'dropzone/|dropzone.min.css',
        ), $bDynamic);
        return $bDynamic ? $s : '';
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
    public function getUploaderForm($isMultiple = true, $iContentId = false, $isPrivate = true)
    {
        $iResizeWidth = (int)getParam('client_image_resize_width');
        $iResizeHeight = (int)getParam('client_image_resize_height');
        $sAcceptedFiles = $this->getAcceptedFilesExtensions();
        return $this->_oTemplate->parseHtmlByName($this->_sUploaderFormTemplate, array(
            'form_container_id' => $this->_sFormContainerId,
            'errors_container_id' => $this->_sErrorsContainerId,
            'uploader_instance_name' => $this->getNameJsInstanceUploader(),
            'restrictions_text' => $this->getRestrictionsText(),
            'div_id' => $this->_sDivId,
            'focus_div_id' => $this->_sFocusDivId,
            'content_id' => $iContentId,
            'storage_private' => $isPrivate,
            'max_file_size' => $this->getMaxUploadFileSize(),
            'accepted_files' => null === $sAcceptedFiles ? 'null' : "'" . bx_js_string($sAcceptedFiles) . "'",
            'resize_width' => $iResizeWidth ? $iResizeWidth : 'null',
            'resize_height' => $iResizeHeight ? $iResizeHeight : 'null',
            'resize_method' => $iResizeWidth && $iResizeHeight ? "'contain'" : 'null',
        ));
    }

    /**
     * Handle uploads here.
     * @param $mixedFiles as usual $_FILES['some_name'] array, but maybe some other params depending on the uploader
     * @return nothing, but if some files failed to upload, the actual error message can be determined by calling BxDolUploader::getUploadErrorMessages()
     */
    public function handleUploads ($iProfileId, $mixedFiles, $isMultiple = true, $iContentId = false, $bPrivate = true)
    {
        $oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);

        if (!$isMultiple)
            $this->deleteGhostsForProfile($iProfileId, $iContentId);

        if (bx_get('file')) {
            $iId = $oStorage->storeFileFromXhr(bx_get('file'), $bPrivate, $iProfileId, $iContentId);
        } 
        else {
            $iId = $oStorage->storeFileFromForm($_FILES['file'], $bPrivate, $iProfileId, $iContentId);
        }

        if ($iId) {
            $aResponse = array ('success' => 1);
        } else {
            $this->appendUploadErrorMessage(_t('_sys_uploader_err_msg', isset($_FILES['file']['name']) ? $_FILES['file']['name'] : bx_get('file'), $oStorage->getErrorString()));
            $aResponse = array ('error' => $this->getUploadErrorMessages());
        }

        echo htmlspecialchars(json_encode($aResponse), ENT_NOQUOTES);
    }
}

/** @} */
