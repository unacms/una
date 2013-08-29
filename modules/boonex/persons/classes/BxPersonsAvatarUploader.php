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

bx_import('BxTemplUploaderSimple');

/**
 * Person avatar uploader
 */
class BxPersonsAvatarUploader extends BxTemplUploaderSimple {

    protected $_iAccountProfileId;

    public function __construct($aObject, $sStorageObject, $sUniqId) {
        parent::__construct($aObject, $sStorageObject, $sUniqId);

        bx_import('BxDolProfile');
        $oAccountProfile = BxDolProfile::getInstanceAccountProfile();
        $this->_iAccountProfileId = $oAccountProfile->id();
    }

    public function getGhosts($iProfileId, $sFormat, $sImagesTranscoder = false, $iContentId = false) {
        return parent::getGhosts($this->_iAccountProfileId, $sFormat, $sImagesTranscoder, $iContentId);
    }

    public function deleteGhost($iFileId, $iProfileId) {
        return parent::deleteGhost($iFileId, $this->_iAccountProfileId);
    }

    public function handleUploads ($iProfileId, $mixedFiles, $isMultiple = true, $iContentId = false) {
        parent::handleUploads ($this->_iAccountProfileId, $mixedFiles, $isMultiple, $iContentId);
    }

}

/** @} */
