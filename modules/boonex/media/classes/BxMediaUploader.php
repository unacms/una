<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MediaManager MediaManager
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMediaUploader extends BxDolUploader
{
    protected $_sIframeId;
    protected $_sUploaderFormTemplate = 'media_uploader_form.html';
    protected $_oModule;

    function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        $this->_oModule = BxDolModule::getInstance('bx_media');
        
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
        $this->_sIframeId = 'bx-form-input-files-' . $sUniqId . '-iframe';
        $this->_sButtonTemplate = 'media_uploader_button.html';
    }

    /**
     * Get uploader button title
     */
    public function getUploaderButtonTitle($mixed = false)
    {
        return _t('_bx_media_uploader_button_title');
    }
    
    /**
     * Show uploader form.
     * @return HTML string
     */
    public function getUploaderForm($isMultiple = true, $iContentId = false, $isPrivate = true)
    {
        $aStorageData = array();
        $aGhosts = BxDolStorageQuery::getAllGhosts(array('profile_id' => bx_get_logged_profile_id()));
        foreach ($aGhosts as $k => $aGhost) {
            $oStorage = BxDolStorage::getObjectInstance($aGhost['object']);
            if ($oStorage){
                $aStorageData[] = array('uploader_instance_name' => $this->getNameJsInstanceUploader(),'url' => $oStorage->getFileUrlById($aGhost['id']));
            }
        }
        
        $sAcceptedFiles = $this->getAcceptedFilesExtensions();
        return $this->_oModule->_oTemplate->parseHtmlByName($this->_sUploaderFormTemplate, array(
            'form_container_id' => $this->_sFormContainerId,
            'errors_container_id' => $this->_sErrorsContainerId,
            'uploader_instance_name' => $this->getNameJsInstanceUploader(),
            'restrictions_text' => $this->getRestrictionsText(),
            'iframe_id' => $this->_sIframeId,
            'engine' => $this->_aObject['object'],
            'storage_object' => $this->_sStorageObject,
            'uniq_id' => $this->_sUniqId,
            'multiple' => $isMultiple,
            'filepond_multiple' => $isMultiple ? 'true' : 'false',
            'content_id' => $iContentId,
            'storage_private' => $isPrivate,
            'accepted_files' => null === $sAcceptedFiles ? '' : 'accept="' . bx_js_string($sAcceptedFiles) . '"',
            'bx_if:recent_files' => array(
				'condition' => count($aStorageData) >0,
				'content' => array(
                    'bx_repeat:items' => $aStorageData,
                    'item_count' => count($aStorageData)
                )
			),
        ));
    }
    
    /**
     * Show uploader button.
     * @return HTML string
     */
    public function getUploaderButton($mixedGhostTemplate, $isMultiple = true, $aParams = array(), $bDynamic = false)
    {
        $sJsValue = '';
        if (is_array($mixedGhostTemplate)) {
            $sJsValue = "{\n";
            foreach ($mixedGhostTemplate as $iFileId => $s) {
                $sJsValue .= $iFileId . ':' . "'" . bx_js_string($s, BX_ESCAPE_STR_APOS) . "',\n";
            }
            $sJsValue = substr($sJsValue, 0, -2);
            $sJsValue .= "}\n";
        } else {
            $sJsValue = "'" . bx_js_string($mixedGhostTemplate, BX_ESCAPE_STR_APOS) . "'";
        }
        $aParamsDefault = array (
            'uploader_instance_name' => $this->getNameJsInstanceUploader(),
            'engine' => $this->_aObject['object'],
            'storage_object' => $this->_sStorageObject,
            'images_transcoder' => '',
            'uniq_id' => $this->_sUniqId,
            'template_ghost' => $sJsValue,
            'multiple' => $isMultiple ? 1 : 0,
            'storage_private' => isset($aParams['storage_private']) ? $aParams['storage_private'] : 1,
            'action_url' => $this->_oModule->_oConfig->getBaseUri(),
            'uploader_instance_name' => $this->getNameJsInstanceUploader(),
        );
        $aParams = array_merge($aParamsDefault, $aParams);
        
        $this->_oModule->_oTemplate->addJs(
            array(
                'BxMediaUploader.js',
                BX_DIRECTORY_PATH_MODULES . 'boonex/media/plugins/filepond-master/dist/|filepond.min.js',
                BX_DIRECTORY_PATH_MODULES . 'boonex/media/plugins/filepond-plugin-image-preview-master/dist/|filepond-plugin-image-preview.min.js',
                BX_DIRECTORY_PATH_MODULES . 'boonex/media/plugins/filepond-plugin-media-preview-master/dist/|filepond-plugin-media-preview.min.js',
                BX_DIRECTORY_PATH_MODULES . 'boonex/media/plugins/filepond-plugin-file-validate-type-master/dist/|filepond-plugin-file-validate-type.min.js',
                BX_DIRECTORY_PATH_MODULES . 'boonex/media/plugins/filepond-plugin-image-edit-master/dist/|filepond-plugin-image-edit.min.js'
            )
        );
        $this->_oModule->_oTemplate->addCss(
            array(
                'main.css',
                BX_DIRECTORY_PATH_MODULES . 'boonex/media/plugins/filepond-master/dist/|filepond.min.css',
                BX_DIRECTORY_PATH_MODULES . 'boonex/media/plugins/filepond-plugin-image-preview-master/dist/|filepond-plugin-image-preview.min.css',
                BX_DIRECTORY_PATH_MODULES . 'boonex/media/plugins/filepond-plugin-media-preview-master/dist/|filepond-plugin-media-preview.min.css',
                BX_DIRECTORY_PATH_MODULES . 'boonex/media/plugins/filepond-plugin-image-edit-master/dist/|filepond-plugin-image-edit.min.css',
            )
        );
        return $this->addCssJs ($bDynamic) . $this->_oModule->_oTemplate->parseHtmlByName($this->_sButtonTemplate, $aParams);
    }

}

/** @} */
