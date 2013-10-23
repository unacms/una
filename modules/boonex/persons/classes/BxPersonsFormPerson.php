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
bx_import('BxDolProfile');
bx_import('BxDolStorage');
bx_import('BxDolImageTranscoder');

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
                'field_preview' => BxPersonsConfig::$FIELD_PICTURE_PREVIEW,
            ),
            BxPersonsConfig::$FIELD_COVER => array (
                'storage_object' => BxPersonsConfig::$OBJECT_STORAGE_COVER,
                'images_transcoder' => BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_COVER_THUMB,
                'field_preview' => BxPersonsConfig::$FIELD_COVER_PREVIEW,
            ),
        );
        $this->_aImageFields[BxPersonsConfig::$FIELD_PICTURE_PREVIEW] = $this->_aImageFields[BxPersonsConfig::$FIELD_PICTURE];
        $this->_aImageFields[BxPersonsConfig::$FIELD_COVER_PREVIEW] = $this->_aImageFields[BxPersonsConfig::$FIELD_COVER];

        $oAccountProfile = BxDolProfile::getInstanceAccountProfile();
        if ($oAccountProfile)
            $this->_iAccountProfileId = $oAccountProfile->id();

        $this->_oModule = BxDolModule::getInstance('bx_persons');
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())  {

        parent::initChecker($aValues, $aSpecificValues);

        foreach ($this->_aImageFields as $sField => $aVals) {
            if (!isset($this->aInputs[$sField]))
                continue;

            if ($aValues && !empty($aValues['id'])) 
                $this->aInputs[$sField]['content_id'] = $aValues['id'];

            $sErrorString = '';
            $this->aInputs[$sField]['file_id'] = $this->_processFile ($sField, isset($aValues[$sField]) ? $aValues[$sField] : 0, $sErrorString);
            if ($sErrorString) {
                $this->aInputs[$sField]['error'] = $sErrorString;
                $this->setValid(false);
            }

            if (!isset($this->aInputs[$aVals['field_preview']]) || !empty($this->aInputs[$aVals['field_preview']]['content']))
                continue;

            $oTranscoder = BxDolImageTranscoder::getObjectInstance($aVals['images_transcoder']);

            $aVars = array (
                'bx_if:picture' => array (
                    'condition' => $oTranscoder && isset($aValues[$sField]) && $aValues[$sField] ? true : false,
                    'content' => array (
                        'picture_url' => $oTranscoder && isset($aValues[$sField]) && $aValues[$sField] ? $oTranscoder->getImageUrl($aValues[$sField]) : '',
                    ),
                ),
                'bx_if:no_picture' => array (
                    'condition' => !$oTranscoder || !isset($aValues[$sField]) || !$aValues[$sField] ? true : false,
                    'content' => array (),
                ),
            );
            $this->aInputs[$aVals['field_preview']]['content'] = $this->_oModule->_oTemplate->parseHtmlByName('picture_preview.html', $aVars);
        }
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false) {
        $aValsToAdd = array_merge($aValsToAdd, array (
            BxPersonsConfig::$FIELD_AUTHOR => $this->_iAccountProfileId,
            BxPersonsConfig::$FIELD_ADDED => time(),
            BxPersonsConfig::$FIELD_CHANGED => time(),
        ));
        
        if (isset($this->aInputs[BxPersonsConfig::$FIELD_PICTURE])) {
            $aValsToAdd = array_merge($aValsToAdd, array (
                BxPersonsConfig::$FIELD_PICTURE => $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['file_id'],
            ));
        }

        if (isset($this->aInputs[BxPersonsConfig::$FIELD_COVER])) {
            $aValsToAdd = array_merge($aValsToAdd, array (
                BxPersonsConfig::$FIELD_COVER => $this->aInputs[BxPersonsConfig::$FIELD_COVER]['file_id'],
            ));
        }

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null) {        
        $aValsToAdd[BxPersonsConfig::$FIELD_CHANGED] = time();
        if (isset($this->aInputs[BxPersonsConfig::$FIELD_COVER]))
            $aValsToAdd[BxPersonsConfig::$FIELD_COVER] = $this->aInputs[BxPersonsConfig::$FIELD_COVER]['file_id'];
        if (isset($this->aInputs[BxPersonsConfig::$FIELD_PICTURE]))
            $aValsToAdd[BxPersonsConfig::$FIELD_PICTURE] = $this->aInputs[BxPersonsConfig::$FIELD_PICTURE]['file_id'];

        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }

    function delete ($iContentId, $aContentInfo = array()) {

        foreach ($this->_aImageFields as $sField => $aVals) {
            if (isset($aContentInfo[$sField]) && $aContentInfo[$sField])
                $htis->_deleteFile ($aContentInfo[$sField], $aVals['storage_object']);
        }

        return parent::delete($iContentId);
    }

    function _processFile ($sField, $iFileIdOld, &$sErrorString) {
        if (empty($_FILES[$sField]['tmp_name']))
            return $iFileIdOld;

        $oStorage = BxDolStorage::getObjectInstance($this->_aImageFields[$sField]['storage_object']);
        if (!$oStorage)
            return $iFileIdOld;

        // delete previous file
        $this->_deleteFile($iFileIdOld, $this->_aImageFields[$sField]['storage_object']);

        // process new file and return new file id
        if (!($iFileId = $oStorage->storeFileFromForm($_FILES[$sField], false, $this->_iAccountProfileId))) {
            $sErrorString = $oStorage->getErrorString();
            return 0;
        }

        return $iFileId;
    }

    function _deleteFile ($iFileId, $sStorageObject) {

        if (!$iFileId)
            return true;

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
