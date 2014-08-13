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
    
            // if upgrade was successful
            if (($sFolder = $oController->getAvailableUpgrade()) && $oController->runUpgrade($sFolder)) { 

                // write upgrade log
                $oController->writeLog();

                // send email notification
                bx_import('BxDolLanguages');
                sendMailTemplateSystem('t_UpgradeSuccess', array (
                    'new_version' => bx_get_ver(true),
                    'conclusion' => $oController->getConclusion() ? _t('_sys_upgrade_conclusion', $oController->getConclusion()) : '',
                ));

                // if next upgrade (in case of bulk upgrade) is available then schedule to run it ASAP, upon next cron run
                if ($oController->getAvailableUpgrade()) { 
                    bx_import('BxDolUpgrader');
                    $oUpgrader = bx_instance('BxDolUpgrader');
                    $oUpgrader->setTransientUpgradeCronJob(pathinfo(__FILE__, PATHINFO_DIRNAME));
                }
            }

            $oController->setMaintenanceMode(false);
        }

        // if something went grong during upgrade
        if ($sErrorMsg = $oController->getErrorMsg()) { 

            // write upgrade log
            $oController->writeLog();

            // send email notification
            sendMailTemplateSystem('t_UpgradeFailed', array (
                'error_msg' => $sErrorMsg,
            ));

            // disable auto-upgrade if it is failed
            setParam('sys_autoupdate_system', '');
        }
    }
}

/** @} */
