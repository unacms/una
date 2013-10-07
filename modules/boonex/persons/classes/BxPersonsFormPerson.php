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
    protected $_aImageFields;

    public function __construct($aInfo, $oTemplate = false) {                
        parent::__construct($aInfo, $oTemplate);

        $this->_aImageFields = array (
            BxPersonsConfig::$FIELD_PICTURE => array (
                'storage_object' => BxPersonsConfig::$OBJECT_STORAGE,
                'images_transcoder' => BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_THUMB,
                'uploaders' => array('bx_persons_avatar'),
            ),
            BxPersonsConfig::$FIELD_COVER => array (
                'storage_object' => BxPersonsConfig::$OBJECT_STORAGE_COVER,
                'images_transcoder' => BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_COVER_THUMB,
                'uploaders' => array('bx_persons_cover'),
            ),
        );

        bx_import('BxDolProfile');
        $oAccountProfile = BxDolProfile::getInstanceAccountProfile();
        if ($oAccountProfile)
            $this->_iAccountProfileId = $oAccountProfile->id();

        $this->_oModule = BxDolModule::getInstance('bx_persons');


        $aDefaultsFieldImage = array (
            'storage_object' => BxPersonsConfig::$OBJECT_STORAGE,
            'uploaders' => array('bx_persons_avatar'),
            'images_transcoder' => BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_THUMB,
            'multiple' => false,
            'content_id' => 0,
            'upload_buttons_titles' => _t('_bx_persons_form_person_input_picture_btn_upload'),
            'ghost_template' => '',
        );

        foreach ($this->_aImageFields as $sField => $aVals) {
            if (!isset($this->aInputs[$sField]))
                continue;
            foreach ($aDefaultsFieldImage as $k => $v )
                $this->aInputs[$sField][$k] = isset($aVals[$k]) ? $aVals[$k] : $v;
        }

    }

    function initChecker ($aValues = array (), $aSpecificValues = array())  {

        foreach ($this->_aImageFields as $sField => $aVals) {
            if (!isset($this->aInputs[$sField]))
                continue;

            if ($aValues && !empty($aValues['id'])) 
                $this->aInputs[$sField]['content_id'] = $aValues['id'];

            bx_import('BxDolUploader');
            $oUploader = BxDolUploader::getObjectInstance($this->aInputs[$sField]['uploaders'][0], $this->aInputs[$sField]['storage_object'], rand(0, PHP_INT_MAX));
            
            $aVars = array (
                'name' => $this->aInputs[$sField]['name'],
                'content_id' => $this->aInputs[$sField]['content_id'],
                'current_picture_id' => (isset($aValues[$sField]) ? $aValues[$sField] : 0),
                'uploader_js_instance_name' => $oUploader->getNameJsInstanceUploader(),
                'bx_if:not_required' => array (
                    'condition' => !$this->aInputs[$sField]['required'],
                    'content' => array(
                        'name' => $this->aInputs[$sField]['name'],
                    ),
                ),
            );            
            $this->aInputs[$sField]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName('form_ghost_template.html', $aVars);
        }
        
        return parent::initChecker($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false) {
        $aValsToAdd = array_merge($aValsToAdd, array (
            BxPersonsConfig::$FIELD_AUTHOR => $this->_iAccountProfileId,
            BxPersonsConfig::$FIELD_ADDED => time(),
            BxPersonsConfig::$FIELD_CHANGED => time(),
        ));
        if ($iContentId = parent::insert ($aValsToAdd, $isIgnore)) {
            foreach ($this->_aImageFields as $sField => $aVals)
                $this->_processFiles ((int)$this->getCleanValue($sField), $iContentId, true, $sField);
        }
        return $iContentId;
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null) {        
        $aValsToAdd[BxPersonsConfig::$FIELD_CHANGED] = time();
        if ($iRet = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges)) {
            foreach ($this->_aImageFields as $sField => $aVals)
                $this->_processFiles ((int)$this->getCleanValue($sField), $iContentId, false, $sField);
        }
        return $iRet;
    }

    function delete ($iContentId, $aContentInfo = array()) {

        // TODO: clean db field after deletion
        // TODO: delete proper file! cover or avatar - NOT BOTH!
        // TODO: also old image is not deleted when new image is uploaded
        if (!$this->_deleteFile($aContentInfo[BxPersonsConfig::$FIELD_PICTURE], BxPersonsConfig::$OBJECT_STORAGE) && !$this->_deleteFile($aContentInfo[BxPersonsConfig::$FIELD_COVER], BxPersonsConfig::$OBJECT_STORAGE_COVER))
            return false;

        return parent::delete($iContentId);
    }

    function deleteGhost ($iFileId, $iContentId, $isRestoreOriginal = true, $sFieldName = '') {

        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedAdd()))
            return $sMsg;

        foreach ($this->_aImageFields as $sField => $aVals) {

            if ($sField != $sFieldName)
                continue;

            bx_import('BxDolStorage');
            $oStorage = BxDolStorage::getObjectInstance($aVals['storage_object']);
            if (!$oStorage)
                return _t('_sys_txt_error_occured');

            if (!$oStorage->deleteFile($iFileId, $this->_iAccountProfileId))
                return _t('_sys_txt_error_occured');

            $this->_oModule->_oDb->updateContentPictureById($iContentId, $this->_iAccountProfileId, 0, $sField);
        }

        return '';
    }

    function discardGhost ($iFileId, $iContentId, $isRestoreOriginal = true, $sFieldName = '') {

        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->isAllowedAdd()))
            return $sMsg;


        foreach ($this->_aImageFields as $sField => $aVals) {

            if ($sField != $sFieldName)
                continue;

            bx_import('BxDolStorage');
            $oStorage = BxDolStorage::getObjectInstance($aVals['storage_object']);
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

                
                if ($aContentInfo[$sField])
                    if (!$oStorage->insertGhost($aContentInfo[$sField], $this->_iAccountProfileId, $iContentId))
                        return _t('_sys_txt_error_occured');
                
            }

        }

        return '';
    }

    function _processFiles ($iFileId, $iContentId = 0, $isCheckForZero = false, $sField = '') {
        if (!isset($this->_aImageFields[$sField]))
            return false;

        bx_import('BxDolStorage');
        $oStorage = BxDolStorage::getObjectInstance($this->_aImageFields[$sField]['storage_object']);
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

    function _deleteFile ($iFileId, $sStorageObject) {

        if (!$iFileId)
            return true;        

        bx_import('BxDolStorage');
        if (!($oStorage = BxDolStorage::getObjectInstance($sStorageObject)))
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
