<?php
$aParams = array('per_page' => 'bx_market_per_page_profile');
var_dump(serialize($aParams)); exit;

define('BX_DOL', 1);

define('BX_DOL_URL_ROOT', 'http://trident.me/'); ///< site url
define('BX_DIRECTORY_PATH_ROOT', 'Z:/home/trident.me/www/'); ///< site path

define('BX_DATABASE_HOST', 'localhost'); ///< db host
define('BX_DATABASE_SOCK', ''); ///< db socket
define('BX_DATABASE_PORT', ''); ///< db port
define('BX_DATABASE_USER', 'root'); ///< db user
define('BX_DATABASE_PASS', ''); ///< db password
define('BX_DATABASE_NAME', 'boonex_trident'); ///< db name

define('BX_DB_FULL_VISUAL_PROCESSING', true); ///< upon db error - show error message
define('BX_DB_FULL_DEBUG_MODE', true); ///< upon db error - show detailed report (turn off in production mode)
define('BX_DB_DO_EMAIL_ERROR_REPORT', true); ///< upon db error - send email with detailed report

require_once (BX_DIRECTORY_PATH_ROOT . 'inc/utils.inc.php');
require_once (BX_DIRECTORY_PATH_ROOT . 'inc/classes/BxDol.php');
require_once (BX_DIRECTORY_PATH_ROOT . 'inc/classes/BxDolDb.php');

$oDb = BxDolDb::getInstance();

/*
$oStmt = $oDb->prepare("SELECT `title` FROM `sys_modules` WHERE `name`=?", "system");
$mixedResult = $oDb->getOne($oStmt);
*/

/*
$oStmt = $oDb->prepare("SELECT * FROM `sys_modules` WHERE `name`=?", "system");
$mixedResult = $oDb->getRow($oStmt);
*/

/*
$oStmt = $oDb->prepare("SELECT * FROM `sys_modules` WHERE `enabled`=?", 1);
$mixedResult = $oDb->getColumn($oStmt, 3);
*/

/*
$oStmt = $oDb->prepare("SELECT * FROM `sys_modules` WHERE `type`=? AND `enabled`=?", "module", 1);

$mixedResult[] = $oDb->getFirstRow($oStmt);
while($aRow = $oDb->getNextRow($oStmt)) {
	$mixedResult[] = $aRow;
}
*/

/*
$oStmt = "SELECT * FROM `sys_modules` WHERE `type`=:type AND `enabled`=:enabled";

$mixedResult[] = $oDb->getFirstRow($oStmt, array('type' => 'module', 'enabled' => 1));
while($aRow = $oDb->getNextRow($oStmt)) {
	$mixedResult[] = $aRow;
}
*/

/*
$oStmt = $oDb->prepare("SELECT * FROM `sys_modules` WHERE `type`=? AND `enabled`=?", "module", 1);
$mixedResult = $oDb->getRow($oStmt);
$mixedResult = $oDb->getAffectedRows($oStmt);
*/

/*
$oStmt = $oDb->prepare("UPDATE `sys_modules` SET `pending_uninstall`='0' WHERE `pending_uninstall`='0'");
$mixedResult = $oDb->query($oStmt);
*/

/*
$oStmt = $oDb->prepare("SELECT * FROM `sys_modules` WHERE `type`=? AND `enabled`=?", "language", 1);
$mixedResult = $oDb->getAll($oStmt);
*/

/*
$oStmt = $oDb->pdoQuery("SELECT * FROM `sys_modules` WHERE `type`='template' AND `enabled`=1");
$mixedResult = $oDb->fillArray($oStmt);
*/

/*
$oStmt = $oDb->prepare("SELECT * FROM `sys_modules` WHERE `type`=? AND `enabled`=?", "language", 1);
$mixedResult = $oDb->getAllWithKey($oStmt, 'name');
*/

/*
$oStmt = $oDb->prepare("SELECT * FROM `sys_modules` WHERE `type`=? AND `enabled`=?", "language", 1);
$mixedResult = $oDb->getPairs($oStmt, 'name', 'title');
*/

/*
$oDb->pdoExec("INSERT INTO `sys_modules` SET `name`='ttt', `path`='ttt', `uri`='ttt', `class_prefix`='ttt', `db_prefix`='ttt'");
$mixedResult = $oDb->lastId();
*/

/*
$mixedResult = $oDb->listTables();
*/

/*
$mixedResult = $oDb->getFields('sys_modules');
$mixedResult = $oDb->isFieldExists('sys_modules', 'dependencies');
*/

/*
$mixedResult = $oDb->getEncoding();
*/

/*
$mixedResult = $oDb->escape("It's test!!!");
$mixedResult = $oDb->implode_escape(array("It's test!", "Test", "I'm testing!"));
$mixedResult = $oDb->arrayToSQL(array("title" => "Test", "message" => "It's test!", "content" => "I'm testing!"));
*/

$mixedResult = $oDb->setTimezone('UTC');

var_dump($mixedResult); 