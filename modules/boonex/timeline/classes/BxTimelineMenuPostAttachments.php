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

class BxTimelineMenuPostAttachments extends BxTemplMenu
{
    protected $_oModule;

    protected $_iEvent;
    
    /**
     * An array with uploaders, which are 
     * available (active) in create form.
     */
    protected $_aUploadersInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_timeline');

        $this->addMarkers(array(
            'js_object' => $this->_oModule->_oConfig->getJsObject('post'),
        ));
    }

    public function setEventById($iEventId, $aBrowseParams = array())
    {
        $this->_iEvent = $iEventId;

        $this->addMarkers(array(
            'content_id' => $this->_iEvent,
        ));
    }

    public function setUploadersInfo($aUploadersInfo)
    {
        $this->_aUploadersInfo = $aUploadersInfo;
    }

    public function isMenuItem($sName)
    {
        if(empty($this->_aObject['menu_items'][$sName]) || !is_array($this->_aObject['menu_items'][$sName]))
            return false;

        $aMenuItem = $this->_aObject['menu_items'][$sName];
        if(isset($aMenuItem['active']) && !$aMenuItem['active'])
            return false;

        return $this->_isVisible($aMenuItem);
    }
    
    protected function _getMenuItem($a)
    {
        if(isset($a['active']) && !$a['active'])
            return false;

        if(isset($a['visible_for_levels']) && !$this->_isVisible($a))
            return false;
        
        if(($sUploader = $this->_oModule->_oConfig->getUploaderByMenuItem($a['name'])) !== false) {
            if(!isset($this->_aUploadersInfo[$sUploader]))
                return false;

            $this->addMarkers(array(
                'js_object_' . str_replace('-', '_', $a['name']) => $this->_aUploadersInfo[$sUploader]['js_object']
            ));
        }

        return parent::_getMenuItem($a);
    }
}

/** @} */
