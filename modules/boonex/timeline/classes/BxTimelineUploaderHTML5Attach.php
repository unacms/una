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
            'on_upload' => 'function(iContentId) {' . $sJsObject . '.onAttachMediaUpload(iContentId);}',
            'on_restore_ghosts' => 'function(aData) {' . $sJsObject . '.onAttachMediaRestoreGhosts(aData);}',
        ]);

        return parent::getUploaderJs($mixedGhostTemplate, $isMultiple, $aParams, $bDynamic);
    }
}

/** @} */
