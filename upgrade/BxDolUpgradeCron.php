<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

require_once(BX_DIRECTORY_PATH_ROOT . 'upgrade/classes/BxDolUpgradeController.php');
require_once(BX_DIRECTORY_PATH_ROOT . 'upgrade/classes/BxDolUpgradeUtil.php');
require_once(BX_DIRECTORY_PATH_ROOT . 'upgrade/classes/BxDolUpgradeDb.php');

$aPathInfo = pathinfo(__FILE__);
define ('BX_UPGRADE_DIR_UPGRADES', $aPathInfo['dirname'] . '/files/');

bx_import('BxDolCron');

class BxDolUpgradeCron extends BxDolCron
{
    public function processing()
    {
        $oController = new BxDolUpgradeController();

        $aFolders = $oController->getAllUpgrades();
        $j = count($aFolders);

        for ($i = 0; $i < $j; ++$i) {
            $sFolder = $oController->getAvailableUpgrade();
            if (!$sFolder)
                continue;

            if (!$oController->runUpgrade($sFolder))
                echo $oController->getErrorMsg() . "\n"; // TODO: email report ?
        }
    }
}

/** @} */
