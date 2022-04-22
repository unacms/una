-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_videos_context' AND `title`='_bx_videos_page_block_title_multi_categories_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_videos_context', 1, 'bx_videos', '_bx_videos_page_block_title_sys_multi_categories_in_context', '_bx_videos_page_block_title_multi_categories_in_context', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_videos\";s:6:\"method\";s:29:\"categories_multi_list_context\";}', 0, 0, 0, 2);

-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_videos_view_actions' AND `name` IN ('social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_videos_view_actions', 'bx_videos', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, 1, 0, 300);

UPDATE `sys_menu_items` SET `name`='profile-stats-my-videos', `link`='page.php?i=videos-author&profile_id={member_id}' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-videos';


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_videos' WHERE `object`='bx_videos';


-- CATEGORY
UPDATE `sys_objects_category` SET `module`='bx_videos' WHERE `object`='bx_videos_cats';
