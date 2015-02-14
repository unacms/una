<?php

bx_import('BxDolLanguagesQuery');
bx_import('BxDolEmailTemplatesQuery');
bx_import('BxDolTemplate');
bx_import('BxDolAccountQuery');

$mixCheckResult = 'Update can not be applied';

if ('8.0.0-A10' == $this->oDb->getOne("SELECT `version` FROM `sys_modules` WHERE `name` = 'system'"))
    $mixCheckResult = true;

return $mixCheckResult;
