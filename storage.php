<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

//ob_start();

require_once('./inc/header.inc.php');

$sStorageObject = bx_process_input(bx_get('o'));
$sFile = bx_process_input(bx_get('f'));
$sToken = bx_process_input(bx_get('t'));

$oStorage = BxDolStorage::getObjectInstance($sStorageObject);

if (!$oStorage || !method_exists($oStorage, 'download')) {
    ob_end_clean();
    bx_storage_download_error_occured();
    exit;
}

$i = strrpos($sFile, '.');
$sRemoteId = ($i !== false) ? substr($sFile, 0, $i) : $sFile;
if (!$sRemoteId) {
    ob_end_clean();
    bx_storage_download_error_occured();
    exit;
}

//ob_end_clean();

if (!$oStorage->download($sRemoteId, $sToken)) {
    $iError = $oStorage->getErrorCode();
    switch ($iError) {
        case BX_DOL_STORAGE_ERR_FILE_NOT_FOUND:
            bx_storage_download_error_occured();
            exit;
        case BX_DOL_STORAGE_ERR_PERMISSION_DENIED:
            bx_storage_download_error_occured('displayAccessDenied');
            exit;
        default:
            bx_storage_download_error_occured('displayErrorOccured');
            exit;
    }
}

function bx_storage_download_error_occured($sMethod = 'displayPageNotFound')
{
    require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

    $oTemplate = BxDolTemplate::getInstance();
    $oTemplate->$sMethod ();
}

/** @} */
