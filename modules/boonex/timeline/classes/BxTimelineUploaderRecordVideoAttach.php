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

class BxTimelineUploaderRecordVideoAttach extends BxTemplUploaderRecordVideo
{
    protected $_sModule;
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        $this->_sModule = 'bx_timeline';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);        

        parent::__construct($aObject, $sStorageObject, $sUniqId, $this->_oModule->_oTemplate);

        $this->_sButtonTemplate = 'uploader_button_record_video_attach.html';
    }
}

/** @} */
