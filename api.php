<?php

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "profiles.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

define('BX_API', true);

header('Content-Type: application/json');

bx_api_check_access();

$aRequest = isset($_GET['r']) ? explode('/', $_GET['r']) : [];

$a = ['sModule', 'sMethod', 'sClass'];
foreach ($aRequest as $i => $v)
    if (isset($a[$i]) && preg_match('/^[A-Za-z0-9_-]+$/', $v))
        ${$a[$i]} = $v;

if (!isset($sClass))
    $sClass = 'Module';

foreach ($a as $v) {
    if (!isset($$v)) {
        header('HTTP/1.0 404 Not Found');
        header('Status: 404 Not Found');
        echo json_encode(['status' => 404, 'error' => _t("_sys_request_page_not_found_cpt")]);
        exit;
    }
}

if (!BxDolRequest::serviceExists($sModule, $sMethod, $sClass)) {
    header('HTTP/1.0 404 Not Found');
    header('Status: 404 Not Found');
    echo json_encode(['status' => 404, 'error' => _t("_sys_request_page_not_found_cpt")]);
    exit;
}

if (!($aParams = bx_get('params')))
    $aParams = array();
elseif (is_string($aParams) && preg_match('/^\[.*\]$/', $aParams))
    $aParams = @json_decode($aParams);

if (!is_array($aParams))
    $aParams = array($aParams);

$mixedRet = BxDolService::call($sModule, $sMethod, $aParams, $sClass);

if (is_array($mixedRet) && isset($mixedRet['error'])) { 
    header('HTTP/1.0 500 Internal Server Error');
    header('Status: 500 Internal Server Error');
    $a = [
        'status' => 500, 
        'error' => isset($mixedRet['desc']) ? $mixedRet['desc'] : $mixedRet['error']
    ];
    if (isset($mixedRet['code']))
        $a['code'] = $mixedRet['code'];
    echo json_encode($a);
    exit;
}

echo json_encode([
    'status' => 200,
    'module' => $sModule,
    'method' => $sMethod,
    'params' => $aParams,
    'data' => $mixedRet,
]);
