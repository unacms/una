<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create\edit entry attachments menu
 */
class BxBaseModTextMenuAttachments extends BxTemplMenu
{
    protected $_sModule;
    protected $_oModule;
    protected $_iContentId;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
 
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->addMarkers(array(
            'js_object' => $this->_oModule->_oConfig->getJsObject('poll'),
            'js_object_link' => $this->_oModule->_oConfig->getJsObject('links'),
        ));
    }
    
    public function setContentId($iContentId)
    {
        $this->_iContentId = (int)$iContentId;
        $this->addMarkers(array('content_id' => (int)$this->_iContentId));
    }

    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a))
            return false;

        $sUploader = '';
        switch ($a['name']) {
            case 'photo_simple':
                $sUploader = 'photos_simple';
                break;
            case 'photo_html5':
                $sUploader = 'photos_html5';
                break;
            case 'video_simple':
                $sUploader = 'videos_simple';
                break;
            case 'video_html5':
                $sUploader = 'videos_html5';
                break;
            case 'file_simple':
                $sUploader = 'files_simple';
                break;
            case 'file_html5':
                $sUploader = 'files_html5';
                break;
        }

        if(!empty($sUploader)) {
            $sUploader = 'js_object_uploader_' . $sUploader;
            if(!isset($this->_aMarkers[$sUploader]))
                return false;
        }

        return true;
    }
}

/** @} */
