-- PAGES
UPDATE `sys_pages_blocks` SET `active`='1' WHERE `object`='bx_persons_view_profile' AND `title` IN ('_bx_persons_page_block_title_profile_actions', '_bx_persons_page_block_title_profile_cover', '_bx_persons_page_block_title_profile_info', '_bx_persons_page_block_title_profile_friends');


DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_profile_info' AND `title`='_bx_persons_page_block_title_profile_cover';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_persons_profile_info', 1, 'bx_persons', '', '_bx_persons_page_block_title_profile_cover', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:13:\"profile_cover\";}', 0, 0, 1, 0);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_persons_page_block_title_system_profile_info', `title`='_bx_persons_page_block_title_profile_info_link', `active`='1' WHERE `object`='bx_persons_profile_info' AND `title`='_bx_persons_page_block_title_profile_info';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_profile_friends' AND `title`='_bx_persons_page_block_title_profile_cover';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_persons_profile_friends', 1, 'bx_persons', '', '_bx_persons_page_block_title_profile_cover', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:13:\"profile_cover\";}', 0, 0, 1, 0);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_persons_page_block_title_system_profile_friends', `title`='_bx_persons_page_block_title_profile_friends_link', `active`='1' WHERE `object`='bx_persons_profile_friends' AND `title`='_bx_persons_page_block_title_profile_friends';

UPDATE `sys_objects_page` SET `title_system`='_bx_persons_page_title_sys_recent', `title`='_bx_persons_page_title_recent' WHERE `object`='bx_persons_home';

DELETE FROM `sys_objects_page` WHERE `object`='bx_persons_active';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_active', '_bx_persons_page_title_sys_active', '_bx_persons_page_title_active', 'bx_persons', 5, 2147483647, 1, 'persons-active', 'page.php?i=persons-active', '', '', '', 0, 1, 0, 'BxPersonsPageBrowse', 'modules/boonex/persons/classes/BxPersonsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_active';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_persons_active', 1, 'bx_persons', '_bx_persons_page_block_title_active_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:22:\"browse_active_profiles\";s:6:"params";a:1:{i:0;b:1;}}', 0, 1, 0);

UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='sys_home' AND `title`='_bx_persons_page_block_title_latest_profiles';


-- MENU
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_homepage' AND `name`='persons-home';
SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', 'bx_persons', 'persons-home', '_bx_persons_menu_item_title_system_entries_home', '_bx_persons_menu_item_title_entries_home', 'page.php?i=persons-home', '', '', 'user col-blue3', 'bx_persons_submenu', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_persons_view_actions_popup';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_view_actions_popup', '_bx_persons_menu_title_view_profile_actions_popup', '', 'bx_persons', 16, 0, 1, 'BxPersonsMenuViewActions', 'modules/boonex/persons/classes/BxPersonsMenuViewActions.php');

UPDATE `sys_menu_items` SET `icon`='user-plus' WHERE `set_name`='bx_persons_view_actions' AND `name`='profile-friend-add';
UPDATE `sys_menu_items` SET `order`='30' WHERE `set_name`='bx_persons_view_actions' AND `name`='profile-set-acl-level';

UPDATE `sys_menu_items` SET `icon`='user-times' WHERE `set_name`='bx_persons_view_actions_more' AND `name`='profile-friend-remove';

UPDATE `sys_objects_menu` SET `template_id`='6' WHERE `object`='bx_persons_submenu';

UPDATE `sys_menu_items` SET `title_system`='_bx_persons_menu_item_title_system_entries_recent', `title`='_bx_persons_menu_item_title_entries_recent' WHERE `set_name`='bx_persons_submenu' AND `name`='persons-home';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_submenu' AND `name`='persons-active';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_persons_submenu', 'bx_persons', 'persons-active', '_bx_persons_menu_item_title_system_entries_active', '_bx_persons_menu_item_title_entries_active', 'page.php?i=persons-active', '', '', '', '', 2147483647, 1, 1, 2);

UPDATE `sys_objects_menu` SET `template_id`='6' WHERE `object`='bx_persons_view_submenu';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_persons_view_submenu_cover';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_view_submenu_cover', '_bx_persons_menu_title_view_profile_submenu_cover', 'bx_persons_view_submenu', 'bx_persons', 7, 0, 1, 'BxPersonsMenuView', 'modules/boonex/persons/classes/BxPersonsMenuView.php');

UPDATE `sys_menu_items` SET `icon`='user col-blue3' WHERE `set_name`='bx_persons_view_submenu' AND `name`='view-persons-profile';
UPDATE `sys_menu_items` SET `icon`='info-circle col-gray' WHERE `set_name`='bx_persons_view_submenu' AND `name`='persons-profile-info';
UPDATE `sys_menu_items` SET `icon`='group col-blue3' WHERE `set_name`='bx_persons_view_submenu' AND `name`='persons-profile-friends';
