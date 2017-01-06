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
 * Files module
 */
class BxFilesModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function checkAllowedSetThumb ()
    {
        return _t('_sys_txt_access_denied');
    }
}

/** @} */
