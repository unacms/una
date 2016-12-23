<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolLanguages');

check_logged();

$sSys = isset($_REQUEST['sys']) ? bx_process_input($_REQUEST['sys']) : false;
$iObjectId = isset($_REQUEST['id']) ? bx_process_input($_REQUEST['id'], BX_DATA_INT) : 0;
$sAction = isset($_REQUEST['action']) && preg_match ('/^[A-Za-z_-]+$/', $_REQUEST['action']) ? bx_process_input($_REQUEST['action']) : '';

$oView = BxDolView::getObjectInstance($sSys, $iObjectId, true);

if ($oView && $sSys && $iObjectId && $sAction) {
    header('Content-Type: text/html; charset=utf-8');
    $sMethod = 'action' . ucfirst($sAction);
    if(method_exists($oView, $sMethod))
        echo $oView->$sMethod();
}

/** @} */
