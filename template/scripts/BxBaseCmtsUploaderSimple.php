<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Upload files using standard HTML forms.
 * @see BxBaseUploaderSimple, BxDolUploader
 */
class BxBaseCmtsUploaderSimple extends BxTemplUploaderSimple
{
    function __construct ($aObject, $sStorageObject, $sUniqId)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId);
        $this->_sButtonTemplate = 'comments_uploader_bs.html';
    }
}

/** @} */
