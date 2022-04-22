<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Upload files using standard HTML forms.
 * @see BxDolUploader
 */
class BxBaseUploaderSimple extends BxDolUploader
{
    protected $_sIframeId;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);

        $this->_sIframeId = 'bx-form-input-files-' . $sUniqId . '-iframe';
        $this->_sButtonTemplate = 'uploader_button_simple.html';
        $this->_sJsTemplate = 'uploader_button_simple_js.html';
        $this->_sUploaderFormTemplate = 'uploader_form_simple.html';
    }

    /**
     * Get uploader button title
     */
    public function getUploaderButtonTitle($mixed = false)
    {
        if (is_string($mixed))
            return $mixed;
        elseif (is_array($mixed) && isset($mixed['Simple']))
            return $mixed['Simple'];
        else
            return _t('_sys_uploader_simple_button_name');
    }

    /**
     * Show uploader form.
     * @return HTML string
     */
    public function getUploaderForm($isMultiple = true, $iContentId = false, $isPrivate = true)
    {
        return $this->_oTemplate->parseHtmlByName($this->_sUploaderFormTemplate, array(
            'form_container_id' => $this->_sFormContainerId,
            'errors_container_id' => $this->_sErrorsContainerId,
            'uploader_instance_name' => $this->getNameJsInstanceUploader(),
            'restrictions_text' => $this->getRestrictionsText(),
            'iframe_id' => $this->_sIframeId,
            'engine' => $this->_aObject['object'],
            'storage_object' => $this->_sStorageObject,
            'uniq_id' => $this->_sUniqId,
            'multiple' => $isMultiple,
            'content_id' => $iContentId,
            'storage_private' => $isPrivate,
        ));
    }
}

/** @} */
