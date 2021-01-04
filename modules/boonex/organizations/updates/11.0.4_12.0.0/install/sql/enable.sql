-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_organizations' LIMIT 1);

UPDATE `sys_options` SET `type`='select', `extra`='a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:22:"get_options_activation";}' WHERE `name`='bx_organizations_autoapproval';

DELETE FROM `sys_options` WHERE `name` IN ('bx_organizations_per_page_for_favorites_lists', 'bx_organizations_members_mode', 'bx_organizations_auto_activation_for_categories', 'bx_organizations_internal_notifications');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_organizations_per_page_for_favorites_lists', '5', @iCategId, '_bx_orgs_option_per_page_for_favorites_lists', 'digit', '', '', '', '', 17),
('bx_organizations_members_mode', '', @iCategId, '_bx_orgs_option_members_mode', 'select', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:24:"get_options_members_mode";}', '', '', '', 25),
('bx_organizations_auto_activation_for_categories', 'on', @iCategId, '_bx_orgs_option_auto_activation_for_categories', 'checkbox', '', '', '', '', 35),
('bx_organizations_internal_notifications', '', @iCategId, '_bx_orgs_option_internal_notifications', 'checkbox', '', '', '', '', 40);


-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:21:"browse_favorite_lists";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}' WHERE `object`='bx_organizations_profile_favorites' AND `title`='_bx_orgs_page_block_title_profile_favorites';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_home' AND `title`='_bx_orgs_page_block_title_multicats';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_organizations_home', 1, 'bx_organizations', '_bx_orgs_page_block_title_sys_multicats', '_bx_orgs_page_block_title_multicats', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:21:"categories_multi_list";}', 0, 0, 0, 2);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:21:"browse_favorite_lists";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}' WHERE `object`='bx_organizations_joined' AND `title`='_bx_orgs_page_block_title_favorites_of_author';

DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_favorites';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_favorites', '_bx_orgs_page_title_sys_entries_favorites', '_bx_orgs_page_title_entries_favorites', 'bx_organizations', 12, 2147483647, 1, 'organizations-favorites', 'page.php?i=organizations-favorites', '', '', '', 0, 1, 0, 'BxOrgsPageListEntry', 'modules/boonex/organizations/classes/BxOrgsPageListEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_favorites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_favorites', 2, 'bx_organizations', '', '_bx_orgs_page_block_title_favorites_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:15:"browse_favorite";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}', 0, 1, 1),
('bx_organizations_favorites', 3, 'bx_organizations', '', '_bx_orgs_page_block_title_favorites_entries_info', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:19:"favorites_list_info";}', 0, 0, 0),
('bx_organizations_favorites', 3, 'bx_organizations', '', '_bx_orgs_page_block_title_favorites_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:22:"favorites_list_actions";}', 0, 0, 1);


-- MENU
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_all' AND `name`='notes';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_all', 'bx_organizations', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', '', 0, 2147483647, '', 1, 0, 280);

UPDATE `sys_objects_menu` SET `template_id`='18' WHERE `object`='bx_organizations_view_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_menu_manage_tools' AND `name`='clear-reports';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_menu_manage_tools', 'bx_organizations', 'clear-reports', '_bx_corgs_menu_item_title_system_clear_reports', '_bx_orgs_menu_item_title_clear_reports', 'javascript:void(0)', 'javascript:{js_object}.onClickClearReports({content_id});', '_self', 'eraser', '', '', '', 2147483647, '', 1, 0, 3);


-- GRID
UPDATE `sys_grid_fields` SET `width`='40%' WHERE `object`='bx_organizations_fans' AND `name`='actions';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_organizations_fans' AND `name`='role';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_organizations_fans', 'role', '_bx_orgs_txt_role', '10%', '', 15);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_organizations_fans' AND `name` IN ('to_admins', 'from_admins', 'set_role', 'set_role_submit');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_organizations_fans', 'single', 'set_role', '_bx_orgs_txt_set_role', '', 0, 20),
('bx_organizations_fans', 'single', 'set_role_submit', '', '', 0, 21);

UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`org_name` AS `fullname`, `ta`.`email` AS `account`, `ta`.`logged` AS `last_online`, `tp`.`status` AS `status`, `tp`.`id` as `profile_id` FROM `bx_organizations_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_organizations'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_organizations_administration';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_organizations_administration' AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_organizations_administration', 'bulk', 'clear_reports', '_bx_orgs_grid_action_title_adm_clear_reports', '', 0, 1, 3);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_organizations_allow_view_favorite_list';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `spaces`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_allow_view_favorite_list', 'bx_organizations', 'view_favorite_list', '_bx_orgs_form_entry_input_allow_view_favorite_list', '3', '', 'bx_organizations_favorites_lists', 'id', 'author_id', 'BxOrgsPrivacy', 'modules/boonex/organizations/classes/BxOrgsPrivacy.php');


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_organizations_set_role';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_organizations', '_bx_orgs_email_set_role', 'bx_organizations_set_role', '_bx_orgs_email_set_role_subject', '_bx_orgs_email_set_role_body');
