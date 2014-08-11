<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinUpgrade Dolphin Upgrade Script
 * @{
 */

bx_import('BxDolCron');

class BxDolCronUpgradeCheck extends BxDolCron
{
    public function processing()
    {
        if ('on' != getParam('sys_autoupdate_system'))
            return;

        $o = bx_instance('BxDolUpgrader');
        if (!$o->prepare())
            echo $o->getError() . "\n"; // TODO: email report ?
    }
}

/** @} */
