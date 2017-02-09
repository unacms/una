-- TABLE
ALTER TABLE `bx_posts_files` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_posts_photos_resized` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;


-- VIEWS
UPDATE `sys_objects_view` SET `trigger_field_author`='author' WHERE `name`='bx_posts';


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`='bx_posts';
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_posts', '1', '1', 'page.php?i=view-post&id={object_id}', 'bx_posts_posts', 'id', 'author', 'featured', '', '');