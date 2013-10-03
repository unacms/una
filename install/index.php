<?php

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License.
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details.
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/



define('CHECK_DOLPHIN_REQUIREMENTS', 1); //Don`t recommend to skip this step
if (defined('CHECK_DOLPHIN_REQUIREMENTS')) {
    //check requirements
    $aErrors = array();

    $aErrors[] = (ini_get('register_globals') == 0) ? '' : '<font color="red">register_globals is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
    $aErrors[] = (ini_get('safe_mode') == 0) ? '' : '<font color="red">safe_mode is On, disable it</font>';
    //$aErrors[] = (ini_get('allow_url_fopen') == 0) ? 'Off (warning, better keep this parameter in On to able register Dolphin' : '';
    $aErrors[] = (version_compare(PHP_VERSION, '5.2.0', '<')) ? '<font color="red">PHP version too old, please update to PHP 5.2.0 at least</font>' : '';
    $aErrors[] = (! extension_loaded( 'mbstring')) ? '<font color="red">mbstring extension not installed. <b>Warning!</b> Dolphin cannot work without <b>mbstring</b> extension.</font>' : '';
    // $aErrors[] = (! function_exists('shell_exec')) ? '<font color="red">shell_exec function is unvailable. <b>Warning!</b> Dolphin cannot work without <b>shell_exec</b> function.</font>' : '';
    $aErrors[] = (ini_get('short_open_tag') == 0) ? '<font color="red">short_open_tag is Off (must be On!)<b>Warning!</b> Dolphin cannot work without <b>short_open_tag</b>.</font>' : '';

    if (version_compare(phpversion(), "5.2", ">") == 1) {
        $aErrors[] = (ini_get('allow_url_include') == 0) ? '' : '<font color="red">allow_url_include is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
    };

    $aErrors = array_diff($aErrors, array('')); //delete empty
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

if (version_compare(phpversion(), "5.3.0", ">=")  == 1)
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
else
  error_reporting(E_ALL & ~E_NOTICE);
set_magic_quotes_runtime(0);
ini_set('magic_quotes_sybase', 0);

/*------------------------------*/
/*----------Vars----------------*/
    $aConf = array();
    $aConf['release'] = '24.03.11';
    $aConf['iVersion'] = '8.0';
    $aConf['iPatch'] = '0';
    $aConf['dolFile'] = '../inc/header.inc.php';
    $aConf['confDir'] = '../inc/';
    $aConf['headerTempl'] = <<<EOS
<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

define ('BX_PROFILER', true);
if (BX_PROFILER && !isset(\$GLOBALS['bx_profiler_start']))
    \$GLOBALS['bx_profiler_start'] = microtime ();


//--- Version ---//
define('BX_DOL_VERSION', '{$aConf['iVersion']}');
define('BX_DOL_BUILD', '{$aConf['iPatch']}');

//--- Main URLs ---//
define('BX_DOL_URL_ROOT', '%site_url%');
define('BX_DOL_URL_PLUGINS', BX_DOL_URL_ROOT . 'plugins/');
define('BX_DOL_URL_MODULES', BX_DOL_URL_ROOT . 'modules/');
define('BX_DOL_URL_CACHE_PUBLIC', BX_DOL_URL_ROOT . 'cache_public/');
define('BX_DOL_URL_BASE', BX_DOL_URL_ROOT . 'template/');

//--- Main Pathes ---//
define('BX_DIRECTORY_PATH_ROOT', '%dir_root%');
define('BX_DIRECTORY_PATH_INC', BX_DIRECTORY_PATH_ROOT . 'inc/');
define('BX_DIRECTORY_PATH_BASE', BX_DIRECTORY_PATH_ROOT . 'template/');
define('BX_DIRECTORY_PATH_CACHE', BX_DIRECTORY_PATH_ROOT . 'cache/');
define('BX_DIRECTORY_PATH_CACHE_PUBLIC', BX_DIRECTORY_PATH_ROOT . 'cache_public/');
define('BX_DIRECTORY_PATH_CLASSES', BX_DIRECTORY_PATH_ROOT . 'inc/classes/');
define('BX_DIRECTORY_PATH_PLUGINS', BX_DIRECTORY_PATH_ROOT . 'plugins/');
define('BX_DIRECTORY_PATH_MODULES', BX_DIRECTORY_PATH_ROOT . 'modules/');
define('BX_DIRECTORY_STORAGE', BX_DIRECTORY_PATH_ROOT . 'storage/');

//--- DB Connection ---//
define('BX_DATABASE_HOST', '%db_host%');
define('BX_DATABASE_SOCK', '%db_sock%');
define('BX_DATABASE_PORT', '%db_port%');
define('BX_DATABASE_USER', '%db_user%');
define('BX_DATABASE_PASS', '%db_password%');
define('BX_DATABASE_NAME', '%db_name%');

//--- System settings ---//
define('BX_SYSTEM_MOGRIFY', '%dir_mogrify%');
define('BX_SYSTEM_CONVERT', '%dir_convert%');
define('BX_SYSTEM_COMPOSITE', '%dir_composite%');
define('BX_SYSTEM_PHPBIN', '%dir_php%');

define('BX_DOL_DIR_RIGHTS', 0777);
define('BX_DOL_FILE_RIGHTS', 0666);

define('BX_DOL_STORAGE_OBJ_IMAGES', 'sys_images');

define('BX_DOL_INT_MAX', 2147483647);

define('BX_DOL_TRANSCODER_OBJ_ICON_APPLE', 'sys_icon_apple');
define('BX_DOL_TRANSCODER_OBJ_ICON_FACEBOOK', 'sys_icon_facebook');
define('BX_DOL_TRANSCODER_OBJ_ICON_FAVICON', 'sys_icon_favicon');
define('BX_DOL_TRANSCODER_OBJ_IMAGE_PREVIEW_CMTS', 'sys_images_cmts_preview');

//--- Module types ---//
define('BX_DOL_MODULE_TYPE_MODULE', 'module');
define('BX_DOL_MODULE_TYPE_LANGUAGE', 'language');
define('BX_DOL_MODULE_TYPE_TEMPLATE', 'template');

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

define('BX_DOL', 1);

define('BX_DOL_SECRET', 'sdn378vGR35'); // TODO: autogenerate during installation

define('CHECK_DOLPHIN_REQUIREMENTS', 1);
if (defined('CHECK_DOLPHIN_REQUIREMENTS')) {
    //check requirements
    \$aErrors = array();

    \$aErrors[] = (ini_get('register_globals') == 0) ? '' : '<font color="red">register_globals is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
    \$aErrors[] = (ini_get('safe_mode') == 0) ? '' : '<font color="red">safe_mode is On, disable it</font>';
    //\$aErrors[] = (ini_get('allow_url_fopen') == 0) ? 'Off (warning, better keep this parameter in On to able register Dolphin' : '';
    \$aErrors[] = (version_compare(PHP_VERSION, '5.2.0', '<')) ? '<font color="red">PHP version too old, please update to PHP 5.2.0 at least</font>' : '';
    \$aErrors[] = (! extension_loaded( 'mbstring')) ? '<font color="red">mbstring extension not installed. <b>Warning!</b> Dolphin cannot work without <b>mbstring</b> extension.</font>' : '';

    if (version_compare(phpversion(), "5.2", ">") == 1) {
        \$aErrors[] = (ini_get('allow_url_include') == 0) ? '' : '<font color="red">allow_url_include is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
    };

    \$aErrors = array_diff(\$aErrors, array('')); //delete empty
    if (count(\$aErrors)) {
        \$sErrors = implode(" <br /> ", \$aErrors);
        echo <<<EOF
{\$sErrors} <br />
Please go to the <br />
<a href="http://www.boonex.com/trac/dolphin/wiki/GenDolTShooter">Dolphin Troubleshooter</a> <br />
and solve the problem.
EOF;
        exit;
    }
}

//check correct hostname
\$aUrl = parse_url( BX_DOL_URL_ROOT );
if( isset(\$_SERVER['HTTP_HOST']) and 0 != strcasecmp(\$_SERVER['HTTP_HOST'], \$aUrl['host']) and 0 != strcasecmp(\$_SERVER['HTTP_HOST'], \$aUrl['host'] . ':80') ) {
    header( "Location:http://{\$aUrl['host']}{\$_SERVER['REQUEST_URI']}" );
    exit;
}

// check if install folder exists
/**
 * Uncomment for live version 
 *
if ( !defined ('BX_SKIP_INSTALL_CHECK') && file_exists( BX_DIRECTORY_PATH_ROOT . 'install' ) ) {
    \$ret = <<<EOJ
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
        <head>
            <title>Dolphin Smart Community Builder Installed</title>
            <link href="install/general.css" rel="stylesheet" type="text/css" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        </head>
        <body>
            <div id="main">
            <div id="header">
                <img src="install/images/boonex-logo.png" alt="" /></div>
            <div id="content">
                <div class="installed_pic">
                    <img alt="Dolphin Installed" src="install/images/dolphin_installed.jpg" />
            </div>

            <div class="installed_text">
                Please, remove INSTALL directory from your server and reload this page to activate your community site.
            </div>
            <div class="installed_text">
                NOTE: Once you remove this page you can safely <a href="administration/modules.php">install modules via Admin Panel</a>.
            </div>
        </body>
    </html>
EOJ;
    echo \$ret;
    exit();
}
*/

// set error reporting level
error_reporting(E_ALL);

ini_set('magic_quotes_sybase', 0);

// set default encoding for multibyte functions
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

require_once(BX_DIRECTORY_PATH_CLASSES . "BxDol.php");
require_once(BX_DIRECTORY_PATH_INC . "security.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "utils.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "db.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "profiles.inc.php");

bx_import('BxDolConfig');
bx_import('BxDolService');
bx_import('BxDolAlerts');

\$oZ = new BxDolAlerts('system', 'begin', 0);
\$oZ->alert();

EOS;

    $aConf['periodicTempl'] = <<<EOS
MAILTO=%site_email%<br />
* * * * * cd %dir_root%periodic; %dir_php% -q cron.php<br />
EOS;

    $confFirst = array();
    $confFirst['site_url'] = array(
        name => "Site URL",
        ex => "http://www.mydomain.com/path/",
        desc => "Your site URL here (backslash at the end required)",
        def => "http://",
        def_exp => '
            $str = "http://".$_SERVER[\'HTTP_HOST\'].$_SERVER[\'PHP_SELF\'];
            return preg_replace("/install\/(index\.php$)/","",$str);',
        check => 'return strlen($arg0) >= 10 ? true : false;'
    );
    $confFirst['dir_root'] = array(
        name => "Directory root",
        ex => "/path/to/your/script/files/",
        desc => "Path to directory where your php script files stored.",
        def_exp => '
            $str = rtrim($_SERVER[\'DOCUMENT_ROOT\'], \'/\').$_SERVER[\'PHP_SELF\'];
            return preg_replace("/install\/(index\.php$)/","",$str);',
        check => 'return strlen($arg0) >= 1 ? true : false;'
    );
    $confFirst['dir_php'] = array(
        name => "Path to php binary",
        ex => "/usr/local/bin/php",
        desc => "You should specify full path to your PHP interpreter here.",
        def => "/usr/local/bin/php",
        def_exp => "
            if ( file_exists(\"/usr/local/bin/php\") ) return \"/usr/local/bin/php\";
            \$fp = popen ( \"whereis php\", \"r\");
            if ( \$fp )
            {
                \$s = fgets(\$fp);
                \$s = sscanf(\$s, \"php: %s\");
                if ( file_exists(\"\$s[0]\") ) return \"\$s[0]\";
               }
               return '';",
        check => 'return strlen($arg0) >= 7 ? true : false;'
    );
    $confFirst['dir_mogrify'] = array(
        name => "Path to mogrify",
        ex => "/usr/local/bin/mogrify",
        desc => "If mogrify binary doesn't exist please install <a href='http://www.imagemagick.org/'>ImageMagick</a>",
        def => "/usr/local/bin/mogrify",
        def_exp => "
            if ( file_exists(\"/usr/X11R6/bin/mogrify\") ) return \"/usr/X11R6/bin/mogrify\";
            if ( file_exists(\"/usr/local/bin/mogrify\") ) return \"/usr/local/bin/mogrify\";
            if ( file_exists(\"/usr/bin/mogrify\") ) return \"/usr/bin/mogrify\";
            if ( file_exists(\"/usr/local/X11R6/bin/mogrify\") ) return \"/usr/local/X11R6/bin/mogrify\";
            if ( file_exists(\"/usr/bin/X11/mogrify\") ) return \"/usr/bin/X11/mogrify\";
            return '';",
        check => 'return strlen($arg0) >= 7 ? true : false;'
    );
    $confFirst['dir_convert'] = array(
        name => "Path to convert",
        ex => "/usr/local/bin/convert",
        desc => "If convert binary doesn't exist please install <a href='http://www.imagemagick.org/'>ImageMagick</a>",
        def => "/usr/local/bin/convert",
        def_exp => "
            if ( file_exists(\"/usr/X11R6/bin/convert\") ) return \"/usr/X11R6/bin/convert\";
            if ( file_exists(\"/usr/local/bin/convert\") ) return \"/usr/local/bin/convert\";
            if ( file_exists(\"/usr/bin/convert\") ) return \"/usr/bin/convert\";
            if ( file_exists(\"/usr/local/X11R6/bin/convert\") ) return \"/usr/local/X11R6/bin/convert\";
            if ( file_exists(\"/usr/bin/X11/convert\") ) return \"/usr/bin/X11/convert\";
            return '';",
        check => 'return strlen($arg0) >= 7 ? true : false;'
    );
    $confFirst['dir_composite'] = array(
        name => "Path to composite",
        ex => "/usr/local/bin/composite",
        desc => "If composite binary doesn't exist please install <a href='http://www.imagemagick.org/'>ImageMagick</a>",
        def => "/usr/local/bin/composite",
        def_exp => "
            if ( file_exists(\"/usr/X11R6/bin/composite\") ) return \"/usr/X11R6/bin/composite\";
            if ( file_exists(\"/usr/local/bin/composite\") ) return \"/usr/local/bin/composite\";
            if ( file_exists(\"/usr/bin/composite\") ) return \"/usr/bin/composite\";
            if ( file_exists(\"/usr/local/X11R6/bin/composite\") ) return \"/usr/local/X11R6/bin/composite\";
            if ( file_exists(\"/usr/bin/X11/composite\") ) return \"/usr/bin/X11/composite\";
            return '';",
        check => 'return strlen($arg0) >= 7 ? true : false;'
    );

    $aDbConf = array();
    $aDbConf['sql_file'] = array(
        name => "SQL file",
        ex => "/home/dolphin/public_html/install/sql/vXX.sql",
        desc => "SQL file location",
        def => "./sql/vXX.sql",
        def_exp => '
            if ( !( $dir = opendir( "sql/" ) ) )
                return "";
            while (false !== ($file = readdir($dir)))
                {
                if ( substr($file,-3) != \'sql\' ) continue;
                closedir( $dir );
                return "./sql/$file";
            }
            closedir( $dir );
            return "";',
        check => 'return strlen($arg0) >= 4 ? true : false;'
    );
    $aDbConf['db_host'] = array(
        name => "Database host name",
        ex => "localhost",
        desc => "Your MySQL database host name here.",
        def => "localhost",
        check => 'return strlen($arg0) >= 1 ? true : false;'
    );
    $aDbConf['db_port'] = array(
        name => "Database host port number",
        ex => "5506",
        desc => "Leave blank or specify MySQL Database host port number.",
        def => "",
        check => ''
    );
    $aDbConf['db_sock'] = array(
        name => "Database socket path",
        ex => "/tmp/mysql50.sock",
        desc => "Leave blank or specify MySQL Database socket path.",
        def => "",
        check => ''
    );
    $aDbConf['db_name'] = array(
        name => "Database name",
        ex => "YourDatabaseName",
        desc => "Your MySQL database name here.",
        check => 'return strlen($arg0) >= 1 ? true : false;'
    );
    $aDbConf['db_user'] = array(
        name => "Database user",
        ex => "YourName",
        desc => "Your MySQL database read/write user name here.",
        check => 'return strlen($arg0) >= 1 ? true : false;'
    );
    $aDbConf['db_password'] = array(
        name => "Database password",
        ex => "YourPassword",
        desc => "Your MySQL database password here.",
        check => 'return strlen($arg0) >= 0 ? true : false;'
    );

    $aGeneral = array();
    $aGeneral['site_title'] = array(
        name => "Site Title",
        ex => "The Best Community",
        desc => "The name of your site",
        check => 'return strlen($arg0) >= 1 ? true : false;'
    );
    $aGeneral['site_desc'] = array(
        name => "Site Description",
        ex => "The place to find new friends, communicate and have fun.",
        desc => "Meta description of your site",
        check => 'return strlen($arg0) >= 1 ? true : false;'
    );
    $aGeneral['site_email'] = array(
        name => "Site e-mail",
        ex => "your@email.here",
        desc => "Your site e-mail.",
        check => 'return strlen($arg0) > 0 AND strstr($arg0,"@") ? true : false;'
    );
    $aGeneral['notify_email'] = array(
        name => "Notify e-mail",
        ex => "your@email.here",
        desc => "Envelope \"From:\" address for notification messages",
        check => 'return strlen($arg0) > 0 AND strstr($arg0,"@") ? true : false;'
    );
    $aGeneral['bug_report_email'] = array(
        name => "Bug report email",
        ex => "your@email.here",
        desc => "Your email for receiving bug reports.",
        check => 'return strlen($arg0) > 0 AND strstr($arg0,"@") ? true : false;'
    );
    $aGeneral['admin_username'] = array(
        name => "Admin Username",
        ex => "admin",
        desc => "Specify the admin name here",
        check => 'return strlen($arg0) >= 1 ? true : false;'
    );
    $aGeneral['admin_password'] = array(
        name => "Admin Password",
        ex => "dolphin",
        desc => "Specify the admin password here",
        check => 'return strlen($arg0) >= 1 ? true : false;'
    );

    $aNonDeletableModules = array(
        'boonex/shared_photo/',
    );

    $aTemporalityWritableFolders = array(
        'inc',
    );

/*----------Vars----------------*/
/*------------------------------*/


$sAction = $_REQUEST['action'];
$sError = '';

define('BX_SKIP_INSTALL_CHECK', true);
// --------------------------------------------
if ($sAction=='step6' || $sAction=='step7' || $sAction=='compile_languages') {
    require_once('../inc/header.inc.php' );
    require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
    require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
} else {
    define ('BX_DOL', 1);
    require_once('../inc/classes/BxDol.php');
}
// --------------------------------------------
require_once('../inc/classes/BxDolIO.php');


$sInstallPageContent = InstallPageContent( $sError );

mb_internal_encoding('UTF-8');

echo PageHeader( $sAction, $sError );
echo $sInstallPageContent;
echo PageFooter( $sAction );

function InstallPageContent(&$sError) {
    global $aConf, $confFirst, $aDbConf, $aGeneral;

    $sRet = '';

    switch ($_REQUEST['action']) {
        case 'compile_languages':
            performInstallLanguages();
            $sRet .= 'Default Dolphin language was recompiled';
            break;
        /*case 'step8':
            $sRet .= genMainDolphinPage();
        break;

        case 'step7':
            $sInstallLog = '';
            if ($_REQUEST['sub_action']=='install_modules') {
                if (is_array($_POST['pathes']) && count($_POST['pathes'])>0) {
                    $oInstallerUi = new BxDolInstallerUi();
                    $sInstallLog = $oInstallerUi->actionInstall($_POST['pathes']);
                }
            }
            $sRet .= genInstallModulesPage($sInstallLog);
        break;*/

        case 'step7':
            $sRet .= genMainDolphinPage();
        break;

        case 'step6':
            $sErrorMessage = checkPostInstallPermissions($sError);
            $sRet .= (strlen($sErrorMessage)) ? genPostInstallPermissionTable($sErrorMessage) : genMainDolphinPage();
        break;

        case 'step5':
            $sRet .= genPostInstallPermissionTable();
        break;

        case 'step4':
            $sErrorMessage = checkConfigArray($aGeneral, $sError);
            $sRet .= (strlen($sErrorMessage)) ? genSiteGeneralConfig($sErrorMessage) : genInstallationProcessPage();
        break;

        case 'step3':
            $sErrorMessage = checkConfigArray($aDbConf, $sError);
            $sErrorMessage .= CheckSQLParams();

            $sRet .=  (strlen($sErrorMessage)) ? genDatabaseConfig($sErrorMessage) : genSiteGeneralConfig();
        break;

        case 'step2':
            $sErrorMessage = checkConfigArray($confFirst, $sError);
            $sRet .= (strlen($sErrorMessage)) ? genPathCheckingConfig($sErrorMessage) : genDatabaseConfig();
        break;

        case 'step1':
            $sErrorMessage = checkPreInstallPermission($sError);
            $sRet .= (strlen($sErrorMessage)) ? genPreInstallPermissionTable($sErrorMessage) : genPathCheckingConfig();
        break;

        case 'preInstall':
            $sRet .= genPreInstallPermissionTable();
        break;

        default:
            $sRet .= StartInstall();
        break;
    }

    return $sRet;
}

function performInstallLanguages() {
    db_res("TRUNCATE TABLE `sys_localization_languages`");
    db_res("TRUNCATE TABLE `sys_localization_keys`");
    db_res("TRUNCATE TABLE `sys_localization_strings`");

    if (!($sLangsDir = opendir(BX_DIRECTORY_PATH_ROOT . 'install/langs/')))
        return;
    while (false !== ($sFilename = readdir($sLangsDir))) {
        if (substr($sFilename,-3) == 'php') {
            //$sLangName = substr($sFilename,-6, 2);
            unset($LANG);
            unset($LANG_INFO);
            require_once(BX_DIRECTORY_PATH_ROOT . 'install/langs/' . $sFilename);
            walkThroughLanguage($LANG, $LANG_INFO);
        }
    }
    closedir ($sLangsDir);
    require_once('../inc/languages.inc.php');
    compileLanguage();
}

function walkThroughLanguage($aLanguage, $aLangInfo) {
    $sLangName = $aLangInfo['Name'];
    $sLangFlag = $aLangInfo['Flag'];
    $sLangTitle = $aLangInfo['Title'];
    $sInsertLanguageSQL = "INSERT INTO `sys_localization_languages` VALUES (NULL, '{$sLangName}', '{$sLangFlag}', '{$sLangTitle}')";
    db_res($sInsertLanguageSQL);
    $iLangKey = db_last_id();

    foreach ($aLanguage as $sKey => $sValue) {
        $sDqKey = str_replace("'", "''", $sKey);
        $sDqValue = str_replace("'", "''", $sValue);

        $iExistedKey = (int)db_value("SELECT `ID` FROM `sys_localization_keys` WHERE `Key`='{$sDqKey}'");
        if ($iExistedKey>0) { //Key existed, no need insert key
        } else {
            $sInsertKeySQL = "INSERT INTO `sys_localization_keys` VALUES(NULL, 1, '{$sDqKey}')";
            db_res($sInsertKeySQL);
            $iExistedKey = db_last_id();
        }

        $sInsertValueSQL = "INSERT INTO `sys_localization_strings` VALUES({$iExistedKey}, {$iLangKey}, '{$sDqValue}');";
        db_res($sInsertValueSQL);
    }
}

function genInstallModulesPage($sErrorMessage = '') {
    global $aNonDeletableModules;

    $sCurPage = $_SERVER['PHP_SELF'];

    if ($_REQUEST['sub_action']!='install_modules') {
        performInstallLanguages();
    }

    $sErrors = printInstallError($sErrorMessage);

    $oInstallerUi = new BxDolInstallerUi();
    $aAdditionalInputs['hidden_sub_action'] = array(
        'type' => 'hidden',
        'name' => 'sub_action',
        'value' => 'install_modules'
    );
    $aAdditionalInputs['hidden_action'] = array(
        'type' => 'hidden',
        'name' => 'action',
        'value' => 'step7'
    );

    $sNotInstalled = $oInstallerUi->getNotInstalled($aAdditionalInputs /*, $aNonDeletableModules*/);

    //module_not_install_form
    $sSkipStep = '';
    if ($_REQUEST['sub_action']=='install_modules') {
        $sSkipStep = <<<EOF
<div class="button_area_2">
    <form action="{$sCurPage}" method="post">
        <input id="button" type="image" src="images/skip.gif" />
        <input type="hidden" name="action" value="step8" />
    </form>
</div>
EOF;
    }

    return <<<EOF
<div class="position">Modules.</div>
{$sErrors}
<div class="LeftRight">
    <div class="clearBoth"></div>
    <div class="left">&nbsp;</div>
    <div class="right">

    <script type="text/javascript">
    <!--
        function PerformInstall() {
            var oForm = document.getElementById('module_not_install_form');
            oForm.submit();
        }
    -->
    </script>

        {$sNotInstalled}
        <div class="formKeeper1">
            <div class="button_area_1">
                <form action="{$sCurPage}" method="post">
                    <input id="button" type="image" src="images/next.gif" onclick="PerformInstall(); return false;" />
                    <input type="hidden" name="action" value="step8" />
                </form>
            </div>
            {$sSkipStep}
        </div>
    </div>
    <div class="clearBoth"></div>
</div>
EOF;
}

function genInstallationProcessPage($sErrorMessage = '') {
    global $aConf, $confFirst, $aDbConf, $aGeneral;

    $sAdminName     = get_magic_quotes_gpc() ? stripslashes($_REQUEST['admin_username']) : $_REQUEST['admin_username'];
    $sAdminPassword = get_magic_quotes_gpc() ? stripslashes($_REQUEST['admin_password']) : $_REQUEST['admin_password'];
    $resRunSQL = RunSQL( $sAdminName, $sAdminPassword );

    $sForm = '';

    if ('done' ==  $resRunSQL) {
        $sForm = '
        <div class="formKeeper">
            <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
                <input type="image" src="images/next.gif" />
                <input type="hidden" name="action" value="step5" />
            </form>
        </div>
        <div class="clearBoth"></div>';
    } else {
        $sForm = $resRunSQL . '
        <div class="formKeeper">
            <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
                <input type="image" src="images/back.gif" />';
        foreach ($_POST as $sKey => $sValue) {
            if ($sKey != "action")
                $sForm .= '<input type="hidden" name="' . $sKey . '" value="' . $sValue . '" />';
        }
        $sForm .= '<input type="hidden" name="action" value="step2" />
            </form>
        </div>
        <div class="clearBoth"></div>';
        return $sForm;
    }

    foreach ($confFirst as $key => $val) {
        $aConf['headerTempl'] = str_replace ("%$key%", $_POST[$key], $aConf['headerTempl']);
    }
    foreach ($aDbConf as $key => $val) {
        $aConf['headerTempl'] = str_replace ("%$key%", $_POST[$key], $aConf['headerTempl']);
    }
    foreach ($aGeneral as $key => $val) {
        $aConf['headerTempl'] = str_replace ("%$key%", $_POST[$key], $aConf['headerTempl']);
    }

    $aConf['periodicTempl'] = str_replace("%site_email%", $_POST['site_email'], $aConf['periodicTempl']);
    $aConf['periodicTempl'] = str_replace("%dir_root%",   $_POST['dir_root'],   $aConf['periodicTempl']);
    $aConf['periodicTempl'] = str_replace("%dir_php%",    $_POST['dir_php'],    $aConf['periodicTempl']);

    $sInnerCode = '';
    $fp = fopen($aConf['dolFile'], 'w');
    if ($fp) {
        fputs($fp, $aConf['headerTempl']);
        fclose($fp);
        chmod($aConf['dolFile'], 0666);
        //$sInnerCode .='Config file was successfully written to <strong>' . $aConf['dolFile'] . '</strong><br />';
    } else {
        $text = 'Warning!!! can not get write access to config file ' . $aConf['dolFile'] . '. Here is config file</font><br>';
        $sInnerCode .= printInstallError($text);
        $trans = get_html_translation_table(HTML_ENTITIES);
        $templ = strtr($aConf['headerTempl'], $trans);
        $sInnerCode .= '<textarea cols="20" rows="10" class="headerTextarea">' . $aConf['headerTempl'] . '</textarea>';
    }

    $sInnerCode .= <<<EOF
<div class="left">
    Please, setup Cron Jobs as specified below. Helpful info about Cron Jobs is <a href="http://www.boonex.com/trac/dolphin/wiki/DetailedInstall#InstallScript-Step5-CronJobs">available here</a>.</div>
    <div class="debug">
        {$aConf['periodicTempl']}
    </div>
EOF;

    return <<<EOF
<div class="position">Cron Jobs</div>
<div class="LeftRirght">
    {$sInnerCode}{$sForm}
</div>
EOF;
}

function isAdmin() { return false; }

// check of step 5
function checkPostInstallPermissions(&$sError) {
    global $aTemporalityWritableFolders;

    $sFoldersErr = $sFilesErr = $sErrorMessage = '';
    
    require_once('../studio/classes/BxDolStudioTools.php');
    $oAdmTools = new BxDolAdminTools();
    $oBxDolIO = new BxDolIO();

    $aInstallDirsMerged = array_merge($aTemporalityWritableFolders, $oAdmTools->aPostInstallPermDirs);
    foreach ($aInstallDirsMerged as $sFolder) {
        if ($oBxDolIO->isWritable($sFolder)) {
            $sFoldersErr .= '&nbsp;&nbsp;&nbsp;' . $sFolder . ';<br />';
        }
    }
    if (strlen( $sFoldersErr)) {
        $sError = 'error';
        $sErrorMessage .= '<strong>Next directories have inappropriate permissions</strong>:<br />' . $sFoldersErr;
    }
    foreach ($oAdmTools->aPostInstallPermFiles as $sFile) {
        if ($oBxDolIO->isWritable($sFile)) {
            $sFilesErr .= '&nbsp;&nbsp;&nbsp;' . $sFile . ';<br /> ';
        }
    }
    if (strlen($sFilesErr)) {
        $sError = 'error';
        $sErrorMessage .= '<strong>Next files have inappropriate permissions</strong>:<br />' . $sFilesErr;
    }

    return $sErrorMessage;
}

// step 5
function genPostInstallPermissionTable($sErrorMessage = '') {
    global $aTemporalityWritableFolders;

    $sCurPage = $_SERVER['PHP_SELF'];
    $sPostFolders = $sPostFiles = '';

    $sErrors = printInstallError($sErrorMessage);

    require_once('../studio/classes/BxDolStudioTools.php');
    $oAdmTools = new BxDolAdminTools();
    $oBxDolIO = new BxDolIO();

    $aInstallDirsMerged = array_merge($aTemporalityWritableFolders, $oAdmTools->aPostInstallPermDirs);
    $i = 0;
    foreach($aInstallDirsMerged as $sFolder) {
        $sStyleAdd = ( ($i%2) == 0 ) ? 'background-color:#ede9e9;' : 'background-color:#fff;';

        $sEachFolder = ( $oBxDolIO->isWritable($sFolder) )
            ? '<span class="unwritable">Writable</span>' : '<span class="writable">Non-writable</span>';

        $sPostFolders .= <<<EOF
<tr style="{$sStyleAdd}" class="cont">
    <td>{$sFolder}</td>
    <td class="span">
        {$sEachFolder}
    </td>
    <td class="span">
        <span class="desired">Non-writable</span>
    </td>
</tr>
EOF;
        $i++;
    }

    $i = 0;
    foreach($oAdmTools->aPostInstallPermFiles as $sFile) {
        $str = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF'];
        $sFolder = preg_replace("/install\/(index\.php$)/","",$str);

        if (file_exists($sFolder . $sFile)) {
            $sStyleAdd = ( ($i%2) == 0 ) ? 'background-color:#ede9e9;' : 'background-color:#fff;';

            $sEachFile = ( $oBxDolIO->isWritable($sFile) )
                ? '<span class="unwritable">Writable</span>'
                : '<span class="writable">Non-writable</span>';

            $sPostFiles .= <<<EOF
<tr style="{$sStyleAdd}" class="cont">
    <td>{$sFile}</td>
    <td class="span">
        {$sEachFile}
    </td>
    <td class="span">
        <span class="desired">Non-writable</span>
    </td>
</tr>
EOF;
            $i++;
        }
    }

    return <<<EOF
<div class="position">Permissions Reversal</div>
{$sErrors}
<div class="LeftRight">
    <div class="clearBoth"></div>
    <div class="left">Now, when Dolphin completed installation, you should change permissions for some files to keep your site secure. Please, change permissions as specified in the chart below. Helpful info about permissions is <a href="http://www.boonex.com/trac/dolphin/wiki/DetailedInstall#InstallScript-Step1-Permissions" target="_blank">available here</a>.</div>
    <div class="right">
        <table cellpadding="0" cellspacing="1" width="100%" border="0" style="background-color:silver;">
            <tr class="head">
                <td>Directories</td>
                <td>Current Level</td>
                <td>Desired Level</td>
            </tr>
            {$sPostFolders}
            <tr class="head">
                <td>Files</td>
                <td>Current Level</td>
                <td>Desired Level</td>
            </tr>
            {$sPostFiles}
        </table>
        <div class="formKeeper1">
            <div class="button_area_1">
                <form action="{$sCurPage}" method="post">
                    <input id="button" type="image" src="images/check.gif" />
                    <input type="hidden" name="action" value="step5" />
                </form>
            </div>
            <div class="button_area_1">
                <form action="{$sCurPage}" method="post">
                    <input id="button" type="image" src="images/next.gif" />
                    <input type="hidden" name="action" value="step6" />
                </form>
            </div>
            <div class="button_area_2">
                <form action="{$sCurPage}" method="post">
                    <input id="button" type="image" src="images/skip.gif" />
                    <input type="hidden" name="action" value="step7" />
                </form>
            </div>
        </div>
    </div>
    <div class="clearBoth"></div>
</div>
EOF;
}

function genSiteGeneralConfig($sErrorMessage = '') {
    global $aGeneral;

    $sCurPage = $_SERVER['PHP_SELF'];
    $sSGParamsTable = createTable($aGeneral);

    $sErrors = '';
    if (strlen($sErrorMessage)) {
        $sErrors = printInstallError($sErrorMessage);
        unset($_POST['site_title']);
        unset($_POST['site_email']);
        unset($_POST['notify_email']);
        unset($_POST['bug_report_email']);
    }

    $sOldDataParams = '';
    foreach($_POST as $postKey => $postValue) {
        $sOldDataParams .= ('action' == $postKey || isset($aGeneral[$postKey])) ? '' : '<input type="hidden" name="' . $postKey . '" value="' . $postValue . '" />';
    }

    return <<<EOF
<div class="position">Configuration</div>
{$sErrors}
<div class="LeftRirght">
    <div class="clearBoth"></div>
    <div class="left"></div>
    <div class="right">
        <form action="{$sCurPage}" method="post">
            <table cellpadding="0" cellspacing="1" width="100%" border="0" style="background-color:silver;">
                <tr class="head">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                {$sSGParamsTable}
            </table>
            <div class="formKeeper">
                <input id="button" type="image" src="images/next.gif" />
                <input type="hidden" name="action" value="step4" />
                {$sOldDataParams}
            </div>
        </form>
    </div>
    <div class="clearBoth"></div>
</div>
EOF;
}

// check of config pages steps
function checkConfigArray($aCheckedArray, &$sError) {
    //$error_arr //It is like global variable
    //$config_arr //It is like global variable, but non used

    $sErrorMessage = '';

    foreach ($aCheckedArray as $sKey => $sValue) {
        if (! strlen($sValue['check'])) continue;

        $funcbody = $sValue['check'];
        $func = create_function('$arg0', $funcbody);

        if (! $func($_POST[$sKey])) {
            $sFieldErr = $sValue['name'];
            $sErrorMessage .= "Please, input valid data to <b>{$sFieldErr}</b> field<br />";
            $error_arr[$sKey] = 1;
            unset($_POST[$sKey]);
        } else
            $error_arr[$sKey] = 0;

        //$config_arr[$sKey]['def'] = $_POST[$sKey];
    }

    if (strlen($sErrorMessage)) {
        $sError = 'error';
    }

    return $sErrorMessage;
}

function genDatabaseConfig($sErrorMessage = '') {
    global $aDbConf;

    $sCurPage = $_SERVER['PHP_SELF'];
    $sDbParamsTable = createTable($aDbConf);

    $sErrors = '';
    if (strlen($sErrorMessage)) {
        $sErrors = printInstallError($sErrorMessage);
        unset($_POST['db_name']);
        unset($_POST['db_user']);
        unset($_POST['db_password']);
    }

    $sOldDataParams = '';
    foreach($_POST as $postKey => $postValue) {
        $sOldDataParams .= ('action' == $postKey || isset($aDbConf[$postKey])) ? '' : '<input type="hidden" name="' . $postKey . '" value="' . $postValue . '" />';
    }

    return <<<EOF
<div class="position">Database</div>
{$sErrors}
<div class="LeftRirght">
    <div class="clearBoth"></div>
    <div class="left">
        Please <a target="_blank" href="http://www.boonex.com/trac/dolphin/wiki/DetailedInstall#Part2:CreateaDatabaseandaUser">create a database</a> and tell Dolphin about it.
    </div>
    <div class="right">
        <form action="{$sCurPage}" method="post">
            <table cellpadding="0" cellspacing="1" width="100%" border="0" style="background-color:silver;">
                <tr class="head">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                {$sDbParamsTable}
            </table>
            <div class="formKeeper">
                <input id="button" type="image" src="images/next.gif" />
                <input type="hidden" name="action" value="step3" />
                {$sOldDataParams}
            </div>
        </form>
    </div>
    <div class="clearBoth"></div>
</div>
EOF;
}

function genPathCheckingConfig($sErrorMessage = '') {
    global  $aConf, $confFirst;

    $sCurPage = $_SERVER['PHP_SELF'];

    $sGDRes = (extension_loaded('gd')) ? '<span class="writable">GD library installed</span>'
        : '<span class="unwritable">GD library NOT installed</span>';

    $sError = printInstallError( $sErrorMessage );
    $sPathsTable = createTable($confFirst);

    return <<<EOF
<div class="position">Paths Check</div>
{$sError}
<div class="LeftRirght">
    <div class="clearBoth"></div>
    <div class="left">
        Dolphin checks general script paths.
    </div>
    <div class="right">
        <form action="{$sCurPage}" method="post">
            <table cellpadding="0" cellspacing="1" width="100%" border="0" style="background-color:silver;">
                <tr class="head">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                {$sPathsTable}
                <tr class="cont" style="background-color:#ede9e9;">
                    <td>
                        Check GD Installed
                    </td>
                    <td>
                        {$sGDRes}
                    </td>
                </tr>
            </table>
            <div class="formKeeper">
                <input id="button" type="image" src="images/next.gif" />
                <input type="hidden" name="action" value="step2" />
            </div>
        </form>
    </div>
    <div class="clearBoth"></div>
</div>
EOF;
}

function checkPreInstallPermission(&$sError) {
    global $aTemporalityWritableFolders;

    $sFoldersErr = $sFilesErr = $sErrorMessage = '';

    $oBxDolIO = new BxDolIO();

    require_once('../studio/classes/BxDolStudioTools.php');
    $oAdmTools = new BxDolAdminTools();

    $aInstallDirsMerged = array_merge($aTemporalityWritableFolders, $oAdmTools->aInstallDirs);
    foreach ($aInstallDirsMerged as $sFolder) {
        if (! $oBxDolIO->isWritable($sFolder)) {
            $sFoldersErr .= '&nbsp;&nbsp;&nbsp;' . $sFolder . ';<br />';
        }
    }

    foreach ($oAdmTools->aFlashDirs as $sFolder) {
        if (! $oBxDolIO->isWritable($sFolder)) {
            $sFoldersErr .= '&nbsp;&nbsp;&nbsp;' . $sFolder . ';<br />';
        }
    }

    if( strlen( $sFoldersErr ) ) {
        $sError = 'error';
        $sErrorMessage .= '<strong>Next directories have inappropriate permissions</strong>:<br />' . $sFoldersErr;
    }

    foreach ($oAdmTools->aInstallFiles as $sFile) {
        if (! $oBxDolIO->isWritable($sFile)) {
            $sFilesErr .= '&nbsp;&nbsp;&nbsp;' . $sFile . ';<br /> ';
        }
    }

    foreach( $oAdmTools->aFlashFiles as $sFile ) {
        if (strpos($sFile,'ffmpeg') === false) {
            if (! $oBxDolIO->isWritable($sFile)) {
                $sFilesErr .= '&nbsp;&nbsp;&nbsp;' . $sFile . ';<br /> ';
            }
        } else {
            if (! $oBxDolIO->isExecutable($sFile)) {
                $sFilesErr .= '&nbsp;&nbsp;&nbsp;' . $sFile . ';<br /> ';
            }
        }
    }

    if (strlen($sFilesErr)) {
        $sError = 'error';
        $sErrorMessage .= '<strong>Next files have inappropriate permissions</strong>:<br />' . $sFilesErr;
    }

    return $sErrorMessage;
}

// pre install
function genPreInstallPermissionTable($sErrorMessage = '') {
    global $aTemporalityWritableFolders;

    $sCurPage = $_SERVER['PHP_SELF'];
    $sErrorMessage .= (ini_get('safe_mode') == 1 || ini_get('safe_mode') == 'On') ? "Please turn off <b>safe_mode</b> in your php.ini file configuration" : '';
    $sError = printInstallError($sErrorMessage);

    require_once('../studio/classes/BxDolStudioTools.php');
    $oAdmTools = new BxDolAdminTools();
    $oAdmTools->aInstallDirs = array_merge($aTemporalityWritableFolders, $oAdmTools->aInstallDirs);
    $sPermTable = $oAdmTools->GenCommonCode();
    $sPermTable .= $oAdmTools->GenPermTable();

    return <<<EOF
<div class="position">Permissions</div>
{$sError}
<div class="LeftRirght">
    <div class="clearBoth"></div>
    <div class="left">
        Dolphin needs special access for certain files and directories. Please, change permissions as specified in the chart below. Helpful info about permissions is <a href="http://www.boonex.com/trac/dolphin/wiki/DetailedInstall#InstallScript-Step1-Permissions" target="_blank">available here</a>.
    </div>
    <div class="clear_both"></div>
    <div class="right">
        <script src="../plugins/jquery/jquery.js" type="text/javascript" language="javascript"></script>
        {$sPermTable}
        <div class="formKeeper">
            <div class="button_area_1">
                <form action="{$sCurPage}" method="post">
                    <input id="button" type="image" src="images/check.gif" />
                    <input type="hidden" name="action" value="preInstall" />
                </form>
            </div>
            <div class="button_area_2">
                <form action="{$sCurPage}" method="post">
                    <input id="button" type="image" src="images/next.gif" />
                    <input type="hidden" name="action" value="step1" />
                </form>
            </div>
            <div class="clearBoth"></div>
        </div>
    </div>
</div>
EOF;
}

function StartInstall() {
    global $aConf;

    return <<<EOF
<div class="install_pic">
    Dolphin {$aConf['iVersion']}.{$aConf['iPatch']}
</div>

<div class="install_text">
    Thank you for choosing Dolphin Smart Community Builder!<br />
    Click the button below to create your own community.
</div>

<div class="install_button">
    <form action="{$_SERVER['PHP_SELF']}" method="post">
    <input id="button" type="image" src="images/install.gif" />
    <input type="hidden" name="action" value="preInstall" />
    </form>
</div>
EOF;
}

function genMainDolphinPage() {
    //TODO: Forward into Studio -> Store to install Language(s) or install default one automatically.
    //performInstallLanguages();

    /**
     *  Register System Transcoders
	 */
    $aTranscoders = array(
        BX_DOL_TRANSCODER_OBJ_ICON_APPLE, 
        BX_DOL_TRANSCODER_OBJ_ICON_FACEBOOK, 
        BX_DOL_TRANSCODER_OBJ_ICON_FAVICON,
        BX_DOL_TRANSCODER_OBJ_IMAGE_PREVIEW_CMTS
    );

    bx_import('BxDolImageTranscoder');
    foreach($aTranscoders as $sTranscoder)
        BxDolImageTranscoder::getObjectInstance($sTranscoder)->registerHandlers();

    /**
     * Perform admin login. 
     */
    $sExistedAdminPass = db_value("SELECT `password` FROM `sys_accounts` WHERE `id`='1'");

    $aUrl = parse_url($GLOBALS['site']['url']);
    $sPath = isset($aUrl['path']) && !empty($aUrl['path']) ? $aUrl['path'] : '/';
    $sHost = '';

    $iCookieTime = 0;
    setcookie("memberID", 1, $iCookieTime, $sPath, $sHost);
    $_COOKIE['memberID'] = 1;
    setcookie("memberPassword", $sExistedAdminPass, $iCookieTime, $sPath, $sHost, false, true /* http only */);
    $_COOKIE['memberPassword'] = $sExistedAdminPass;

    return <<<EOF
<script type="text/javascript">
    window.location = "../index.php";
</script>
EOF;
}

function PageHeader($sAction = '', $sError = '') {
    global $aConf;

    $aActions = array(
        "startInstall" => "Dolphin Installation",
        "preInstall" => "Permissions",
        "step1" => "Paths",
        "step2" => "Database",
        "step3" => "Config",
        "step4" => "Cron Jobs",
        "step5" => "Permissions Reversal",
        "step6" => "Modules"
    );

    if( !strlen( $sAction ) )
        $sAction = "startInstall";

    $sActiveStyle = ($sAction == "step6") ? 'Active' : 'Inactive';

    $iCounterCurrent = 1;
    $iCounterActive     = 1;

    foreach ($aActions as $sActionKey => $sActionValue) {
        if ($sAction != $sActionKey) {
            $iCounterActive++;
        } else
            break;
    }

    if (strlen($sError))
        $iCounterActive--;

    $sSubActions = '';
    foreach ($aActions as $sActionKey => $sActionValue) {
        if ($iCounterActive == $iCounterCurrent) {
            $sSubActions .= '<div id="topActive">' . $sActionValue . '</div>';
        } elseif (($iCounterActive - $iCounterCurrent) == -1) {
            $sSubActions .= '<img src="images/active_inactive.gif" /><div id="topInactive">' . $sActionValue . '</div><img src="images/inactive_inactive.gif" />';
        } elseif (($iCounterActive - $iCounterCurrent) == 1) {
            $sSubActions .= '<div id="topInactive">' . $sActionValue . '</div><img src="images/inactive_active.gif" />';
        } else {
            $sSubActions .= '<div id="topInactive">' . $sActionValue . '</div>';
            if ($sActionKey != "step6")
                $sSubActions .= '<img src="images/inactive_inactive.gif" />';
        }
        $iCounterCurrent++;
    }

    return <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
        <head>
            <title>Dolphin Smart Community Builder Installation Script</title>
            <link href="general.css" rel="stylesheet" type="text/css" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <script src="../inc/js/functions.js" type="text/javascript" language="javascript"></script>
            <!--[if lt IE 7.]>
            <script defer type="text/javascript" src="../inc/js/pngfix.js"></script>
            <![endif]-->
        </head>
        <body>
            <div id="main">
                <div id="topMenu{$sActiveStyle}">
                    {$sSubActions}
                </div>
            <div id="header">
                <img src="images/boonex-logo.png" alt="" /></div>
            <div id="content">
EOF;
}

function PageFooter($sAction) {
    $sAdminAdd = ($sAction) ? '<div id="footer"><img src="images/dolphin_transparent.jpg" alt="" /></div>' : '';

    return <<<EOF
            </div>
        {$sAdminAdd}
        </div>
    </body>
</html>
EOF;
}

function printInstallError($sText) {
    $sRet = (strlen($sText)) ? '<div class="error">' . $sText . '</div>' : '';
    return $sRet;
}

function createTable($arr) {
    $ret = '';
    $i = '';
    foreach($arr as $key => $value) {
        $sStyleAdd = (($i%2) == 0) ? 'background-color:#ede9e9;' : 'background-color:#fff;';

        $def_exp_text = "";
        if (strlen($value['def_exp'])) {
            $funcbody = $value['def_exp'];
            $func = create_function("", $funcbody);
            $def_exp = $func();
            if (strlen($def_exp)) {
                $def_exp_text = "&nbsp;<font color=green>found</font>";
                $value['def'] = $def_exp;
            } else {
                $def_exp_text = "&nbsp;<font color=red>not found</font>";
            }
        }

        $st_err = ($error_arr[$key] == 1) ? ' style="background-color:#FFDDDD;" ' : '';

        $ret .= <<<EOF
    <tr class="cont" style="{$sStyleAdd}">
        <td>
            <div>{$value['name']}</div>
            <div>Description:</div>
            <div>Example:</div>
        </td>
        <td>
            <div><input {$st_err} size="30" name="{$key}" value="{$value['def']}" /> {$def_exp_text}</div>
            <div>{$value['desc']}</div>
            <div style="font-style:italic;">{$value['ex']}</div>
        </td>
    </tr>
EOF;
        $i ++;
    }

    return $ret;
}

function rewriteFile($sCode, $sReplace, $sFile) {
    $ret = '';
    $fs = filesize($sFile);
    $fp = fopen($sFile, 'r');
    if ($fp) {
        $fcontent = fread($fp, $fs);
        $fcontent = str_replace($sCode, $sReplace, $fcontent);
        fclose($fp);
        $fp = fopen($sFile, 'w');
        if ($fp) {
            if (fputs($fp, $fcontent)) {
                $ret .= true;
            } else {
                $ret .= false;
            }
            fclose ( $fp );
        } else {
            $ret .= false;
        }
    } else {
        $ret .= false;
    }
    return $ret;
}

function RunSQL($sAdminName, $sAdminPassword) {
    $aDbConf['host']   = $_POST['db_host'];
    $aDbConf['sock']   = $_POST['db_sock'];
    $aDbConf['port']   = $_POST['db_port'];
    $aDbConf['user']   = $_POST['db_user'];
    $aDbConf['passwd'] = $_POST['db_password'];
    $aDbConf['db']     = $_POST['db_name'];

    $aDbConf['host'] .= ( $aDbConf['port'] ? ":{$aDbConf['port']}" : '' ) . ( $aDbConf['sock'] ? ":{$aDbConf['sock']}" : '' );

    $pass = true;
    $errorMes = '';
    $filename = $_POST['sql_file'];

    $vLink = @mysql_connect($aDbConf['host'], $aDbConf['user'], $aDbConf['passwd']);

    if( !$vLink )
        return printInstallError( mysql_error() );

    if (!mysql_select_db ($aDbConf['db'], $vLink))
        return printInstallError( $aDbConf['db'] . ': ' . mysql_error() );

    mysql_query ("SET sql_mode = ''", $vLink);

    if (! ($f = fopen ( $filename, "r" )))
        return printInstallError( 'Could not open file with sql instructions:' . $filename  );

    //Begin SQL script executing
    $s_sql = "";
    while ($s = fgets ( $f, 10240)) {
        $s = trim( $s ); //Utf with BOM only

        if (! strlen($s)) continue;
        if (mb_substr($s, 0, 1) == '#') continue; //pass comments
        if (mb_substr($s, 0, 2) == '--') continue;
        if (substr($s, 0, 5) == "\xEF\xBB\xBF\x2D\x2D") continue;

        $s_sql .= $s;

        if (mb_substr($s, -1) != ';') continue;

        $res = mysql_query($s_sql, $vLink);
        if (!$res)
            $errorMes .= 'Error while executing: ' . $s_sql . '<br />' . mysql_error($vLink) . '<hr />';

        $s_sql = '';
    }

    fclose($f);

    $sAdminNameDB = DbEscape($sAdminName, false);
    $sSiteEmail = DbEscape($_POST['site_email']);
    $sSaltDB = base64_encode(substr(md5(microtime()), 2, 6));
    $sAdminPasswordDB = sha1(md5($sAdminPassword) . $sSaltDB); // encryptUserPwd
    $sAdminQuery = "
        INSERT INTO `sys_accounts`
            (`name`, `email`, `email_confirmed`, `receive_updates`, `receive_news`, `password`, `salt`, `role`, `added`)
        VALUES
            ('{$sAdminNameDB}', '{$sSiteEmail}', 1, 1, 1, '{$sAdminPasswordDB}', '{$sSaltDB}', 3, '" . time() . "')
    ";
    if (!mysql_query($sAdminQuery, $vLink)) {

        $errorMes .= 'Error while executing: ' . $sAdminQuery . '<br />' . mysql_error($vLink) . '<hr />';

    } else {

        $iAccontId = mysql_insert_id($vLink);
        $sAdminQuery = "
            INSERT INTO `sys_profiles`
                (`account_id`, `type`, `content_id`, `status`)
            VALUES
                ($iAccontId, 'system', $iAccontId, 'active')
        ";
        if (!mysql_query($sAdminQuery, $vLink))
            $errorMes .= 'Error while executing: ' . $sAdminQuery . '<br />' . mysql_error($vLink) . '<hr />';
    }
        

    $enable_gd_value = extension_loaded('gd') ? 'on' : '';
    if (!(mysql_query ("UPDATE `sys_options` SET `VALUE`='{$enable_gd_value}' WHERE `Name`='enable_gd'", $vLink)))
        $ret .= "<font color=red><i><b>Error</b>:</i> " . mysql_error($vLink) . "</font><hr>";

    $sSiteTitle = DbEscape($_POST['site_title']);
    $sSiteDesc = DbEscape($_POST['site_desc']);
    $sSiteEmailNotify = DbEscape($_POST['notify_email']);
    $sSiteEmailBugReport = DbEscape($_POST['bug_report_email']);
    if ($sSiteEmail != '' && $sSiteTitle != '' && $sSiteEmailNotify != '') {
        if (! (mysql_query("UPDATE `sys_options` SET `VALUE`='{$sSiteEmail}' WHERE `Name`='site_email'", $vLink)))
            $ret .= "<font color=red><i><b>Error</b>:</i> ".mysql_error($vLink)."</font><hr>";
        if (! (mysql_query("UPDATE `sys_options` SET `VALUE`='{$sSiteTitle}' WHERE `Name`='site_title'", $vLink)))
            $ret .= "<font color=red><i><b>Error</b>:</i> ".mysql_error($vLink)."</font><hr>";
        if (! (mysql_query("UPDATE `sys_options` SET `VALUE`='{$sSiteEmailNotify}' WHERE `Name`='site_email_notify'", $vLink)))
            $ret .= "<font color=red><i><b>Error</b>:</i> ".mysql_error($vLink)."</font><hr>";
        if (! (mysql_query("UPDATE `sys_options` SET `VALUE`='{$sSiteEmailBugReport}' WHERE `Name`='site_email_bug_report'", $vLink)))
            $ret .= "<font color=red><i><b>Error</b>:</i> ".mysql_error($vLink)."</font><hr>";
        if (! (mysql_query("UPDATE `sys_options` SET `VALUE`='{$sSiteDesc}' WHERE `Name`='MetaDescription'", $vLink)))
            $ret .= "<font color=red><i><b>Error</b>:</i> ".mysql_error($vLink)."</font><hr>";
    } else {
        $ret .= "<font color=red><i><b>Error</b>:</i> Don`t received POSTed site_email or site_title or site_email_notify</font><hr>";
    }

    mysql_close($vLink);

    $errorMes .= $ret;

    if (strlen($errorMes)) {
        return printInstallError($errorMes);
    } else {
        return 'done';
    }
//    return $ret."Truncating tables finished.<br>";
}

function DbEscape($s, $isDetectMagixQuotes = true) {
    if (get_magic_quotes_gpc() && $isDetectMagixQuotes)
        $s = stripslashes ($s);
    return mysql_real_escape_string($s);
}

function CheckSQLParams() {
    $aDbConf['host']   = $_POST['db_host'];
    $aDbConf['sock']   = $_POST['db_sock'];
    $aDbConf['port']   = $_POST['db_port'];
    $aDbConf['user']   = $_POST['db_user'];
    $aDbConf['passwd'] = $_POST['db_password'];
    $aDbConf['db']     = $_POST['db_name'];

    $aDbConf['host'] .= ( $aDbConf['port'] ? ":{$aDbConf['port']}" : '' ) . ( $aDbConf['sock'] ? ":{$aDbConf['sock']}" : '' );

    $vLink = @mysql_connect($aDbConf['host'], $aDbConf['user'], $aDbConf['passwd']);

    if (!$vLink)
        return printInstallError(mysql_error());

    if (!mysql_select_db ($aDbConf['db'], $vLink))
        return printInstallError($aDbConf['db'] . ': ' . mysql_error());

    mysql_close($vLink);
}

// set error reporting level
if (version_compare(phpversion(), "5.3.0", ">=") == 1)
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
else
  error_reporting(E_ALL & ~E_NOTICE);

?>
