

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0' WHERE (`version` = '9.0.0.RC13' OR `version` = '9.0.0-RC13') AND `name` = 'system';

