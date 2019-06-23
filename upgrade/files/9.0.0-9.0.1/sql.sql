

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.1' WHERE (`version` = '9.0.0') AND `name` = 'system';

