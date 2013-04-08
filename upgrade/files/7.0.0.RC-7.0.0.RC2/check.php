<?php

$mixCheckResult = 'Update can not be applied';

if ('' == $this->oDb->getOne("SELECT `Name` FROM `sys_options` WHERE `Name` = 'sys_tmp_version'"))
    $mixCheckResult = true;

return $mixCheckResult;

?>
