
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.1.0' WHERE (`version` = '13.1.0.RC3' OR `version` = '13.1.0-RC3') AND `name` = 'system';

