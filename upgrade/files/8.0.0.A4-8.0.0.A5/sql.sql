
-- last step is to update current version

UPDATE `sys_modules` SET `version` = '8.0.0-A5' WHERE `version` = '8.0.0-A4' AND `name` = 'system';

