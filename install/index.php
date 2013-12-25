<?php 
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinInstall Dolphin Install
 * @{
 */

define('BX_DOL', 1);

define ('BX_INSTALL_DIR_PLUGINS', '../plugins/');
define ('BX_INSTALL_DIR_TEMPLATES', './templates/');
define ('BX_INSTALL_DIR_CLASSES', './classes/');

require_once('../inc/utils.inc.php');
require_once('../inc/classes/BxDol.php');
require_once('../template/scripts/BxBaseConfig.php');
require_once('../studio/classes/BxDolStudioTools.php');
require_once('../studio/classes/BxDolStudioToolsAudit.php');

require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallController.php');
require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallView.php');

$oController = new BxDolInstallController ();
$oController->run(isset($_REQUEST['action']) ? $_REQUEST['action'] : '');

/** @} */
