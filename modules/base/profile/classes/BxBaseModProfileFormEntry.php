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

        if (isset($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]) && $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) {
            $sInfo = $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]['info'];
			$this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']] = $oPrivacy->getGroupChooser($CNF['OBJECT_PRIVACY_VIEW']);
            $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]['db']['pass'] = 'Xss';
            $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]['info'] = $sInfo;
		}

        if (!empty($CNF['FIELD_PICTURE']) && isset($this->aInputs[$CNF['FIELD_PICTURE']])) {
            $this->aInputs[$CNF['FIELD_PICTURE']]['storage_object'] = $CNF['OBJECT_STORAGE'];
            $this->aInputs[$CNF['FIELD_PICTURE']]['uploaders'] = !empty($this->aInputs[$CNF['FIELD_PICTURE']]['value']) ? unserialize($this->aInputs[$CNF['FIELD_PICTURE']]['value']) : $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_PICTURE']]['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_THUMB'];
            $this->aInputs[$CNF['FIELD_PICTURE']]['storage_private'] = 0;
            $this->aInputs[$CNF['FIELD_PICTURE']]['multiple'] = false;
            $this->aInputs[$CNF['FIELD_PICTURE']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_PICTURE']]['ghost_template'] = '';
        }

        if (!empty($CNF['FIELD_COVER'])) {
            $this->_aImageFields[$CNF['FIELD_COVER']] = array (
                'storage_object' => $CNF['OBJECT_STORAGE_COVER'],
                'images_transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_COVER_THUMB'],
                'field_preview' => $CNF['FIELD_COVER_PREVIEW'],
            );
        }

        if (!empty($CNF['FIELD_COVER_PREVIEW']))
            $this->_aImageFields[$CNF['FIELD_COVER_PREVIEW']] = $this->_aImageFields[$CNF['FIELD_COVER']];

        $oAccountProfile = BxDolProfile::getInstanceAccountProfile();
        if ($oAccountProfile)
            $this->_iAccountProfileId = $oAccountProfile->id();
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($this->aInputs[$CNF['FIELD_PICTURE']])) {

            $aContentInfo = false;
            if ($aValues && !empty($aValues[$CNF['FIELD_ID']])) {
                $aContentInfo = $this->_oModule->_oDb->getContentInfoById ($aValues[$CNF['FIELD_ID']]);
                $this->aInputs[$CNF['FIELD_PICTURE']]['content_id'] = $aValues[$CNF['FIELD_ID']];
            }

            $this->aInputs[$CNF['FIELD_PICTURE']]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName('form_ghost_template.html', $this->_getPhotoGhostTmplVars($aContentInfo));
        }

        parent::initChecker($aValues, $aSpecificValues);

        foreach ($this->_aImageFields as $sField => $aVals) {
            if (!isset($this->aInputs[$sField]))
                continue;

            if ($aValues && !empty($aValues[$CNF['FIELD_ID']]))
                $this->aInputs[$sField]['content_id'] = $aValues[$CNF['FIELD_ID']];

            $sErrorString = '';
            $this->aInputs[$sField]['file_id'] = $this->_processFile (!empty($aValues[$CNF['FIELD_ID']]) ? $aValues[$CNF['FIELD_ID']] : 0, $sField, isset($aValues[$sField]) ? $aValues[$sField] : 0, $sErrorString);
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
                'bx_if:delete' => array (
                    'condition' => $oTranscoder && isset($aValues[$sField]) && $aValues[$sField] && $sField == $CNF['FIELD_COVER'] ? true : false,
                    'content' => array ('action_ajax' => isset($aValues[$sField]) ? BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'delete_profile_img/' . $aValues[$sField] . '/' . (!empty($aValues[$CNF['FIELD_ID']]) ? $aValues[$CNF['FIELD_ID']] : 0) . '/' . $sField : ''),
                ),
            );
            $this->aInputs[$aVals['field_preview']]['content'] = $this->_oModule->_oTemplate->parseHtmlByName('picture_preview.html', $aVars);
        }
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!empty($this->aInputs[$CNF['FIELD_COVER']]) && !empty($this->aInputs[$CNF['FIELD_COVER']]['file_id'])) {
            $aValsToAdd = array_merge($aValsToAdd, array (
                $CNF['FIELD_COVER'] => $this->aInputs[$CNF['FIELD_COVER']]['file_id'],
            ));
        }

        if (isset($CNF['FIELD_PICTURE']))
            $aValsToAdd[$CNF['FIELD_PICTURE']] = 0; // we will update this field later, since we don't know content id yet
        
        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!empty($this->aInputs[$CNF['FIELD_COVER']]) && !empty($this->aInputs[$CNF['FIELD_COVER']]['file_id']))
            $aValsToAdd[$CNF['FIELD_COVER']] = $this->aInputs[$CNF['FIELD_COVER']]['file_id'];

        if (isset($CNF['FIELD_PICTURE']))
            $aValsToAdd[$CNF['FIELD_PICTURE']] = 0; // we will update this field later
        
        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }

    function delete ($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        foreach ($this->_aImageFields as $sField => $aVals) {
            if (isset($aContentInfo[$sField]) && $aContentInfo[$sField])
                $this->_deleteFile ($iContentId, $sField, $aContentInfo[$sField]);
        }

        if (isset($CNF['FIELD_PICTURE']) && isset($CNF['OBJECT_STORAGE']) && $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE'])) {
            $iProfileId = $this->getContentOwnerProfileId($iContentId);
            $aFiles = $oStorage->getGhosts($iProfileId, $iContentId);
            foreach ($aFiles as $aFile) {
                if (!$oStorage->getFile($aFile['id']))
                    continue;
                $bRet = $oStorage->deleteFile($aFile['id'], $this->_iAccountProfileId);
            }
        }

        return parent::delete($iContentId, $aContentInfo);
    }

    function _processFile ($iContentId, $sField, $iFileIdOld, &$sErrorString)
    {
        if (empty($_FILES[$sField]['tmp_name']))
            return $iFileIdOld;

        $oStorage = BxDolStorage::getObjectInstance($this->_aImageFields[$sField]['storage_object']);
        if (!$oStorage)
            return $iFileIdOld;

        // delete previous file
        $this->_deleteFile($iContentId, $sField, $iFileIdOld);

        // process new file and return new file id
        if (!($iFileId = $oStorage->storeFileFromForm($_FILES[$sField], false, $this->_iAccountProfileId))) {
            $sErrorString = $oStorage->getErrorString();
            return 0;
        }

        return $iFileId;
    }

    protected function _associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId)
    {
        $oStorage->updateGhostsContentId ($iFileId, $iProfileId, $iContentId);
        $this->_oModule->_oDb->updateContentPictureById($iContentId, 0/*$iProfileId*/, $iFileId, $this->_oModule->_oConfig->CNF['FIELD_PICTURE']);
    }
    
    function _deleteFile ($iContentId, $sFieldPicture, $iFileId, $bForceFieldUpdate = false)
    {
        if (!$iFileId)
            return true;

        if (!$this->_aImageFields[$sFieldPicture]['storage_object'])
            return false;

        if (!($oStorage = BxDolStorage::getObjectInstance($this->_aImageFields[$sFieldPicture]['storage_object'])))
            return false;

        if (!$oStorage->getFile($iFileId))
            return true;

        if (($bRet = $oStorage->deleteFile($iFileId, $this->_iAccountProfileId)) && $bForceFieldUpdate) {
            $this->_oModule->_oDb->updateContentPictureById($iContentId, 0, 0, $sFieldPicture);
        }

        return $bRet;
    }

    protected function _getPhotoGhostTmplVars($aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
			'name' => $this->aInputs[$CNF['FIELD_PICTURE']]['name'],
            'content_id' => $this->aInputs[$CNF['FIELD_PICTURE']]['content_id'],
			'bx_if:set_thumb' => array (
				'condition' => false,
				'content' => array (),
			),
		);
    }
}

/** @} */
