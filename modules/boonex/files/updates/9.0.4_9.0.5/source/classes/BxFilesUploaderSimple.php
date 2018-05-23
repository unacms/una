<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFilesUploaderSimple extends BxBaseModFilesUploaderSimple
{
    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
		$this->MODULE = 'bx_files';
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
    }
}

/** @} */
