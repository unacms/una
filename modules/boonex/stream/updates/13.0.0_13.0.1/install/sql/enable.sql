-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_stream_streaming' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_stream_recordings_url';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_stream_recordings_url', '', @iCategId, '_bx_stream_option_recordings_url', 'digit', '', '', '', 30);

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_stream_engine_ome' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_stream_server_ome_recordings_source';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_stream_server_ome_recordings_source', '', @iCategId, '_bx_stream_option_ome_recordings_source', 'digit', '', '', '', 30);

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_stream_engine_nginx' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_stream_server_nginx_auth', 'bx_stream_server_nginx_recording_base_path');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_stream_server_nginx_auth', 'on', @iCategId, '_bx_stream_option_nginx_auth', 'checkbox', '', '', '', 12),
('bx_stream_server_nginx_recording_base_path', '', @iCategId, '_bx_stream_option_nginx_recording_base_path', 'digit', '', '', '', 20);

UPDATE `sys_options` SET `value`='https://{host}/stat' WHERE `name`='bx_stream_server_nginx_stats_url';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_stream_view_entry' AND `title`='_bx_stream_page_block_title_entry_recordings';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_stream_view_entry', 3, 'bx_stream', '', '_bx_stream_page_block_title_entry_recordings', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_stream\";s:6:\"method\";s:17:\"stream_recordings\";}', 0, 0, 1, 5);



-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_stream_view_actions' AND `name` IN ('social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_stream_view_actions', 'bx_stream', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, '', 1, 0, 300);


-- ACL
DELETE FROM `sys_acl_actions` WHERE `Module`='bx_stream' AND `Name`='record';
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_stream', 'record', NULL, '_bx_stream_acl_action_record', '', 1, 3);


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_stream' WHERE `object`='bx_stream';


-- CATEGORY
UPDATE `sys_objects_category` SET `module`='bx_stream' WHERE `object`='bx_stream_cats';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_stream_recordings';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('bx_stream_recordings', 'Sql', 'SELECT `bx_stream_recordings`.* FROM `bx_stream_recordings` INNER JOIN `sys_storage_ghosts` USING (`id`) WHERE `sys_storage_ghosts`.`object` = "bx_stream_recordings"', 'bx_stream_recordings', 'id', 'added', '', '', 16, NULL, 'start', '', '', '', 'auto', 'size,added', '', 2147483647, 0, 'BxStrmGridRecordings', 'modules/boonex/stream/classes/BxStrmGridRecordings.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_stream_recordings';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_stream_recordings', 'size', '_bx_stream_field_size', '20%', 0, 0, '', 1),
('bx_stream_recordings', 'added', '_bx_stream_field_added', '20%', 0, 0, '', 2),
('bx_stream_recordings', 'actions', '_sys_actions', '60%', 0, 0, '', 3);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_stream_recordings';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_stream_recordings', 'single', 'download', '', 'download', 0, 1),
('bx_stream_recordings', 'single', 'publish', '', 'upload', 1, 2),
('bx_stream_recordings', 'single', 'delete', '', 'remove',  1, 3);
