
-- last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-B5' WHERE (`version` = '9.0.0.B4' OR `version` = '9.0.0-B4') AND `name` = 'system';

