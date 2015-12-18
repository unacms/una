

-- last step is to update current version


UPDATE `sys_modules` SET `version` = '8.0.0' WHERE `version` = '8.0.0-RC' AND `name` = 'system';

