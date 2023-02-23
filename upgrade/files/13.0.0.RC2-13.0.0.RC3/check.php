<?php

$mixCheckResult = 'Update can not be applied';

if (!(PHP_VERSION_ID >= 70400)) {
    $mixCheckResult = 'This update requires a PHP version ">= 7.4.0". You are running ' . PHP_VERSION;
}
else {
    $sVer = $this->oDb->getOne("SELECT `version` FROM `sys_modules` WHERE `name` = 'system'");
    if ('13.0.0.RC2' == $sVer || '13.0.0-RC2' == $sVer)
        $mixCheckResult = true;
}

return $mixCheckResult;
