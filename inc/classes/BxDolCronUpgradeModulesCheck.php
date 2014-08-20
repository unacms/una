<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinUpgradeModules Dolphin Upgrade Modules Script
 * @{
 */

bx_import('BxDolCron');

class BxDolCronUpgradeModulesCheck extends BxDolCron
{
    public function processing()
    {
        if ('on' != getParam('sys_autoupdate_modules'))
            return;

        $o = bx_instance('BxDolUpgraderModules');
        if(!$o->prepare()) {
            sendMailTemplateSystem('t_UpgradeFailed', array (
                'error_msg' => $o->getError(),
            ));
            setParam('sys_autoupdate_modules', ''); // disable auto-update if it is failed
        }
        else
        	sendMailTemplateSystem('t_UpgradeModulesSuccess');
    }
}

/** @} */
