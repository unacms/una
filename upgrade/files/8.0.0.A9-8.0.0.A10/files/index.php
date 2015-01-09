<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

if (!file_exists("./inc/header.inc.php")) {
    // this is dynamic page - send headers to not cache this page
    $now = gmdate('D, d M Y H:i:s') . ' GMT';
    header("Expires: $now");
    header("Last-Modified: $now");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");

    echo "It seems to be script is <b>not</b> installed.<br />\n";
    if ( file_exists( "install/index.php" ) ) {
        echo "Please, wait. Redirecting you to installation form...<br />\n";
        echo "<script language=\"javascript\">location.href = 'install/index.php';</script>\n";
    }
    exit;
}

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "profiles.inc.php");

/*
if(!isLogged()) {
    require_once("./splash.php");
    exit;
}
*/

$_GET['i'] = 'home';
require_once("./page.php");

/** @} */
