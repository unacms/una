-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_groups' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_groups_per_page_for_favorites_lists', 'bx_groups_members_mode', 'bx_groups_internal_notifications');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_groups_per_page_for_favorites_lists', '5', @iCategId, '_bx_groups_option_per_page_for_favorites_lists', 'digit', '', '', '', '', 17),
('bx_groups_members_mode', '', @iCategId, '_bx_groups_option_members_mode', 'select', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:24:"get_options_members_mode";}', '', '', '', 40),
('bx_groups_internal_notifications', '', @iCategId, '_bx_groups_option_internal_notifications', 'checkbox', '', '', '', '', 50);

-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_groups_join_profile';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_join_profile', 'join-group-profile', '_bx_groups_page_title_sys_join_profile', '_bx_groups_page_title_join_profile', 'bx_groups', 5, 2147483647, 1, 'page.php?i=join-group-profile', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_join_profile';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_join_profile', 1, 'bx_groups', '', '_bx_groups_page_block_title_join_profile', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:11:"entity_join";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_groups_profile_pricing';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_profile_pricing', 'edit-group-pricing', '_bx_groups_page_title_sys_profile_pricing', '_bx_groups_page_title_profile_pricing', 'bx_groups', 5, 2147483647, 1, 'page.php?i=edit-group-pricing', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_profile_pricing';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_profile_pricing', 1, 'bx_groups', '_bx_groups_page_block_title_system_profile_pricing', '_bx_groups_page_block_title_profile_pricing_link', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:14:"entity_pricing";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 1, 1);

UPDATE `sys_pages_blocks` SET `title`='_bx_groups_page_block_title_sys_entries_of_author', `content`='a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:21:"browse_favorite_lists";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}', `copyable`='1', `active`='1' WHERE `object`='bx_groups_joined' AND `title_system`='_bx_groups_page_block_title_sys_entries_of_author';

DELETE FROM `sys_objects_page` WHERE `object`='bx_groups_favorites';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_favorites', '_bx_groups_page_title_sys_entries_favorites', '_bx_groups_page_title_entries_favorites', 'bx_groups', 12, 2147483647, 1, 'groups-favorites', 'page.php?i=groups-favorites', '', '', '', 0, 1, 0, 'BxGroupsPageListEntry', 'modules/boonex/groups/classes/BxGroupsPageListEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_favorites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_favorites', 2, 'bx_groups', '', '_bx_groups_page_block_title_favorites_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:15:"browse_favorite";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}', 0, 1, 1),
('bx_groups_favorites', 3, 'bx_groups', '', '_bx_groups_page_block_title_favorites_entries_info', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:19:"favorites_list_info";}', 0, 0, 0),
('bx_groups_favorites', 3, 'bx_groups', '', '_bx_groups_page_block_title_favorites_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:22:"favorites_list_actions";}', 0, 0, 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions' AND `name`='join-group-profile';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_groups_view_actions', 'bx_groups', 'join-group-profile', '_bx_groups_menu_item_title_system_pay_and_join', '_bx_groups_menu_item_title_pay_and_join', 'page.php?i=join-group-profile&profile_id={profile_id}', '', '', 'sign-in-alt', '', '', '', 0, 2147483647, 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:22:"is_paid_join_avaliable";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 1, 0, 0, 5);

UPDATE `sys_menu_items` SET `visibility_custom`='a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:22:"is_free_join_avaliable";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}' WHERE `set_name`='bx_groups_view_actions' AND `name`='profile-fan-add';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_more' AND `name`='edit-group-pricing';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_actions_more', 'bx_groups', 'edit-group-pricing', '_bx_groups_menu_item_title_system_edit_pricing', '_bx_groups_menu_item_title_edit_pricing', 'page.php?i=edit-group-pricing&profile_id={profile_id}', '', '', 'money-check-alt', '', '', '', 2147483647, 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:20:"is_pricing_avaliable";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 1, 0, 41);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_all' AND `name` IN ('join-group-profile', 'notes', 'edit-group-pricing');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_actions_all', 'bx_groups', 'join-group-profile', '_bx_groups_menu_item_title_system_become_fan_paid', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 5),
('bx_groups_view_actions_all', 'bx_groups', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', '', 0, 2147483647, '', 1, 0, 280),
('bx_groups_view_actions_all', 'bx_groups', 'edit-group-pricing', '_bx_groups_menu_item_title_system_profile_pricing', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 415);

UPDATE `sys_objects_menu` SET `template_id`='18' WHERE `object`='bx_groups_view_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_snippet_meta' AND `name`='join-paid';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_groups_snippet_meta', 'bx_groups', 'join-paid', '_sys_menu_item_title_system_sm_join_paid', '_sys_menu_item_title_sm_join_paid', '', '', '', '', '', '', '', 2147483647, 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:33:"is_paid_join_avaliable_by_content";s:6:"params";a:1:{i:0;s:12:"{content_id}";}}', 1, 0, 1, 9);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_menu_manage_tools' AND `name`='clear-reports';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_groups_menu_manage_tools', 'bx_groups', 'clear-reports', '_bx_groups_menu_item_title_system_clear_reports', '_bx_groups_menu_item_title_clear_reports', 'javascript:void(0)', 'javascript:{js_object}.onClickClearReports({content_id});', '_self', 'eraser', '', '', '', 2147483647, '', 1, 0, 3);


