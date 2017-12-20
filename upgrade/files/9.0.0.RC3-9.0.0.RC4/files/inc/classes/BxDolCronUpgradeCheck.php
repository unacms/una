<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCronUpgradeCheck extends BxDolCron
{
    public function processing()
    {
        if ('on' != getParam('sys_autoupdate'))
            return;

        $o = bx_instance('BxDolUpgrader');
        if (!$o->prepare()) {
            sendMailTemplateSystem('t_UpgradeFailed', array (
                'error_msg' => $o->getError(),
            ));
            setParam('sys_autoupdate', ''); // disable auto-update if it is failed
        }
    }
}

/** @} */
