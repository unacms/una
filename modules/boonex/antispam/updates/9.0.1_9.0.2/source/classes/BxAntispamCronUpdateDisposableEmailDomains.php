<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Antispam Antispam
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAntispamCronUpdateDisposableEmailDomains extends BxDolCron
{
    function processing()
    {
        BxDolService::call('bx_antispam', 'update_disposable_domains_lists');
    }
}

/** @} */
