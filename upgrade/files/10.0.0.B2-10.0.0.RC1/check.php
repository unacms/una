<?php

$mixCheckResult = 'Update can not be applied';

$sVer = $this->oDb->getOne("SELECT `version` FROM `sys_modules` WHERE `name` = 'system'");
if ('10.0.0.B2' == $sVer || '10.0.0-B2' == $sVer)
    $mixCheckResult = true;

return $mixCheckResult;
