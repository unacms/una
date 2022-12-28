<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
* Note: This file is intended to be publicly accessible.
*/

require_once('./inc/header.inc.php');

$aContent = [
    'name' => '',
    'short_name' => '',
    'start_url' => '/',
    'display' => 'standalone',
];

$sPattern = '/^[A-Za-z0-9\.\-]+$/';

if(!empty($_GET['bx_name']) && preg_match($sPattern, $_GET['bx_name']))
    $aContent['name'] = $_GET['bx_name'];

if(!empty($_GET['bx_short_name']) && preg_match($sPattern, $_GET['bx_short_name']))
    $aContent['short_name'] = $_GET['bx_short_name'];
else
    $aContent['short_name'] = $aContent['name'];

if(isLogged())
    $aContent['gcm_sender_id'] = '482941778795';

/*
 * Android device icons
 */

$aAdi = [];
if(($iId = (int)getParam('sys_site_icon_android')) != 0) {
    $oTranscoder = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_ANDROID);

    $sSizes = '192x192';
    if(($aTranscoderParams = $oTranscoder->getFilterParams('Resize')) !== false)
        $sSizes = $aTranscoderParams['w'] . 'x' . $aTranscoderParams['h'];

    $aAdi[] = [
        'src' => $oTranscoder->getFileUrl($iId), 
        'type' => $oTranscoder->getFileMimeType($iId), 
        'sizes' => $sSizes
    ];
}

if(($iId = (int)getParam('sys_site_icon_android_splash')) != 0) {
    $oTranscoder = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_ANDROID_SPLASH);

    $sSizes = '512x512';
    if(($aTranscoderParams = $oTranscoder->getFilterParams('Resize')) !== false)
        $sSizes = $aTranscoderParams['w'] . 'x' . $aTranscoderParams['h'];

    $aAdi[] = [
        'src' => $oTranscoder->getFileUrl($iId), 
        'type' => $oTranscoder->getFileMimeType($iId), 
        'sizes' => $sSizes
    ];
}

if(!empty($aAdi) && is_array($aAdi))
    $aContent['icons'] = $aAdi;

header("Content-Type: application/json");
header("X-Robots-Tag: none");
echo json_encode($aContent);
?>