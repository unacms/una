

-- last step is to update current version


UPDATE `sys_modules` SET `version` = '8.0.1' WHERE `version` = '8.0.0' AND `name` = 'system';

