<?php

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');

$a = BxDolStudioInstallerUtils::getInstance()->perform('boonex/intercom/', 'install', array('auto_enable' => true));
echo json_encode($a);

