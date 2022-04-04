<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseFile Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Files upload form
 */
class BxBaseModFilesFormUpload extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_sGhostTemplate = 'form_ghost_template_upload.html';

        if (isset($this->aInputs['profile_id']))
            $this->aInputs['profile_id']['value'] = bx_get('profile_id');
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return $this->processFiles ($CNF['FIELD_PHOTO'], 0, true);
    }

    public function processFiles ($sFieldFile, $iContentId = 0, $isAssociateWithContent = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!isset($this->aInputs[$sFieldFile]))
            return true;

        $mixedFileIds = $this->getCleanValue($sFieldFile);
        if(!$mixedFileIds)
            return true;

        $oStorage = BxDolStorage::getObjectInstance($this->aInputs[$sFieldFile]['storage_object']);
        if (!$oStorage)
            return false;

        $iProfileId = $this->getContentOwnerProfileId($iContentId);

        $aGhostFiles = $oStorage->getGhosts ($iProfileId, $isAssociateWithContent ? 0 : $iContentId, true);
        if (!$aGhostFiles)
            return true;

        bx_import('BxDolPrivacy');

        // get values form main form to pass it to each file later
        $aFormValues = array();
        foreach ($this->aInputs as $aInput) {
            if(in_array($aInput['name'], array('do_submit', 'profile_id', $CNF['FIELD_PHOTO'])))
                continue;

            $aFormValues[$aInput['name']] = $this->getCleanValue($aInput['name']);
        }

        $aContentIds = array();
        foreach ($aGhostFiles as $aFile) {
            if (CHECK_ACTION_RESULT_ALLOWED !== $this->_oModule->checkAllowedAdd())
                continue;
            if (is_array($mixedFileIds) && !in_array($aFile['id'], $mixedFileIds))
                continue;
            $iContentId = 0;
            if ($isAssociateWithContent)
                $iContentId = BxBaseModGeneralFormEntry::insert (array_merge(array(
                    $CNF['FIELD_FOR_STORING_FILE_ID'] => $aFile['id'],
                    $CNF['FIELD_TITLE'] => $this->getCleanValue('title-' . $aFile['id']),
                    $CNF['FIELD_AUTHOR'] => bx_get('profile_id') && $this->_oModule->serviceIsAllowedAddContentToProfile(bx_get('profile_id')) ? bx_get('profile_id') : '',
                    $CNF['FIELD_STATUS_ADMIN'] => !$this->_oModule->_isModerator() && !$this->_oModule->_oConfig->isAutoApproveEnabled() ? BX_BASE_MOD_GENERAL_STATUS_PENDING : BX_BASE_MOD_TEXT_STATUS_ACTIVE
                ), $aFormValues));
            if (!$iContentId)
                continue;
            $aContentIds[] = $iContentId;
            
            if ($aFile['private'] || (isset($aFormValues[$CNF['FIELD_ALLOW_VIEW_TO']]) && BX_DOL_PG_ALL !== $aFormValues[$CNF['FIELD_ALLOW_VIEW_TO']]))
                $oStorage->setFilePrivate ($aFile['id'], true);
            if (!$iContentId)
                continue;

            $this->_associalFileWithContent($oStorage, $aFile['id'], $iProfileId, $iContentId, $sFieldFile);
            $this->_oModule->checkAllowedAdd(true);
        }

        return $aContentIds;
    }
}

/** @} */
