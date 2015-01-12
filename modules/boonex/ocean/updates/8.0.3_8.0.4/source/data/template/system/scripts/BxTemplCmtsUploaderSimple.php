<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import ('BxBaseCmtsUploaderSimple');

/**
 * @see BxDolUploader
 */
class BxTemplCmtsUploaderSimple extends BxBaseCmtsUploaderSimple
{
    function __construct($aObject, $sStorageObject, $sUniqId)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId);
    }
}

/** @} */
