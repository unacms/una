

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0' WHERE (`version` = '14.0.0.RC5' OR `version` = '14.0.0-RC5') AND `name` = 'system';

