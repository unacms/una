<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentInstall Trident Install
 * @{
 */

define ('BX_DOL_INSTALL',  true);
define ('BX_SKIP_INSTALL_CHECK', true);

define ('BX_DOL_VER', '9.0.0-DEV2');

define ('BX_INSTALL_DEFAULT_LANGUAGE', 'en');

define ('BX_INSTALL_URL_ROOT', '../');
define ('BX_INSTALL_URL_MODULES', BX_INSTALL_URL_ROOT . 'modules/');

$aPathInfo = pathinfo(__FILE__);
define ('BX_INSTALL_DIR', $aPathInfo['dirname'] . '/');
define ('BX_INSTALL_DIR_ROOT', BX_INSTALL_DIR . '../');
define ('BX_INSTALL_DIR_MODULES', BX_INSTALL_DIR_ROOT . 'modules/');
define ('BX_INSTALL_DIR_PLUGINS', BX_INSTALL_DIR_ROOT . 'plugins/');
define ('BX_INSTALL_DIR_TEMPLATES', BX_INSTALL_DIR . 'templates/');
define ('BX_INSTALL_DIR_CLASSES', BX_INSTALL_DIR . 'classes/');

define ('BX_INSTALL_PATH_HEADER', BX_INSTALL_DIR_ROOT . 'inc/header.inc.php');

if (!defined('BX_DOL_MODULE_TYPE_MODULE')) {
    define('BX_DOL_MODULE_TYPE_MODULE', 'module');
    define('BX_DOL_MODULE_TYPE_LANGUAGE', 'language');
    define('BX_DOL_MODULE_TYPE_TEMPLATE', 'template');
}

require_once(BX_INSTALL_DIR_ROOT . 'inc/utils.inc.php');
require_once(BX_INSTALL_DIR_ROOT . 'inc/classes/BxDol.php');
require_once(BX_INSTALL_DIR_ROOT . 'inc/classes/BxDolIO.php');
require_once(BX_INSTALL_DIR_ROOT . 'inc/classes/BxDolDb.php');
require_once(BX_INSTALL_DIR_ROOT . 'inc/classes/BxDolXmlParser.php');
require_once(BX_INSTALL_DIR_ROOT . 'template/scripts/BxBaseConfig.php');
require_once(BX_INSTALL_DIR_ROOT . 'studio/classes/BxDolStudioTools.php');
require_once(BX_INSTALL_DIR_ROOT . 'studio/classes/BxDolStudioToolsAudit.php');

require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallController.php');
require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallView.php');
require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallModulesTools.php');
require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallLang.php');
require_once(BX_INSTALL_DIR_CLASSES . 'BxDolInstallSiteConfig.php');

$oController = new BxDolInstallController ();
$oController->run(isset($_REQUEST['action']) ? $_REQUEST['action'] : '');

/** @} */
