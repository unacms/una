<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxBaseUploaderHTML5, BxDolUploader
 */
class BxBaseCmtsUploaderHTML5 extends BxTemplUploaderHTML5
{
    function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
        $this->_sButtonTemplate = 'comments_uploader_bs.html';
    }
}

/** @} */
