<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Upload files using crop interface
 * @see BxDolUploader
 */
class BxBaseUploaderCrop extends BxDolUploader
{
    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);

        $this->_sButtonTemplate = 'uploader_button_crop.html';
        $this->_sJsTemplate = 'uploader_button_crop_js.html';
        $this->_sUploaderFormTemplate = 'uploader_form_crop.html';

        $this->addJs([
            'croppie/croppie.min.js'
        ]);

        $this->addCss([
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'croppie/|croppie.css'
        ]);
    }

    /**
     * Get uploader button title
     */
    public function getUploaderButtonTitle($mixed = false)
    {
        if (is_string($mixed))
            return $mixed;
        elseif (is_array($mixed) && isset($mixed['Crop']))
            return $mixed['Crop'];
        else
            return _t('_sys_uploader_crop_button_name');
    }

    /**
     * add necessary js, css files and js translations
     */ 
    public function addCssJs($bDynamic = false)
    {
        $s = parent::addCssJs($bDynamic);
        $s .= $this->_oTemplate->addJsTranslation([
            '_sys_uploader_crop_err_upload', 
            '_sys_uploader_crop_wrong_ext'
        ], $bDynamic);
        return $bDynamic ? $s : '';
    }
    
    /**
     * Show uploader form.
     * @return HTML string
     */
    public function getUploaderForm($isMultiple = true, $iContentId = false, $isPrivate = true)
    {
    	$oForm = new BxTemplFormView(array());
    	$aFormInput = array('type' => 'file', 'name' => 'f');

        return $this->_oTemplate->parseHtmlByName($this->_sUploaderFormTemplate, array(
            'form_container_id' => $this->_sFormContainerId,
        	'file_field' => $oForm->genRow($aFormInput),
            'errors_container_id' => $this->_sErrorsContainerId,
            'uploader_instance_name' => $this->getNameJsInstanceUploader(),
            'restrictions_text' => $this->getRestrictionsText(),
            'engine' => $this->_aObject['object'],
            'storage_object' => $this->_sStorageObject,
            'uniq_id' => $this->_sUniqId,
            'multiple' => $isMultiple,
            'content_id' => $iContentId,
            'storage_private' => $isPrivate,
        ));
    }

    public function handleUploads ($iProfileId, $mixedFiles, $isMultiple = true, $iContentId = false, $bPrivate = true)
    {
        ob_start();
        parent::handleUploads ($iProfileId, $mixedFiles, $isMultiple, $iContentId, $bPrivate);
        $s = ob_get_clean();
        echo strip_tags(str_replace('window.parent.', 'window.', $s));
    }

}

/** @} */
