

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-RC8' WHERE (`version` = '9.0.0.RC7' OR `version` = '9.0.0-RC7') AND `name` = 'system';

