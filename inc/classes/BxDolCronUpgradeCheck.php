<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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
        if (!$o->prepare()) {
            sendMailTemplateSystem('t_UpgradeFailed', array (
                'error_msg' => $o->getError(),
            ));
            setParam('sys_autoupdate_system', ''); // disable auto-update if it is failed
        }
    }
}

/** @} */
