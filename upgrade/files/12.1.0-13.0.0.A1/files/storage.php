<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

require_once('./inc/header.inc.php');

$sStorageObject = bx_process_input(bx_get('o'));
$sFile = bx_process_input(bx_get('f'));
$sToken = bx_process_input(bx_get('t'));

$oStorage = BxDolStorage::getObjectInstance($sStorageObject);
// upload action implementation
if ($oStorage && bx_get('a') == 'upload' && bx_get('t')) {
    header('Content-Type: application/json; charset=utf-8');

    $iProfileId = bx_get_logged_profile_id();

    if (!($iId = $oStorage->storeFileFromForm($_FILES['file'], false, $iProfileId))) {
        echo json_encode(array('error' => '1'));
        exit;
    }

    $oStorage->afterUploadCleanup($iId, $iProfileId);

    $aFileInfo = $oStorage->getFile($iId);
    if ($aFileInfo && in_array($aFileInfo['ext'], array('jpg', 'jpeg', 'jpe', 'png'))) {
        $oTranscoder = BxDolTranscoderImage::getObjectInstance(bx_get('t'));
        $sUrl = $oTranscoder->getFileUrl($iId);
    }
    else {
        $sUrl = $oStorage->getFileUrlById($iId);
    }

    echo json_encode(array('link' => $sUrl));
    exit;
}

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

// redirect for remote storage in case if some references still pointing to local storage
$aObject = $oStorage->getObjectData();
if ('Local' != $aObject['engine']) {
    $sUrl = $oStorage->getFileUrlByRemoteId($sFile);

    if (!$sUrl) {
        $sFile = preg_replace("/\.[A-Za-z0-9]+$/", '', $sFile);
        $sUrl = $oStorage->getFileUrlByRemoteId($sFile);
    }

    // Tmp fix for storages renaming in the past
    if (!$sUrl && 'bx_posts_files' == $sStorageObject && ($oStorage = BxDolStorage::getObjectInstance('bx_posts_covers'))) 
        $sUrl = $oStorage->getFileUrlByRemoteId($sFile);

    if (!$sUrl)
        bx_storage_download_error_occured();
    else
        header("Location: " . $sUrl);
    exit;
}

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
