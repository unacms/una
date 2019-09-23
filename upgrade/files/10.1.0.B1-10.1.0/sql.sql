
-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '10.1.0' WHERE (`version` = '10.1.0.B1' OR `version` = '10.1.0-B1') AND `name` = 'system';

