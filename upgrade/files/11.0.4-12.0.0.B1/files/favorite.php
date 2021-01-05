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
$iObjectId = isset($_REQUEST['object_id']) ? bx_process_input($_REQUEST['object_id'], BX_DATA_INT) : 0;
$sAction = isset($_REQUEST['action']) && preg_match ('/^[A-Za-z_-]+$/', $_REQUEST['action']) ? bx_process_input($_REQUEST['action']) : '';

$oFavorite = BxDolFavorite::getObjectInstance($sSys, $iObjectId, true);

if ($oFavorite && $sSys && $sAction) {
    header('Content-Type: text/html; charset=utf-8');
    $sMethod = 'action' . ucfirst($sAction);
    if(method_exists($oFavorite, $sMethod))
        echo $oFavorite->$sMethod();
}

/** @} */
