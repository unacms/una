<?php

$mixCheckResult = 'Update can not be applied';

$sMysqlVer = $this->oDb->getOne("SELECT VERSION()");
if (version_compare($sMysqlVer, '5.5.3', '<')) {
    $mixCheckResult = "This version requires MySQL 5.5.3 or higher";
}
else {
    $sVer = $this->oDb->getOne("SELECT `version` FROM `sys_modules` WHERE `name` = 'system'");
    if ('9.0.0.RC4' == $sVer || '9.0.0-RC4' == $sVer)
        $mixCheckResult = true;
}

return $mixCheckResult;
