
-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-RC6' WHERE (`version` = '13.0.0.RC5' OR `version` = '13.0.0-RC5') AND `name` = 'system';

