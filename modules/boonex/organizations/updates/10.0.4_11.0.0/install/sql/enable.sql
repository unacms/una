-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_organizations' AND `title_system`='_bx_orgs_page_block_title_sys_active_profiles';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_organizations', '_bx_orgs_page_block_title_sys_active_profiles', '_bx_orgs_page_block_title_active_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:22:\"browse_active_profiles\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:15:\"gallery_wo_info\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions' AND `name`='profile-set-badges';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_organizations_view_actions', 'bx_organizations', 'profile-set-badges', '_sys_menu_item_title_system_set_badges', '_sys_menu_item_title_set_badges', 'javascript:void(0)', 'bx_menu_popup(''sys_set_badges'', window, {}, {module: ''bx_organizations'', content_id: {content_id}});', '', 'check-circle', '', '', 0, 192, 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:19:"is_badges_avaliable";}', 1, 0, 1, 40);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_all' AND `name`='profile-set-badges';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_all', 'bx_organizations', 'profile-set-badges', '_sys_menu_item_title_system_set_badges', '', '', '', '', '', '', '', 0, 192, 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:19:"is_badges_avaliable";}', 1, 0, 80);


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_organizations_administration' AND `name` IN ('audit_content', 'audit_profile');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_organizations_administration', 'single', 'audit_content', '_bx_orgs_grid_action_title_adm_audit_content', 'search', 1, 0, 3),
('bx_organizations_administration', 'single', 'audit_profile', '_bx_orgs_grid_action_title_adm_audit_profile', 'search-location', 1, 0, 4);


-- PRIVACY 
UPDATE `sys_objects_privacy` SET `default_group`='5' WHERE `object`='bx_organizations_allow_post_to';

DELETE FROM `sys_objects_privacy` WHERE `object`='bx_organizations_allow_contact_to';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `spaces`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_allow_contact_to', 'bx_organizations', 'contact', '_bx_orgs_form_profile_input_allow_contact_to', '3', '', 'bx_organizations_data', 'id', 'author', 'BxOrgsPrivacyContact', 'modules/boonex/organizations/classes/BxOrgsPrivacyContact.php');
