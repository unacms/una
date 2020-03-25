
-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '11.0.0' WHERE (`version` = '11.0.0.RC2' OR `version` = '11.0.0-RC2') AND `name` = 'system';
