-- SETTINGS
DELETE FROM `sys_options` WHERE `name`='bx_posts_labels';


-- PAGE
UPDATE `sys_pages_blocks` SET `cell_id`='2', `active`='1', `order`='4' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_attachments';
UPDATE `sys_pages_blocks` SET `order`='5' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_comments';
UPDATE `sys_pages_blocks` SET `order`='5' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_featured_entries_view_extended';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_polls';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_view_entry', 3, 'bx_posts', '', '_bx_posts_page_block_title_entry_polls', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:12:\"entity_polls\";}', 0, 0, 1, 4);


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_posts_entry_attachments';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_entry_attachments', '_bx_posts_menu_title_entry_attachments', 'bx_posts_entry_attachments', 'bx_posts', 23, 0, 1, 'BxPostsMenuAttachments', 'modules/boonex/posts/classes/BxPostsMenuAttachments.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_posts_entry_attachments';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_posts_entry_attachments', 'bx_posts', '_bx_posts_menu_set_title_entry_attachments', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_posts_entry_attachments';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_posts_entry_attachments', 'bx_posts', 'photo_simple', '_bx_posts_menu_item_title_system_cpa_photo_simple', '_bx_posts_menu_item_title_cpa_photo_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_photos_simple}.showUploaderForm();', '_self', 'camera', '', '', 2147483647, '', 0, 0, 1, 1),
('bx_posts_entry_attachments', 'bx_posts', 'photo_html5', '_bx_posts_menu_item_title_system_cpa_photo_html5', '_bx_posts_menu_item_title_cpa_photo_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_photos_html5}.showUploaderForm();', '_self', 'camera', '', '', 2147483647, '', 1, 0, 1, 2),
('bx_posts_entry_attachments', 'bx_posts', 'video_simple', '_bx_posts_menu_item_title_system_cpa_video_simple', '_bx_posts_menu_item_title_cpa_video_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_simple}.showUploaderForm();', '_self', 'video', '', '', 2147483647, '', 0, 0, 1, 3),
('bx_posts_entry_attachments', 'bx_posts', 'video_html5', '_bx_posts_menu_item_title_system_cpa_video_html5', '_bx_posts_menu_item_title_cpa_video_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_html5}.showUploaderForm();', '_self', 'video', '', '', 2147483647, '', 1, 0, 1, 4),
('bx_posts_entry_attachments', 'bx_posts', 'file_simple', '_bx_posts_menu_item_title_system_cpa_file_simple', '_bx_posts_menu_item_title_cpa_file_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_files_simple}.showUploaderForm();', '_self', 'file', '', '', 2147483647, '', 0, 0, 1, 5),
('bx_posts_entry_attachments', 'bx_posts', 'file_html5', '_bx_posts_menu_item_title_system_cpa_file_html5', '_bx_posts_menu_item_title_cpa_file_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_files_html5}.showUploaderForm();', '_self', 'file', '', '', 2147483647, '', 1, 0, 1, 6),
('bx_posts_entry_attachments', 'bx_posts', 'poll', '_bx_posts_menu_item_title_system_cpa_poll', '_bx_posts_menu_item_title_cpa_poll', 'javascript:void(0)', 'javascript:{js_object}.showPollForm(this);', '_self', 'tasks', '', '', 2147483647, '', 1, 0, 1, 7);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_posts_view_actions' AND `name` IN ('reaction', 'social-sharing-googleplus');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_posts_view_actions', 'bx_posts', 'reaction', '_sys_menu_item_title_system_va_reaction', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 225);

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_posts_view_actions' AND `name`='vote';

UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='posts-administration' AND `icon`='';


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_posts_photos_simple', 'bx_posts_photos_html5', 'bx_posts_videos_simple', 'bx_posts_videos_html5', 'bx_posts_files_simple', 'bx_posts_files_html5');
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_posts_photos_simple', 1, 'BxPostsUploaderSimpleAttach', 'modules/boonex/posts/classes/BxPostsUploaderSimpleAttach.php'),
('bx_posts_photos_html5', 1, 'BxPostsUploaderHTML5Attach', 'modules/boonex/posts/classes/BxPostsUploaderHTML5Attach.php'),
('bx_posts_videos_simple', 1, 'BxPostsUploaderSimpleAttach', 'modules/boonex/posts/classes/BxPostsUploaderSimpleAttach.php'),
('bx_posts_videos_html5', 1, 'BxPostsUploaderHTML5Attach', 'modules/boonex/posts/classes/BxPostsUploaderHTML5Attach.php'),
('bx_posts_files_simple', 1, 'BxPostsUploaderSimpleAttach', 'modules/boonex/posts/classes/BxPostsUploaderSimpleAttach.php'),
('bx_posts_files_html5', 1, 'BxPostsUploaderHTML5Attach', 'modules/boonex/posts/classes/BxPostsUploaderHTML5Attach.php');


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_posts' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='bx_posts_videos_mp4' AND `action`='transcoded' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_posts_videos_mp4', 'transcoded', @iHandler);
