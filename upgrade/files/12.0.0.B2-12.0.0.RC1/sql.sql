

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '12.0.0-RC1' WHERE (`version` = '12.0.0.B2' OR `version` = '12.0.0-B2') AND `name` = 'system';

