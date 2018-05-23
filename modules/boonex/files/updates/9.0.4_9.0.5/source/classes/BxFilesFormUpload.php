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

/**
 * Files upload form
 */
class BxFilesFormUpload extends BxBaseModFilesFormUpload
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_files';
        parent::__construct($aInfo, $oTemplate);
    }    
}

/** @} */
