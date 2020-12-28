-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_ads_author' AND `title`='_bx_ads_page_block_title_entries_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_ads_author', 1, 'bx_ads', '_bx_ads_page_block_title_sys_entries_in_context', '_bx_ads_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:6:"bx_ads";s:6:"method";s:14:"browse_context";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";i:0;a:1:{s:13:"empty_message";b:0;}}}', 0, 0, 1, 4);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_ads_page_block_title_sys_my_entries', `title`='_bx_ads_page_block_title_my_entries' WHERE `module`='bx_ads' AND `title`='_bx_ads_page_block_title_my_entries';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_entry_attachments' AND `name`='record_video';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_ads_entry_attachments', 'bx_ads', 'record_video', '_bx_ads_menu_item_title_system_cpa_video_record', '_bx_ads_menu_item_title_cpa_video_record', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_record_video}.showUploaderForm();', '_self', 'fas circle', '', '', '', 2147483647, '', 1, 0, 1, 5);


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_ads_record_video', 'bx_ads_videos_record_video');
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_ads_record_video', 1, 'BxAdsUploaderRecordVideo', 'modules/boonex/ads/classes/BxAdsUploaderRecordVideo.php'),
('bx_ads_videos_record_video', 1, 'BxAdsUploaderRecordVideoAttach', 'modules/boonex/ads/classes/BxAdsUploaderRecordVideoAttach.php');
