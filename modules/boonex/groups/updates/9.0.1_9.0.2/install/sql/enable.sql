-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_groups@modules/boonex/groups/|std-icon.svg' WHERE `name`='bx_groups';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_joined' AND `title` IN ('_bx_groups_page_block_title_favorites_of_author');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_groups_joined', 1, 'bx_groups', '_bx_groups_page_block_title_sys_favorites_of_author', '_bx_groups_page_block_title_favorites_of_author', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:15:"browse_favorite";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 1, 1, 1);


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`='bx_groups';
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_groups', 'bx_groups_favorites_track', '1', '1', '1', 'page.php?i=view-group-profile&id={object_id}', 'bx_groups_data', 'id', 'author', 'favorites', '', '');