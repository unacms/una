<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineUploaderHTML5Attach extends BxTemplUploaderHTML5
{
    protected $_sModule;
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        $this->_sModule = 'bx_timeline';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);

        $this->_sButtonTemplate = 'uploader_button_html5_attach.html';
    }

    public function getUploaderJs($mixedGhostTemplate, $isMultiple = true, $aParams = [], $bDynamic = false)
    {
        $sJsObject = $this->_oModule->_oConfig->getJsObject('post');

        $aParams = array_merge($aParams, [
            'on_upload_before' => 'function(oUploader) {' . $sJsObject . '.onAttachMediaUploadBefore(oUploader);}',
            'on_upload' => 'function(oUploader, iContentId) {' . $sJsObject . '.onAttachMediaUpload(oUploader, iContentId);}',
            'on_restore_ghosts' => 'function(oUploader, aData) {' . $sJsObject . '.onAttachMediaRestoreGhosts(oUploader, aData);}',
        ]);

        return parent::getUploaderJs($mixedGhostTemplate, $isMultiple, $aParams, $bDynamic);
    }
}

/** @} */
