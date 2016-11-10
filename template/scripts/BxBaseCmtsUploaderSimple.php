<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Upload files using standard HTML forms.
 * @see BxBaseUploaderSimple, BxDolUploader
 */
class BxBaseCmtsUploaderSimple extends BxTemplUploaderSimple
{
    function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
        $this->_sButtonTemplate = 'comments_uploader_bs.html';
    }
}

/** @} */
