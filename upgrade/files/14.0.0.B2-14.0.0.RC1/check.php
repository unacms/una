<?php

$mixCheckResult = 'Update can not be applied';

if (!(PHP_VERSION_ID >= 80100)) {
    $mixCheckResult = 'This update requires a PHP version ">= 8.1.0". You are running ' . PHP_VERSION;
}
else {
    $sVer = $this->oDb->getOne("SELECT `version` FROM `sys_modules` WHERE `name` = 'system'");
    if ('14.0.0.B2' == $sVer || '14.0.0-B2' == $sVer)
        $mixCheckResult = true;
}

return $mixCheckResult;
