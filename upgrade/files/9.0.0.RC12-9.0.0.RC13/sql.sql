

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-RC13' WHERE (`version` = '9.0.0.RC12' OR `version` = '9.0.0-RC12') AND `name` = 'system';

