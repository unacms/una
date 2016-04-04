<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

/**
 * Upload files using AJAX uploader with multiple files selection support (without flash),
 * it works in Firefox and WebKit(Safari, Chrome) browsers only, but has fallback for other browsers (IE, Opera).
 * @see BxDolUploader
 */
class BxBaseStudioSettingsUploaderHTML5 extends BxTemplUploaderHTML5
{
    function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
    }

    public function getGhosts($iProfileId, $sFormat, $sImagesTranscoder = false, $iContentId = false)
    {
    	return parent::getGhosts(0, $sFormat, $sImagesTranscoder, $iContentId);
    }

    public function handleUploads ($iProfileId, $mixedFiles, $isMultiple = true, $iContentId = false, $bPrivate = true)
    {
    	parent::handleUploads(0, $mixedFiles, $isMultiple, $iContentId, $bPrivate);
    }

    public function deleteGhost($iFileId, $iProfileId)
    {
    	return parent::deleteGhost($iFileId, 0);
    }
}

/** @} */
