<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaUpgrade UNA Upgrade Script
 * @{
 */

$aPathInfo = pathinfo(__FILE__);
$aPathInfo['dirname'] = str_replace("\\", "/", $aPathInfo['dirname']); // fix Windows path
define ('BX_UPGRADE_DIR_UPGRADES', $aPathInfo['dirname'] . '/files/');

require_once($aPathInfo['dirname'] . '/classes/BxDolUpgradeController.php');
require_once($aPathInfo['dirname'] . '/classes/BxDolUpgradeUtil.php');
require_once($aPathInfo['dirname'] . '/classes/BxDolUpgradeDb.php');

bx_import('BxDolCron');
bx_import('BxDolLanguages');
bx_import('BxDolEmailTemplates');

class BxDolUpgradeCron extends BxDolCron
{
    public function processing()
    {
        $oController = new BxDolUpgradeController();
        if ($oController->setMaintenanceMode(true)) {
    
            // if upgrade was successful
            if (($sFolder = $oController->getAvailableUpgrade()) && $oController->runUpgrade($sFolder)) { 

                setParam('sys_revision', getParam('sys_revision') + 1);

                // write upgrade log
                $oController->writeLog();

                // send email notification
                bx_import('BxDolLanguages');
                sendMailTemplateSystem('t_UpgradeSuccess', array (
                    'new_version' => bx_get_ver(true),
                    'conclusion' => $oController->getConclusion() ? _t('_sys_upgrade_conclusion', $oController->getConclusion()) : '',
                ));

                // if next upgrade is available (in case of bulk upgrade) then schedule to run it upon next cron run
                $sUpgradeDir = pathinfo(__FILE__, PATHINFO_DIRNAME);
                if ($oController->getAvailableUpgrade()) {

                    $oUpgrader = bx_instance('BxDolUpgrader');
                    $oUpgrader->setTransientUpgradeCronJob($sUpgradeDir);

                } elseif (0 === strpos($sUpgradeDir, BX_DIRECTORY_PATH_TMP)) {

                    @bx_rrmdir($sUpgradeDir);

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
            setParam('sys_autoupdate', '');
        } 
        else { // run modules update after successful system upgrade

            bx_import('BxDolCronQuery');
            BxDolCronQuery::getInstance()->addTransientJobClass('sys_perform_upgrade_modules', 'BxDolCronUpgradeModulesCheck', 'inc/classes/BxDolCronUpgradeModulesCheck.php');
        }
    }
}

/** @} */
