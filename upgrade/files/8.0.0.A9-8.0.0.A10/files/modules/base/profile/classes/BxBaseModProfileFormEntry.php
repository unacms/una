<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModGeneralFormEntry');
bx_import('BxDolTranscoderImage');

/**
 * Create/edit profile form.
 */
class BxBaseModProfileFormEntry extends BxBaseModGeneralFormEntry
{
    protected $_iAccountProfileId = 0;
    protected $_aImageFields = array ();

    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!empty($CNF['FIELD_PICTURE'])) {
            $this->_aImageFields[$CNF['FIELD_PICTURE']] = array (
                'storage_object' => $CNF['OBJECT_STORAGE'],
                'images_transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_THUMB'],
                'field_preview' => $CNF['FIELD_PICTURE_PREVIEW'],
            );
        }

        if (!empty($CNF['FIELD_COVER'])) {
            $this->_aImageFields[$CNF['FIELD_COVER']] = array (
                'storage_object' => $CNF['OBJECT_STORAGE_COVER'],
                'images_transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_COVER_THUMB'],
                'field_preview' => $CNF['FIELD_COVER_PREVIEW'],
            );
        }

        if (!empty($CNF['FIELD_PICTURE_PREVIEW']))
            $this->_aImageFields[$CNF['FIELD_PICTURE_PREVIEW']] = $this->_aImageFields[$CNF['FIELD_PICTURE']];

        if (!empty($CNF['FIELD_COVER_PREVIEW']))
            $this->_aImageFields[$CNF['FIELD_COVER_PREVIEW']] = $this->_aImageFields[$CNF['FIELD_COVER']];

        $oAccountProfile = BxDolProfile::getInstanceAccountProfile();
        if ($oAccountProfile)
            $this->_iAccountProfileId = $oAccountProfile->id();
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        parent::initChecker($aValues, $aSpecificValues);

        $CNF = &$this->_oModule->_oConfig->CNF;

        foreach ($this->_aImageFields as $sField => $aVals) {
            if (!isset($this->aInputs[$sField]))
                continue;

            if ($aValues && !empty($aValues[$CNF['FIELD_ID']]))
                $this->aInputs[$sField]['content_id'] = $aValues[$CNF['FIELD_ID']];

            $sErrorString = '';
            $this->aInputs[$sField]['file_id'] = $this->_processFile ($sField, isset($aValues[$sField]) ? $aValues[$sField] : 0, $sErrorString);
            if ($sErrorString) {
                $this->aInputs[$sField]['error'] = $sErrorString;
                $this->setValid(false);
            }

            if (!isset($this->aInputs[$aVals['field_preview']]) || !empty($this->aInputs[$aVals['field_preview']]['content']))
                continue;

            $oTranscoder = BxDolTranscoderImage::getObjectInstance($aVals['images_transcoder']);

            $aVars = array (
                'bx_if:picture' => array (
                    'condition' => $oTranscoder && isset($aValues[$sField]) && $aValues[$sField] ? true : false,
                    'content' => array (
                        'picture_url' => $oTranscoder && isset($aValues[$sField]) && $aValues[$sField] ? $oTranscoder->getFileUrl($aValues[$sField]) : '',
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

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!empty($this->aInputs[$CNF['FIELD_PICTURE']])) {
            $aValsToAdd = array_merge($aValsToAdd, array (
                $CNF['FIELD_PICTURE'] => $this->aInputs[$CNF['FIELD_PICTURE']]['file_id'],
            ));
        }

        if (!empty($this->aInputs[$CNF['FIELD_COVER']])) {
            $aValsToAdd = array_merge($aValsToAdd, array (
                $CNF['FIELD_COVER'] => $this->aInputs[$CNF['FIELD_COVER']]['file_id'],
            ));
        }

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!empty($this->aInputs[$CNF['FIELD_COVER']]))
            $aValsToAdd[$CNF['FIELD_COVER']] = $this->aInputs[$CNF['FIELD_COVER']]['file_id'];

        if (!empty($this->aInputs[$CNF['FIELD_PICTURE']]))
            $aValsToAdd[$CNF['FIELD_PICTURE']] = $this->aInputs[$CNF['FIELD_PICTURE']]['file_id'];

        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }

    function delete ($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        foreach ($this->_aImageFields as $sField => $aVals) {
            if (isset($aContentInfo[$sField]) && $aContentInfo[$sField])
                $this->_deleteFile ($aContentInfo[$sField], $aVals['storage_object']);
        }

        return parent::delete($iContentId, $aContentInfo);
    }

    function _processFile ($sField, $iFileIdOld, &$sErrorString)
    {
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

    function _deleteFile ($iFileId, $sStorageObject)
    {
        if (!$iFileId)
            return true;

        if (!($oStorage = BxDolStorage::getObjectInstance($sStorageObject)))
            return false;

        if (!$oStorage->getFile($iFileId))
            return true;

        return $oStorage->deleteFile($iFileId, $this->_iAccountProfileId);
    }

}

/** @} */
