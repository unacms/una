-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_events@modules/boonex/events/|std-icon.svg' WHERE `name`='bx_events';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_joined' AND `title` IN ('_bx_events_page_block_title_favorites_of_author');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_events_joined', 1, 'bx_events', '_bx_events_page_block_title_sys_favorites_of_author', '_bx_events_page_block_title_favorites_of_author', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:15:"browse_favorite";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 1, 1, 1);


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`='bx_events';
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_events', 'bx_events_favorites_track', '1', '1', '1', 'page.php?i=view-event-profile&id={object_id}', 'bx_events_data', 'id', 'author', 'favorites', '', '');