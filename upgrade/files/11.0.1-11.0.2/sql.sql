
-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '11.0.2' WHERE (`version` = '11.0.1') AND `name` = 'system';
