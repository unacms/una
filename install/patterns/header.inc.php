<?php

define('BX_DOL', 1);

define('BX_DOL_START', microtime ());

//--- Main URLs ---//
define('BX_DOL_URL_ROOT', '%SITE_URL%');
define('BX_DOL_URL_PLUGINS', BX_DOL_URL_ROOT . 'plugins/');
define('BX_DOL_URL_PLUGINS_PUBLIC', BX_DOL_URL_ROOT . 'plugins_public/');
define('BX_DOL_URL_MODULES', BX_DOL_URL_ROOT . 'modules/');
define('BX_DOL_URL_CACHE_PUBLIC', BX_DOL_URL_ROOT . 'cache_public/');
define('BX_DOL_URL_BASE', BX_DOL_URL_ROOT . 'template/');

//--- Main Pathes ---//
define('BX_DIRECTORY_PATH_ROOT', '%ROOT_DIR%');
define('BX_DIRECTORY_PATH_INC', BX_DIRECTORY_PATH_ROOT . 'inc/');
define('BX_DIRECTORY_PATH_BASE', BX_DIRECTORY_PATH_ROOT . 'template/');
define('BX_DIRECTORY_PATH_CACHE', BX_DIRECTORY_PATH_ROOT . 'cache/');
define('BX_DIRECTORY_PATH_CACHE_PUBLIC', BX_DIRECTORY_PATH_ROOT . 'cache_public/');
define('BX_DIRECTORY_PATH_CLASSES', BX_DIRECTORY_PATH_ROOT . 'inc/classes/');
define('BX_DIRECTORY_PATH_PLUGINS', BX_DIRECTORY_PATH_ROOT . 'plugins/');
define('BX_DIRECTORY_PATH_PLUGINS_PUBLIC', BX_DIRECTORY_PATH_ROOT . 'plugins_public/');
define('BX_DIRECTORY_PATH_MODULES', BX_DIRECTORY_PATH_ROOT . 'modules/');
define('BX_DIRECTORY_PATH_TMP', BX_DIRECTORY_PATH_ROOT . 'tmp/');
define('BX_DIRECTORY_STORAGE', BX_DIRECTORY_PATH_ROOT . 'storage/');

//--- DB Connection ---//
define('BX_DATABASE_HOST', '%DB_HOST%');
define('BX_DATABASE_SOCK', '%DB_SOCK%');
define('BX_DATABASE_PORT', '%DB_PORT%');
define('BX_DATABASE_USER', '%DB_USER%');
define('BX_DATABASE_PASS', '%DB_PASSWORD%');
define('BX_DATABASE_NAME', '%DB_NAME%');

//--- System settings ---//
define('BX_SYSTEM_CONVERT', '%CONVERT_PATH%');
define('BX_SYSTEM_COMPOSITE', '%COMPOSITE_PATH%');

define('BX_DOL_DIR_RIGHTS', 0777);
define('BX_DOL_FILE_RIGHTS', 0666);

define('BX_DOL_STORAGE_OBJ_IMAGES', 'sys_images');

define('BX_DOL_INT_MAX', 2147483647);

define('BX_DOL_TRANSCODER_OBJ_ICON_APPLE', 'sys_icon_apple');
define('BX_DOL_TRANSCODER_OBJ_ICON_FACEBOOK', 'sys_icon_facebook');
define('BX_DOL_TRANSCODER_OBJ_ICON_FAVICON', 'sys_icon_favicon');

//--- Module types ---//
if (!defined('BX_DOL_MODULE_TYPE_MODULE')) {
    define('BX_DOL_MODULE_TYPE_MODULE', 'module');
    define('BX_DOL_MODULE_TYPE_LANGUAGE', 'language');
    define('BX_DOL_MODULE_TYPE_TEMPLATE', 'template');
}

//--- Studio settings ---//
define('BX_DOL_STUDIO_FOLDER', 'studio');

define('BX_DOL_URL_STUDIO', BX_DOL_URL_ROOT . BX_DOL_STUDIO_FOLDER . '/');
define('BX_DOL_URL_STUDIO_BASE', BX_DOL_URL_STUDIO . 'template/');

define('BX_DOL_DIR_STUDIO', BX_DIRECTORY_PATH_ROOT . BX_DOL_STUDIO_FOLDER . '/');
define('BX_DOL_DIR_STUDIO_INC', BX_DOL_DIR_STUDIO . 'inc/');
define('BX_DOL_DIR_STUDIO_CLASSES', BX_DOL_DIR_STUDIO . 'classes/');
define('BX_DOL_DIR_STUDIO_BASE', BX_DOL_DIR_STUDIO . 'template/');

