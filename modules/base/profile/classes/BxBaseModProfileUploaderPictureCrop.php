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

class BxBaseModProfileUploaderPictureCrop extends BxTemplUploaderCrop
{
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
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

            // for profile image delete only unassigned ghosts and currently set profile pictures
            if ($aFile['id'] == $aContentInfo[$CNF['FIELD_PICTURE']] || $aFile['id'] != $aContentInfo[$CNF['FIELD_COVER']])
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

            // for profile pictures show only unassigned ghosts and currently set profile pictures
            if ($sImagesTranscoder == $CNF['OBJECT_IMAGES_TRANSCODER_THUMB'] && ($aFile['file_id'] == $aContentInfo[$CNF['FIELD_PICTURE']] || $aFile['file_id'] != $aContentInfo[$CNF['FIELD_COVER']]))
                $aResult[] = $aFile;            
        }

        return json_encode($aResult);
    }    
}

/** @} */
