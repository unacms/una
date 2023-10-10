
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');





-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.1.0-RC1' WHERE (`version` = '13.1.0.B2' OR `version` = '13.1.0-B2') AND `name` = 'system';

