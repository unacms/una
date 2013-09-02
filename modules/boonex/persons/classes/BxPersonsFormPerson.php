<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplFormView');

/**
 * Create/Edit Person Form.
 */
class BxPersonsFormPerson extends BxTemplFormView {

    protected $_oModule;
    protected $_iAccountProfileId;

    public function __construct($aInfo, $oTemplate = false) {                
        parent::__construct($aInfo, $oTemplate);

        bx_import('BxDolProfile');
        $oAccountProfile = BxDolProfile::getInstanceAccountProfile();
        $this->_iAccountProfileId = $oAccountProfile->id();

        $this->_oModule = BxDolModule::getInstance('bx_persons');

        if (isset($this->aInputs[BxPersonsConfig::$FIELD_PICTURE])) {
            $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['storage_object'] = BxPersonsConfig::$OBJECT_STORAGE;
            $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['uploaders'] = array('bx_persons_avatar');
            $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['images_transcoder'] = BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_THUMB;
            $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['multiple'] = false;
            $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['content_id'] = 0;
            $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['upload_buttons_titles'] = _t('_bx_persons_form_person_input_picture_btn_upload');
            $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['ghost_template'] = '';
        }   
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())  {

        if (isset($this->aInputs[BxPersonsConfig::$FIELD_PICTURE])) {

            if ($aValues && !empty($aValues['id'])) 
                $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['content_id'] = $aValues['id'];

            bx_import('BxDolUploader');
            $oUploader = BxDolUploader::getObjectInstance($this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['uploaders'][0], $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['storage_object'], rand(0, PHP_INT_MAX));
            
            $aVars = array (
                'name' => $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['name'],
                'content_id' => $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['content_id'],
                'current_picture_id' => (isset($aValues[BxPersonsConfig::$FIELD_PICTURE]) ? $aValues[BxPersonsConfig::$FIELD_PICTURE] : 0),
                'uploader_js_instance_name' => $oUploader->getNameJsInstanceUploader(),
                'bx_if:not_required' => array (
                    'condition' => !$this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['required'],
                    'content' => array(),
                ),
            );            
            $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName('form_ghost_template.html', $aVars);
        }
        
        return parent::initChecker($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false) {
        $aValsToAdd = array_merge($aValsToAdd, array (
            BxPersonsConfig::$FIELD_AUTHOR => $this->_iAccountProfileId,
            BxPersonsConfig::$FIELD_ADDED => time(),
            BxPersonsConfig::$FIELD_CHANGED => time(),
        ));
        if ($iContentId = parent::insert ($aValsToAdd, $isIgnore)) 
            $this->_processFiles ((int)$this->getCleanValue(BxPersonsConfig::$FIELD_PICTURE), $iContentId, true);
        return $iContentId;
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null) {        
        $aValsToAdd[BxPersonsConfig::$FIELD_CHANGED] = time();
        if ($iRet = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges)) 
            $this->_processFiles ((int)$this->getCleanValue(BxPersonsConfig::$FIELD_PICTURE), $iContentId);
        return $iRet;
    }

    function delete ($iContentId, $aContentInfo = array()) {

        if (!$this->_deleteFile($aContentInfo[BxPersonsConfig::$FIELD_PICTURE]))
            return false;

        return parent::delete($iContentId);
    }

    function deleteGhost ($iFileId, $iContentId, $isRestoreOriginal = true) {

        if (CHECK_ACTION_RESULT_ALLOWED != ($sMsg = $this->_oModule->isAllowedAdd()))
            return $sMsg;

        bx_import('BxDolStorage');
        $oStorage = BxDolStorage::getObjectInstance(BxPersonsConfig::$OBJECT_STORAGE);
        if (!$oStorage)
            return _t('_sys_txt_error_occured');

        if (!$oStorage->deleteFile($iFileId, $this->_iAccountProfileId))
            return _t('_sys_txt_error_occured');

        $this->_oModule->_oDb->updateContentPictureById($iContentId, $this->_iAccountProfileId, 0);

        return '';
    }

    function discardGhost ($iFileId, $iContentId, $isRestoreOriginal = true) {

        if (CHECK_ACTION_RESULT_ALLOWED != ($sMsg = $this->_oModule->isAllowedAdd()))
            return $sMsg;

        bx_import('BxDolStorage');
        $oStorage = BxDolStorage::getObjectInstance(BxPersonsConfig::$OBJECT_STORAGE);
        if (!$oStorage)
            return _t('_sys_txt_error_occured');
    
        
        $aFiles = $oStorage->getGhosts($this->_iAccountProfileId, $iContentId);        
        if (!$aFiles)
            return _t('_sys_txt_error_occured');

        $isFileDeleted = false;
        foreach ($aFiles as $aFile) {
            if ($aFile['id'] == $iFileId) {
                $isFileDeleted = $oStorage->deleteFile($aFile['id'], $this->_iAccountProfileId);
                break;
            }
        }
 
        if (!$isFileDeleted)
            return _t('_sys_txt_error_occured');

        if ($isRestoreOriginal && $iContentId) {
            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
            if (!$aContentInfo)
                return _t('_sys_txt_error_occured');
            if ($aContentInfo[BxPersonsConfig::$FIELD_PICTURE])
                if (!$oStorage->insertGhost($aContentInfo[BxPersonsConfig::$FIELD_PICTURE], $this->_iAccountProfileId, $iContentId))
                    return _t('_sys_txt_error_occured');
        }

        return '';
    }

    function _processFiles ($iFileId, $iContentId = 0, $isCheckForZero = false) {
        bx_import('BxDolStorage');
        $oStorage = BxDolStorage::getObjectInstance(BxPersonsConfig::$OBJECT_STORAGE);
        if (!$oStorage)
            return false;

        $iErrors = 0;

        $a = array($iContentId);
        if ($isCheckForZero)
            $a[] = 0;

        foreach ($a as $iForceContentId) {
            $aFiles = $oStorage->getGhosts($this->_iAccountProfileId, $iForceContentId);
            foreach ($aFiles as $aFile) {
                if ($iFileId == $aFile['id']) { 
                    // save only one file
                    if (0 == $iForceContentId && $isCheckForZero) // if we are adding content, then associate ghosts with just added content
                        if (!$oStorage->updateGhostsContentId($iFileId, $this->_iAccountProfileId, $iContentId))
                            ++$iErrors;
                } else {
                    // delete all other files
                    if (!$oStorage->deleteFile($aFile['id'], $this->_iAccountProfileId)) 
                        ++$iErrors;
                }
            }
        }
        return $iErrors ? false : true;
    }

    function _deleteFile ($iFileId) {

        if (!$iFileId)
            return true;        

        bx_import('BxDolStorage');
        if (!($oStorage = BxDolStorage::getObjectInstance(BxPersonsConfig::$OBJECT_STORAGE)))
            return false;

        if (!$oStorage->getFile($iFileId))
            return true;

        return $oStorage->deleteFile($iFileId, $this->_iAccountProfileId);
    }


    function addCssJs () {

        if (!isset($this->aParams['view_mode']) || !$this->aParams['view_mode']) {
            if (self::$_isCssJsAdded)
                return;
            $this->_oModule->_oTemplate->addJs('forms.js');
        }

        return parent::addCssJs ();
    }


}

/** @} */
