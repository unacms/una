<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolAcl');
bx_import('BxDolLanguages');

check_logged();

$sSys = isset($_REQUEST['sys']) ? bx_process_input($_REQUEST['sys']) : false;
$iObjectId = isset($_REQUEST['id']) ? bx_process_input($_REQUEST['id'], BX_DATA_INT) : 0;
$sAction = isset($_REQUEST['action']) && preg_match ('/^[A-Za-z_-]+$/', $_REQUEST['action']) ? bx_process_input($_REQUEST['action']) : '';

bx_import("BxDolVote");
$oVote = BxDolVote::getObjectInstance($sSys, $iObjectId, true);

if ($oVote && $sSys && $iObjectId && $sAction) {
    header('Content-Type: text/html; charset=utf-8');
    $sMethod = 'action' . ucfirst($sAction);
    if(method_exists($oVote, $sMethod))
        echo $oVote->$sMethod();
}

/** @} */
