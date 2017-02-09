SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');


-- TABLE
ALTER TABLE `bx_organizations_pics` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_organizations_pics_resized` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;


-- STORAGES & TRANSCODERS
UPDATE `sys_objects_storage` SET `engine`=@sStorageEngine WHERE `object`='bx_organizations_pics';
UPDATE `sys_objects_storage` SET `engine`=@sStorageEngine WHERE `object`='bx_organizations_pics_resized';