<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Antispam Antispam
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolCron');

class BxAntispamCronPruning extends BxDolCron
{
    function processing()
    {
        BxDolService::call('bx_antispam', 'pruning');
    }
}

/** @} */
