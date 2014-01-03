<?php 
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinInstall Dolphin Install
 * @{
 */

define ('BX_DOL', 1);
define ('BX_DOL_INSTALL', 1);

define ('BX_DOL_VER', '8.0.0');

define ('BX_INSTALL_DEFAULT_LANGUAGE', 'en');

define ('BX_INSTALL_URL_ROOT', '../');
define ('BX_INSTALL_URL_MODULES', '../modules/');

define ('BX_INSTALL_DIR_ROOT', '../');
define ('BX_INSTALL_DIR_MODULES', '../modules/');
define ('BX_INSTALL_DIR_PLUGINS', '../plugins/');
define ('BX_INSTALL_DIR_TEMPLATES', './templates/');
define ('BX_INSTALL_DIR_CLASSES', './classes/');

define ('BX_INSTALL_PATH_HEADER', '../inc/header_test.inc.php');

if (!defined('BX_DOL_MODULE_TYPE_MODULE')) {
    define('BX_DOL_MODULE_TYPE_MODULE', 'module');
    define('BX_DOL_MODULE_TYPE_LANGUAGE', 'language');
    define('BX_DOL_MODULE_TYPE_TEMPLATE', 'template');
}

require_once('../inc/utils.inc.php');
require_once('../inc/classes/BxDol.php');
require_once('../inc/classes/BxDolIO.php');
require_once('../inc/classes/BxDolDb.php');
require_once('../inc/classes/BxDolXmlParser.php');
require_once('../template/scripts/BxBaseConfig.php');
require_once('../studio/classes/BxDolStudioTools.php');
require_once('../studio/classes/BxDolStudioToolsAudit.php');

require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallController.php');
require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallView.php');
require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallLang.php');
require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallSiteConfig.php');

$oController = new BxDolInstallController ();
$oController->run(isset($_REQUEST['action']) ? $_REQUEST['action'] : '');

/** @} */
