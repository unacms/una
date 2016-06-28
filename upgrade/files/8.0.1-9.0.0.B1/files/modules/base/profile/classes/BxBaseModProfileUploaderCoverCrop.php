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

class BxBaseModProfileUploaderCoverCrop extends BxTemplUploaderCrop
{
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate ? $oTemplate : $this->_oModule->_oTemplate);
        $this->_sUploaderFormTemplate = 'uploader_form_crop_cover.html';
    }

    public function deleteGhostsForProfile($iProfileId, $iContentId = false)
    {
        if (!$iContentId)
            return parent::deleteGhostsForProfile($iProfileId, $iContentId);

        $CNF = $this->_oModule->_oConfig->CNF;
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        
        $iCount = 0;

        $oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);

        $aGhosts = $oStorage->getGhosts($iProfileId, $iContentId, $iContentId ? true : false);
        foreach ($aGhosts as $aFile) {

            // for cover image delete only unassigned ghosts and currently set covers
            if ($aFile['id'] == $aContentInfo[$CNF['FIELD_COVER']] || $aFile['id'] != $aContentInfo[$CNF['FIELD_PICTURE']])
                $iCount += $oStorage->deleteFile($aFile['id']);

        }

        return $iCount;
    }
    
    public function getGhosts($iProfileId, $sFormat, $sImagesTranscoder = false, $iContentId = false)
    {
        $s = parent::getGhosts($iProfileId, $sFormat, $sImagesTranscoder, $iContentId);
        if (!$s || !$iContentId) // if we're creating new profile return all ghosts
            return $s;

        $a = json_decode($s, true);
        if (!$a)
            return $s;

        // filter out thumbnails
        $CNF = $this->_oModule->_oConfig->CNF;
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        $aResult = array();
        foreach ($a as $aFile) {

            // for cover image show only unassigned ghosts and currently set covers
            if ($aFile['file_id'] == $aContentInfo[$CNF['FIELD_COVER']] || $aFile['file_id'] != $aContentInfo[$CNF['FIELD_PICTURE']])
                $aResult[] = $aFile;

        }

        return json_encode($aResult);
    }    
}

/** @} */
