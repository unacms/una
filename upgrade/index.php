<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinUpgrade Dolphin Upgrade Script
 * @{
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_ROOT . 'upgrade/classes/BxDolUpgradeController.php');
require_once(BX_DIRECTORY_PATH_ROOT . 'upgrade/classes/BxDolUpgradeUtil.php');
require_once(BX_DIRECTORY_PATH_ROOT . 'upgrade/classes/BxDolUpgradeDb.php');

define ('BX_UPGRADE_DIR_UPGRADES', BX_DIRECTORY_PATH_ROOT . 'upgrade/files/');
define ('BX_UPGRADE_DIR_TEMPLATES', BX_DIRECTORY_PATH_ROOT . 'upgrade/templates/');

$sFolder = $_REQUEST['folder'];

include (BX_UPGRADE_DIR_TEMPLATES . '_header.php');

$oController = new BxDolUpgradeController ();

if (!$sFolder)
    $oController->showAvailableUpgrades();
else
    $oController->runUpgrade($sFolder);

include (BX_UPGRADE_DIR_TEMPLATES . '_footer.php');

/** @} */
