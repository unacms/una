<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

ob_start();

require_once('./inc/header.inc.php');

$sTranscoderObject = bx_process_input(bx_get('o'));
$sHandler = bx_process_input(bx_get('h'));

bx_import('BxDolImageTranscoder');
$oTranscoder = BxDolImageTranscoder::getObjectInstance($sTranscoderObject);

if (!$oTranscoder) {
    ob_end_clean();    
    bx_transcoder_error_occured();
    exit;
}

ob_end_clean();

$sImageUrl = '';
if (!$oTranscoder->isImageReady($sHandler) && !$oTranscoder->transcode ($sHandler)) {
    bx_transcoder_error_occured();
    exit;
}

$sImageUrl = $oTranscoder->getImageUrl($sHandler);

header('HTTP/1.1 301 Moved Permanently');
header('Location: ' . $sImageUrl);
exit;

function bx_transcoder_error_occured($sMethod = 'displayPageNotFound') {
    require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
    require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");
    bx_import('BxDolLanguages');
    bx_import('BxDolTemplate');
    $oTemplate = BxDolTemplate::getInstance();
    $oTemplate->$sMethod ();
}

