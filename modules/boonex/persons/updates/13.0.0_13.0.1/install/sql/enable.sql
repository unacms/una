-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_view_profile' AND `title` IN ('_bx_persons_page_block_title_cover_block', '_bx_persons_page_block_title_profile_friends_mutual');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_persons_view_profile', 0, 'bx_persons', '_bx_persons_page_block_title_sys_cover_block', '_bx_persons_page_block_title_cover_block', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:12:\"entity_cover\";}', 0, 0, 1, 0),
('bx_persons_view_profile', 0, 'bx_persons', '', '_bx_persons_page_block_title_profile_friends_mutual', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:22:\"profile_friends_mutual\";}', 0, 0, 1, 0);

DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_persons' AND `title`='_bx_persons_page_block_title_cover_block';

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:22:\"browse_active_profiles\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:12:\"unit_wo_info\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}' WHERE `module`='bx_persons' AND (`title`='_bx_persons_page_block_title_active_profiles' OR `title` LIKE '_bx_persons_page_block_title_active_profiles_%') AND `content` LIKE '%gallery_wo_info%';

DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_persons' AND `title`='_bx_persons_page_block_title_familiar_profiles';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('', 0, 'bx_persons', '_bx_persons_page_block_title_sys_familiar_profiles', '_bx_persons_page_block_title_familiar_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:24:\"browse_familiar_profiles\";s:6:\"params\";a:4:{s:10:\"connection\";s:20:\"sys_profiles_friends\";s:9:\"unit_view\";s:4:\"unit\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- MENUS
UPDATE `sys_menu_items` SET `onclick`='bx_menu_popup(''sys_set_acl_level'', window, {id:{value:''sys_acl_set_{profile_id}'', force:true}, closeOnOuterClick: false, removeOnClose: true, displayMode: ''box'', cssClass: ''''}, {profile_id: {profile_id}});' WHERE `set_name`='bx_persons_view_actions' AND `name`='profile-set-acl-level';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_snippet_meta' AND `name`='friends-mutual';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_persons_snippet_meta', 'bx_persons', 'friends-mutual', '_sys_menu_item_title_system_sm_friends_mutual', '_sys_menu_item_title_sm_friends_mutual', '', '', '', '', '', 2147483647, 0, 0, 1, 0);

UPDATE `sys_menu_items` SET `collapsed`='0' WHERE `set_name`='sys_profile_followings' AND `name`='persons';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_menu_manage_tools' AND `name`='manage-cf';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_persons_menu_manage_tools', 'bx_persons', 'manage-cf', '_bx_persons_menu_item_title_system_manage_cf', '_bx_persons_menu_item_title_manage_cf', 'javascript:void(0)', 'javascript:{js_object}.onClickManageCf({content_id});', '_self', 'filter', '', 2147483647, 1, 0, 6);


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_persons' WHERE `object`='bx_persons';


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_persons_administration' AND `name`='lock_cf';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_persons_administration', 'single', 'lock_cf', '_bx_persons_grid_action_title_adm_lock_cf', 'filter', 1, 0, 5);
