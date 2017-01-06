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

class BxFilesGridAdministration extends BxBaseModTextGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_files';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
