<?php

$aPathInfo = pathinfo(__FILE__);
$sHeaderPath = $aPathInfo['dirname'] . '/../inc/header.inc.php';
if (!file_exists($sHeaderPath))
    die("Script is not installed\n");
require_once($sHeaderPath);

