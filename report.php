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

bx_import('BxDolLanguages');

check_logged();

$sSys = isset($_REQUEST['sys']) ? bx_process_input($_REQUEST['sys']) : false;
$iObjectId = isset($_REQUEST['object_id']) ? bx_process_input($_REQUEST['object_id'], BX_DATA_INT) : 0;
$sAction = isset($_REQUEST['action']) && preg_match ('/^[A-Za-z_-]+$/', $_REQUEST['action']) ? bx_process_input($_REQUEST['action']) : '';

$oReport = BxDolReport::getObjectInstance($sSys, $iObjectId, true);

if ($oReport && $sSys && $iObjectId && $sAction) {
    header('Content-Type: text/html; charset=utf-8');
    $sMethod = 'action' . ucfirst($sAction);
    if(method_exists($oReport, $sMethod))
        echo $oReport->$sMethod();
}

/** @} */
