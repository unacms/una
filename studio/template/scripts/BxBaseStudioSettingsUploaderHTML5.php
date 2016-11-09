<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
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
        if(!$oTemplate)
            $oTemplate = BxDolStudioTemplate::getInstance();

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
