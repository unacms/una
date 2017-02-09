SET @sName = 'bx_forum';
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');


-- TABLE
ALTER TABLE `bx_forum_files` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_forum_photos_resized` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;


-- STORAGES & TRANSCODERS
UPDATE `sys_objects_storage` SET `engine`=@sStorageEngine WHERE `object`='bx_forum_files';
UPDATE `sys_objects_storage` SET `engine`=@sStorageEngine WHERE `object`='bx_forum_files_cmts';
UPDATE `sys_objects_storage` SET `engine`=@sStorageEngine WHERE `object`='bx_forum_photos_resized';