<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Files upload form
 */
class BxFilesFormUpload extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_files';
        parent::__construct($aInfo, $oTemplate);

        $this->_sGhostTemplate = 'form_ghost_template_upload.html';
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
            if ($aInput['name'] && isset($aInput['value']) && $aInput['value'] && !is_array($aInput['value']) && $aInput['name'] != 'do_submit' && $aInput['name'] != $CNF['FIELD_PHOTO'])
                $aFormValues[$aInput['name']] = $aInput['value'];//$this->getCleanValue($aInput['name']);
        }

        $aContentIds = array();
        foreach ($aGhostFiles as $aFile) {
            if (is_array($mixedFileIds) && !in_array($aFile['id'], $mixedFileIds))
                continue;

            $iContentId = 0;
            if ($isAssociateWithContent)
                $iContentId = BxBaseModGeneralFormEntry::insert (array_merge(array($CNF['FIELD_TITLE'] => $this->getCleanValue('title-' . $aFile['id'])), $aFormValues));
            if (!$iContentId)
                continue;
            $aContentIds[] = $iContentId;
            
            if ($aFile['private'] || (isset($aFormValues[$CNF['FIELD_ALLOW_VIEW_TO']]) && BX_DOL_PG_ALL !== $aFormValues[$CNF['FIELD_ALLOW_VIEW_TO']]))
                $oStorage->setFilePrivate ($aFile['id'], true);
            if ($iContentId)
                $this->_associalFileWithContent($oStorage, $aFile['id'], $iProfileId, $iContentId, $sFieldFile);
        }

        return $aContentIds;
    }
}

/** @} */
