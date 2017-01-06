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
 * 'Files manage tools' menu.
 */
class BxFilesMenuManageTools extends BxBaseModTextMenuManageTools
{

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_files';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
