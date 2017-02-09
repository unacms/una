SET @sName = 'bx_forum';


-- TABLE
ALTER TABLE `bx_forum_files` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_forum_photos_resized` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;