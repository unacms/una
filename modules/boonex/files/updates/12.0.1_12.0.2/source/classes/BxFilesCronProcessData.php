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

class BxFilesCronProcessData extends BxDolCron
{
    function processing()
    {
        BxDolService::call('bx_files', 'process_files_data', array(3));
        BxDolService::call('bx_files', 'prune_downloading_jobs', array());
    }
}

/** @} */
