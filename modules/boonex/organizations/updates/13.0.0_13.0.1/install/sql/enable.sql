-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_view_profile' AND `title` IN ('_bx_orgs_page_block_title_cover_block', '_bx_orgs_page_block_title_profile_friends_mutual');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_organizations_view_profile', 0, 'bx_organizations', '_bx_orgs_page_block_title_sys_cover_block', '_bx_orgs_page_block_title_cover_block', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:12:\"entity_cover\";}', 0, 0, 1, 0),
('bx_organizations_view_profile', 0, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_friends_mutual', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:22:\"profile_friends_mutual\";}', 0, 0, 1, 0);


DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_organizations' AND `title`='_bx_orgs_page_block_title_cover_block';

DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_organizations' AND `title`='_bx_orgs_page_block_title_familiar_profiles';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_organizations', '_bx_orgs_page_block_title_sys_familiar_profiles', '_bx_orgs_page_block_title_familiar_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:24:\"browse_familiar_profiles\";s:6:\"params\";a:4:{s:10:\"connection\";s:20:\"sys_profiles_friends\";s:9:\"unit_view\";s:4:\"unit\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- MENUS
UPDATE `sys_menu_items` SET `onclick`='bx_menu_popup(''sys_set_acl_level'', window, {id:{value:''sys_acl_set_{profile_id}'', force:true}, closeOnOuterClick: false, removeOnClose: true, displayMode: ''box'', cssClass: ''''}, {profile_id: {profile_id}});' WHERE `set_name`='bx_organizations_view_actions' AND `name`='profile-set-acl-level';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_all' AND `name` IN ('social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_all', 'bx_organizations', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, '', 1, 0, 300);


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='friends-mutual';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_organizations_snippet_meta', 'bx_organizations', 'friends-mutual', '_sys_menu_item_title_system_sm_friends_mutual', '_sys_menu_item_title_sm_friends_mutual', '', '', '', '', '', 2147483647, '', 0, 0, 1, 0);

UPDATE `sys_menu_items` SET `collapsed`='0' WHERE `set_name`='sys_profile_followings' AND `name`='organizations';


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_organizations' WHERE `object`='bx_organizations';


-- CATEGORY
UPDATE `sys_objects_category` SET `module`='bx_organizations' WHERE `object`='bx_organizations_cats';


-- CONNECTIONS
UPDATE `sys_objects_connection` SET `profile_initiator`='1', `profile_content`='1' WHERE `object`='bx_organizations_fans';
