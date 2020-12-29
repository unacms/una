<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxDolUploader
 */
class BxTemplUploaderRecordVideo extends BxBaseUploaderRecordVideo
{
    function __construct($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
    }
}

/** @} */
