-- PAGES:
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_view_profile' AND `title` IN ('_bx_timeline_page_block_title_post_profile', '_bx_timeline_page_block_title_view_profile');

SET @iPBCellProfile = 4;
DELETE FROM `sys_pages_blocks` WHERE `object`='trigger_page_persons_view_entry' AND `title` IN ('_bx_timeline_page_block_title_post_profile_persons', '_bx_timeline_page_block_title_view_profile_persons');
DELETE FROM `sys_pages_blocks` WHERE `object`='trigger_page_organizations_view_entry' AND `title` IN ('_bx_timeline_page_block_title_post_profile_organizations', '_bx_timeline_page_block_title_view_profile_organizations');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('trigger_page_persons_view_entry', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_post_profile_persons', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_post_profile";s:6:"params";a:1:{i:0;s:10:"bx_persons";}}', 0, 0, 0),
('trigger_page_persons_view_entry', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_view_profile_persons', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_profile";s:6:"params";a:1:{i:0;s:10:"bx_persons";}}', 0, 0, 0),

('trigger_page_organizations_view_entry', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_post_profile_organizations', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_post_profile";s:6:"params";a:1:{i:0;s:16:"bx_organizations";}}', 0, 0, 0),
('trigger_page_organizations_view_entry', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_view_profile_organizations', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_profile";s:6:"params";a:1:{i:0;s:16:"bx_organizations";}}', 0, 0, 0);


-- MENUS:
DELETE FROM `sys_objects_menu` WHERE `object`='bx_timeline_menu_post_attachments' LIMIT 1;
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_menu_post_attachments', '_bx_timeline_menu_title_post_attachments', 'bx_timeline_menu_post_attachments', 'bx_timeline', 9, 0, 1, '', '');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_timeline_menu_post_attachments' LIMIT 1;
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_timeline_menu_post_attachments', 'bx_timeline', '_bx_timeline_menu_set_title_post_attachments', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_post_attachments';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-link', '_bx_timeline_menu_item_title_system_add_link', '_bx_timeline_menu_item_title_add_link', 'javascript:void(0)', 'javascript:{js_object}.showAttachLink(this);', '_self', 'link', '', '', 2147483647, 1, 0, 1, 1),
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-photo', '_bx_timeline_menu_item_title_system_add_photo', '_bx_timeline_menu_item_title_add_photo', 'javascript:void(0)', 'javascript:{js_object_uploader_photo}.showUploaderForm();', '_self', 'camera', '', '', 2147483647, 1, 0, 1, 2),
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-video', '_bx_timeline_menu_item_title_system_add_video', '_bx_timeline_menu_item_title_add_video', 'javascript:void(0)', 'javascript:{js_object_uploader_video}.showUploaderForm();', '_self', 'video-camera', '', '', 2147483647, 1, 0, 1, 3);


-- OPTIONS:
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_events_per_page' LIMIT 1;
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_events_per_page', '20', @iCategId, '_bx_timeline_option_events_per_page', 'digit', '', '', '', '', 5);


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName`='bx_timeline' LIMIT 1;
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `ClassName`, `ClassPath`) VALUES
('bx_timeline', '_bx_timeline', 'BxTimelineSearchResult', 'modules/boonex/timeline/classes/BxTimelineSearchResult.php');


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object`='bx_timeline' LIMIT 1;
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline', 'bx_timeline_meta_keywords', 'bx_timeline_meta_locations', '', '', '');