-- GRIDS
DELETE FROM `sys_grid_fields` WHERE `object`='bx_groups_fans' AND `name` IN ('role', 'role_added', 'role_expired');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_groups_fans', 'role', '_bx_groups_txt_role', '10%', '', 15),
('bx_groups_fans', 'role_added', '_bx_groups_txt_role_added', '10%', '', 16),
('bx_groups_fans', 'role_expired', '_bx_groups_txt_role_expired', '10%', '', 17);

UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_groups_fans' AND `name`='actions';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_groups_fans' AND `name` IN ('to_admins', 'from_admins', 'set_role', 'set_role_submit');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_groups_fans', 'single', 'set_role', '_bx_groups_txt_set_role', '', 0, 20),
('bx_groups_fans', 'single', 'set_role_submit', '', '', 0, 21);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_groups_administration' AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_groups_administration', 'bulk', 'clear_reports', '_bx_groups_grid_action_title_adm_clear_reports', '', 0, 1, 4);

DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_groups_prices_manage', 'bx_groups_prices_view');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_prices_manage', 'Sql', 'SELECT * FROM `bx_groups_prices` WHERE 1 ', 'bx_groups_prices', 'id', 'order', '', '', 100, NULL, 'start', '', 'period,period_unit,price', '', 'like', '', '', 2147483647, 'BxGroupsGridPricesManage', 'modules/boonex/groups/classes/BxGroupsGridPricesManage.php'),
('bx_groups_prices_view', 'Sql', 'SELECT * FROM `bx_groups_prices` WHERE 1 ', 'bx_groups_prices', 'id', 'order', '', '', 100, NULL, 'start', '', 'period,period_unit,price', '', 'like', '', '', 2147483647, 'BxGroupsGridPricesView', 'modules/boonex/groups/classes/BxGroupsGridPricesView.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_groups_prices_manage', 'bx_groups_prices_view');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_groups_prices_manage', 'checkbox', '_sys_select', '1%', 0, 0, '', 1),
('bx_groups_prices_manage', 'order', '', '1%', 0, 0, '', 2),
('bx_groups_prices_manage', 'name', '_bx_groups_grid_column_title_name', '38%', 0, 32, '', 3),
('bx_groups_prices_manage', 'price', '_bx_groups_grid_column_title_price', '20%', 0, 16, '', 4),
('bx_groups_prices_manage', 'period', '_bx_groups_grid_column_title_period', '20%', 0, 16, '', 5),
('bx_groups_prices_manage', 'actions', '', '20%', 0, 0, '', 6),

('bx_groups_prices_view', 'role_id', '_bx_groups_grid_column_title_role_id', '40%', 0, 32, '', 1),
('bx_groups_prices_view', 'price', '_bx_groups_grid_column_title_price', '20%', 0, 16, '', 2),
('bx_groups_prices_view', 'period', '_bx_groups_grid_column_title_period', '20%', 0, 16, '', 3),
('bx_groups_prices_view', 'actions', '', '20%', 0, 0, '', 4);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_groups_prices_manage', 'bx_groups_prices_view');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_groups_prices_manage', 'independent', 'add', '_bx_groups_grid_action_title_add', '', 0, 0, 1),
('bx_groups_prices_manage', 'single', 'edit', '_bx_groups_grid_action_title_edit', 'pencil-alt', 1, 0, 1),
('bx_groups_prices_manage', 'single', 'delete', '_bx_groups_grid_action_title_delete', 'remove', 1, 1, 2),
('bx_groups_prices_manage', 'bulk', 'delete', '_bx_groups_grid_action_title_delete', '', 0, 1, 1),

('bx_groups_prices_view', 'single', 'buy', '_bx_groups_grid_action_title_buy', 'cart-plus', 0, 0, 1),
('bx_groups_prices_view', 'single', 'subscribe', '_bx_groups_grid_action_title_subscribe', 'credit-card', 0, 0, 2),
('bx_groups_prices_view', 'single', 'choose', '_bx_groups_grid_action_title_choose', 'far check-square', 0, 0, 3);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_groups_allow_view_favorite_list';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `spaces`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_allow_view_favorite_list', 'bx_groups', 'view_favorite_list', '_bx_groups_form_entry_input_allow_view_favorite_list', '3', '', 'bx_groups_favorites_lists', 'id', 'author_id', 'BxGroupsPrivacy', 'modules/boonex/groups/classes/BxGroupsPrivacy.php');


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_groups_set_role';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_groups', '_bx_groups_email_set_role', 'bx_groups_set_role', '_bx_groups_email_set_role_subject', '_bx_groups_email_set_role_body');


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_groups_pruning';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_groups_pruning', '0 0 * * *', 'BxGroupsCronPruning', 'modules/boonex/groups/classes/BxGroupsCronPruning.php', '');
