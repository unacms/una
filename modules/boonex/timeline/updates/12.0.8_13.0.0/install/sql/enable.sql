SET @sName = 'bx_timeline';


-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='5' WHERE `object`='bx_timeline_item_brief';
UPDATE `sys_pages_blocks` SET `cell_id`='0' WHERE `object`='bx_timeline_item_brief' AND `title` IN ('_bx_timeline_page_block_title_item_info', '_bx_timeline_page_block_title_item_comments');


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_share' AND `name`='item-repost-to';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('bx_timeline_menu_item_share', 'bx_timeline', 'item-repost-to', '_bx_timeline_menu_item_title_system_item_repost_to', '_bx_timeline_menu_item_title_item_repost_to', 'javascript:void(0)', 'javascript:{repost_to_onclick}', '_self', 'redo', '', '', '', 2147483647, '', 1, 0, 2);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_post_attachments' AND `name` IN ('add-file-simple', 'add-file-html5');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-file-simple', '_bx_timeline_menu_item_title_system_add_file_simple', '_bx_timeline_menu_item_title_add_file', 'javascript:void(0)', 'javascript:{js_object_add_file_simple}.showUploaderForm();', '_self', 'file', '', '', '', 2147483647, '', 0, 0, 1, 7),
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-file-html5', '_bx_timeline_menu_item_title_system_add_file_html5', '_bx_timeline_menu_item_title_add_file', 'javascript:void(0)', 'javascript:{js_object_add_file_html5}.showUploaderForm();', '_self', 'file', '', '', '', 2147483647, '', 1, 0, 1, 8);


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_card' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_videos_preload';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_videos_preload', 'auto', @iCategId, '_bx_timeline_option_videos_preload', 'select', '', '', '', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:26:"get_options_videos_preload";}', 10);


-- PRELOADER
UPDATE `sys_preloader` SET `content`='modules/boonex/timeline/js/|modernizr.min.js' WHERE `module`='bx_timeline' AND `type`='js_system' AND `content`='modernizr.min.js';