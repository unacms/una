<?php

$mixCheckResult = 'Update can not be applied';

$sVer = $this->oDb->getOne("SELECT `version` FROM `sys_modules` WHERE `name` = 'system'");
if ('10.1.0.B1' == $sVer || '10.1.0-B1' == $sVer)
    $mixCheckResult = true;

return $mixCheckResult;
