<?php

$a = array('sys_simple', 'sys_html5');
 
var_dump(serialize($a));
exit;

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "utils.inc.php");

$sDomain = BX_DOL_URL_ROOT;
$aDomain = parse_url($sDomain);

$sReferrer = 'http://dolphin/studio/store.php?page=goodies';
$aReferrer = parse_url($sReferrer);

var_dump($aDomain['host'], $aReferrer['host']);
exit;

$sPathFrom = BX_DIRECTORY_PATH_ROOT . 'delete/less.php';
$sPathTo = BX_DIRECTORY_PATH_ROOT . 'delete/coppied/less.php';

bx_import('BxDolFile');
$oFile = BxDolFile::getInstance();
$bResult = $oFile->copy($sPathFrom, $sPathTo);

var_dump($bResult);
exit;

/*
if ($handle = fopen('tmp/print.txt', 'a')) {
	fwrite($handle, print_r($_POST, true));
	fclose($handle);
} 
*/   