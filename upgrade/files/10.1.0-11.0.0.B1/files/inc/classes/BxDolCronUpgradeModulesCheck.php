<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCronUpgradeModulesCheck extends BxDolCron
{
    public function processing()
    {
        if('on' != getParam('sys_autoupdate'))
            return;

        BxDolStudioInstallerUtils::getInstance()->performModulesUpgrade(array(
            'directly' => true,
            'transient' => true,
            'autoupdate' => true
        ));
    }
}

/** @} */
