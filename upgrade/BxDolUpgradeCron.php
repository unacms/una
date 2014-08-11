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
        if ($oController->setMaintenanceMode(true)) {
        
            $aFolders = $oController->getAllUpgrades();
            $j = count($aFolders);

            for ($i = 0; $i < $j; ++$i) {
                $sFolder = $oController->getAvailableUpgrade();
                if (!$sFolder)
                    continue;

                if (!$oController->runUpgrade($sFolder))
                    break;

                $oController->writeLog();

                sendMailTemplateSystem('t_UpgradeSuccess', array (
                    'new_version' => bx_get_ver(true),
                ));
            }

            $oController->setMaintenanceMode(false);
        }

        if ($sErrorMsg = $oController->getErrorMsg()) {
            $oController->writeLog();
            sendMailTemplateSystem('t_UpgradeFailed', array (
                'error_msg' => $sErrorMsg,
            ));
            setParam('sys_autoupdate_system', ''); // disable auto-update if it is failed
        }
    }
}

/** @} */