//--- BoonEx Unity Settings ---//
define('BX_DOL_UNITY_URL_ROOT', 'http://www.boonex.com/');
define('BX_DOL_UNITY_URL_MARKET', BX_DOL_UNITY_URL_ROOT . 'market/');
define('BX_DOL_UNITY_URL_PRODUCT', BX_DOL_UNITY_URL_ROOT . 'm/');

//--- BoonEx OAuth Settings ---//
define('BX_DOL_OAUTH_URL_REQUEST_TOKEN', BX_DOL_UNITY_URL_ROOT . 'scripts_public/oauth_request_token.php5');
define('BX_DOL_OAUTH_URL_AUTHORIZE', BX_DOL_UNITY_URL_ROOT . 'scripts_public/oauth_authorize.php5');
define('BX_DOL_OAUTH_URL_ACCESS_TOKEN', BX_DOL_UNITY_URL_ROOT . 'scripts_public/oauth_access_token.php5');
define('BX_DOL_OAUTH_URL_FETCH_DATA', BX_DOL_UNITY_URL_ROOT . 'scripts_public/oauth_fetch_data.php5');

//--- User Roles ---//
define('BX_DOL_ROLE_GUEST', 0);
define('BX_DOL_ROLE_MEMBER', 1);
define('BX_DOL_ROLE_ADMIN', 2);

// profile statuses
define('BX_PROFILE_STATUS_SUSPENDED', 'suspended'); ///< profile status - suspended, profile is syspended by admin/moderator and usually can't access the site
define('BX_PROFILE_STATUS_ACTIVE', 'active'); ///< profile status - active, profile is active on the site
define('BX_PROFILE_STATUS_PENDING', 'pending'); ///< profile status - pending, default method of approving is manual approving

define('BX_DOL_SECRET', '%SECRET%'); 

define('CHECK_DOLPHIN_REQUIREMENTS', 1);
if (defined('CHECK_DOLPHIN_REQUIREMENTS')) {
    $aErrors = array();
    $aErrors[] = (ini_get('register_globals') == 0) ? '' : '<font color="red">register_globals is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
    $aErrors[] = (ini_get('safe_mode') == 0) ? '' : '<font color="red">safe_mode is On, disable it</font>';
    $aErrors[] = (version_compare(PHP_VERSION, '5.2.0', '<')) ? '<font color="red">PHP version too old, please update to PHP 5.2.0 at least</font>' : '';
    $aErrors[] = (!extension_loaded( 'mbstring')) ? '<font color="red">mbstring extension not installed. <b>Warning!</b> Dolphin cannot work without <b>mbstring</b> extension.</font>' : '';
    if (version_compare(phpversion(), "5.2", ">") == 1) {
        $aErrors[] = (ini_get('allow_url_include') == 0) ? '' : '<font color="red">allow_url_include is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
    };

    $aErrors = array_diff($aErrors, array('')); // delete empty
    if (count($aErrors)) {
        $sErrors = implode(" <br /> ", $aErrors);
        echo <<<EOF
{$sErrors} <br />
Please go to the <br />
<a href="http://www.boonex.com/trac/dolphin/wiki/GenDolTShooter">Dolphin Troubleshooter</a> <br />
and solve the problem.
EOF;
        exit;
    }
}

// check correct hostname
$aUrl = parse_url( BX_DOL_URL_ROOT );
if (isset($_SERVER['HTTP_HOST']) and 0 != strcasecmp($_SERVER['HTTP_HOST'], $aUrl['host']) and 0 != strcasecmp($_SERVER['HTTP_HOST'], $aUrl['host'] . ':80')) {
    header( "Location:http://{$aUrl['host']}{$_SERVER['REQUEST_URI']}" );
    exit;
}

// check if install folder exists
if (!defined ('BX_SKIP_INSTALL_CHECK') && file_exists(BX_DIRECTORY_PATH_ROOT . 'install')) {
    header('Location:' . BX_DOL_URL_ROOT . 'install/index.php?action=remove_install');
    exit;
}

// set PHP options
error_reporting(E_ALL);
ini_set('magic_quotes_sybase', 0);
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

// include files necessary for basic functionality
require_once(BX_DIRECTORY_PATH_CLASSES . "BxDol.php");
require_once(BX_DIRECTORY_PATH_INC . "utils.inc.php");

bx_import('BxDolConfig');
bx_import('BxDolService');
bx_import('BxDolAlerts');
bx_import('BxDolDb');

require_once(BX_DIRECTORY_PATH_INC . "profiles.inc.php");

$o = new BxDolAlerts('system', 'begin', 0);
$o->alert();
