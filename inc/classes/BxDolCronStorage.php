<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolCronStorage extends BxDolCron
{
    public function processing()
    {
        set_time_limit(36000);
        ignore_user_abort();

        if (BxDolStorage::pruneDeletions()) // if any files were deleted
            BxDolInstallerUtils::checkModulesPendingUninstall(); // try to uninstall modules pending for uninstall
    }
}

/** @} */
