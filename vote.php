<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

require_once('./inc/header.inc.php');

bx_import("BxDolVoting");

$aSystems =& BxDolVoting::getSystems ();

$sSys = isset($_GET['sys']) ? bx_process_input($_GET['sys']) : false;
$iId = isset($_GET['id']) ? bx_process_input($_GET['id'], BX_DATA_INT) : 0;

if ($sSys && isset($aSystems[$sSys])) {

    if ($aSystems[$sSys]['override_class_name']) {
        require_once (BX_DIRECTORY_PATH_ROOT . $aSystems[$sSys]['override_class_file']);
        $sClassName = $aSystems[$sSys]['override_class_name'];
        new $sClassName($sSys, $iId);
    } else {
        new BxDolVoting($sSys, $iId);
    }

}

