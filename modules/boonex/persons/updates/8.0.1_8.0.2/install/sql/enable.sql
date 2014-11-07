-- PAGE: module manage
DELETE FROM `sys_objects_page` WHERE `module`='bx_persons' AND `object`='bx_persons_manage' LIMIT 1;
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_manage', '_bx_persons_page_title_sys_manage', '_bx_persons_page_title_manage', 'bx_persons', 5, 2147483647, 1, 'persons-manage', 'page.php?i=persons-manage', '', '', '', 0, 1, 0, 'BxPersonsPageBrowse', 'modules/boonex/persons/classes/BxPersonsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_persons' AND `object`='bx_persons_manage';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_persons_manage', 1, 'bx_persons', '_bx_persons_page_block_title_manage', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:12:\"manage_tools\";}}', 0, 1, 0);


-- PAGE: module moderation
DELETE FROM `sys_objects_page` WHERE `module`='bx_persons' AND `object`='bx_persons_moderation' LIMIT 1;
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_moderation', '_bx_persons_page_title_sys_manage', '_bx_persons_page_title_manage', 'bx_persons', 5, 64, 1, 'persons-moderation', 'page.php?i=persons-moderation', '', '', '', 0, 1, 0, 'BxPersonsPageBrowse', 'modules/boonex/persons/classes/BxPersonsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_persons' AND `object`='bx_persons_moderation';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_persons_moderation', 1, 'bx_persons', '_bx_persons_page_block_title_manage', 11, 64, 'service', 'a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:10:\"moderation\";}}', 0, 1, 0);


-- PAGE: module administration
DELETE FROM `sys_objects_page` WHERE `module`='bx_persons' AND `object`='bx_persons_administration' LIMIT 1;
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_administration', '_bx_persons_page_title_sys_manage', '_bx_persons_page_title_manage', 'bx_persons', 5, 128, 1, 'persons-administration', 'page.php?i=persons-administration', '', '', '', 0, 1, 0, 'BxPersonsPageBrowse', 'modules/boonex/persons/classes/BxPersonsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_persons' AND `object`='bx_persons_administration';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_persons_administration', 1, 'bx_persons', '_bx_persons_page_block_title_manage', 11, 128, 'service', 'a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:14:\"administration\";}}', 0, 1, 0);


DELETE FROM `sys_menu_items` WHERE `module`='bx_persons' AND `set_name`='bx_persons_submenu' AND `name`='persons-manage' LIMIT 1;
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_persons_submenu', 'bx_persons', 'persons-manage', '_bx_persons_menu_item_title_system_entries_manage', '_bx_persons_menu_item_title_entries_manage', 'page.php?i=persons-manage', '', '', '', '', 2147483647, 1, 1, 2);


DELETE FROM `sys_menu_items` WHERE `module`='bx_persons' AND `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-profiles' LIMIT 1;
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_persons', 'profile-stats-manage-profiles', '_bx_persons_menu_item_title_system_manage_my_profiles', '_bx_persons_menu_item_title_manage_my_profiles', 'page.php?i=persons-manage', '', '_self', 'group col-blue3', 'a:2:{s:6:"module";s:10:"bx_persons";s:6:"method";s:41:"get_menu_addon_manage_tools_profile_stats";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 1);


-- MENU: manage tools submenu
DELETE FROM `sys_objects_menu` WHERE `module`='bx_persons' AND `object`='bx_persons_menu_manage_tools' LIMIT 1;
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_menu_manage_tools', '_bx_persons_menu_title_manage_tools', 'bx_persons_menu_manage_tools', 'bx_persons', 6, 0, 1, 'BxPersonsMenuManageTools', 'modules/boonex/persons/classes/BxPersonsMenuManageTools.php');

DELETE FROM `sys_menu_sets` WHERE `module`='bx_persons' AND `set_name`='bx_persons_menu_manage_tools' LIMIT 1;
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_persons_menu_manage_tools', 'bx_persons', '_bx_persons_menu_set_title_manage_tools', 0);

DELETE FROM `sys_menu_items` WHERE `module`='bx_persons' AND `set_name`='bx_persons_menu_manage_tools';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_persons_menu_manage_tools', 'bx_persons', 'delete-with-content', '_bx_persons_menu_item_title_system_delete_with_content', '_bx_persons_menu_item_title_delete_with_content', 'javascript:void(0)', 'javascript:{js_object}.onClickDeleteWithContent({content_id});', '_self', 'trash-o', '', 128, 1, 0, 0);


-- MENU: dashboard manage tools
DELETE FROM `sys_menu_items` WHERE `module`='bx_persons' AND `set_name`='sys_account_dashboard_manage_tools' AND `name` IN ('persons-moderation', 'persons-administration');
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_persons', 'persons-moderation', '_bx_persons_menu_item_title_system_admt_persons', '_bx_persons_menu_item_title_admt_persons', 'page.php?i=persons-moderation', '', '_self', '', 'a:2:{s:6:"module";s:10:"bx_persons";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 64, 1, 0, @iManageMenuOrder + 1),
('sys_account_dashboard_manage_tools', 'bx_persons', 'persons-administration', '_bx_persons_menu_item_title_system_admt_persons', '_bx_persons_menu_item_title_admt_persons', 'page.php?i=persons-administration', '', '_self', '', 'a:2:{s:6:"module";s:10:"bx_persons";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 128, 1, 0, @iManageMenuOrder + 2);


-- GRIDS: administration
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_persons_administration', 'bx_persons_moderation', 'bx_persons_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_persons_administration', 'bx_persons_moderation', 'bx_persons_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_persons_administration', 'bx_persons_moderation', 'bx_persons_common');

INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_persons_administration', 'Sql', 'SELECT `td`.*, `ta`.`email` AS `account`, `ta`.`logged` AS `last_online`, `tp`.`status` AS `status` FROM `bx_persons_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_persons'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ', 'bx_persons_data', 'id', '', 'status', '', 20, NULL, 'start', '', 'fullname', '', 'like', '', '', 'BxPersonsGridAdministration', 'modules/boonex/persons/classes/BxPersonsGridAdministration.php'),
('bx_persons_moderation', 'Sql', 'SELECT `td`.*, `ta`.`email` AS `account`, `ta`.`logged` AS `last_online`, `tp`.`status` AS `status` FROM `bx_persons_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_persons'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ', 'bx_persons_data', 'id', '', 'status', '', 20, NULL, 'start', '', 'fullname', '', 'like', '', '', 'BxPersonsGridModeration', 'modules/boonex/persons/classes/BxPersonsGridModeration.php'),
('bx_persons_common', 'Sql', 'SELECT `td`.*, `ta`.`email` AS `account`, `ta`.`logged` AS `last_online`, `tp`.`status` AS `status` FROM `bx_persons_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_persons'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ', 'bx_persons_data', 'id', '', 'status', '', 20, NULL, 'start', '', 'fullname', '', 'like', '', '', 'BxPersonsGridCommon', 'modules/boonex/persons/classes/BxPersonsGridCommon.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_persons_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_persons_administration', 'switcher', '_bx_persons_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_persons_administration', 'fullname', '_bx_persons_grid_column_title_adm_fullname', '25%', 0, '', '', 3),
('bx_persons_administration', 'last_online', '_bx_persons_grid_column_title_adm_last_online', '20%', 1, '25', '', 4),
('bx_persons_administration', 'account', '_bx_persons_grid_column_title_adm_account', '25%', 0, '25', '', 5),
('bx_persons_administration', 'actions', '', '20%', 0, '', '', 6),
('bx_persons_moderation', 'switcher', '', '10%', 0, '', '', 1),
('bx_persons_moderation', 'fullname', '_bx_persons_grid_column_title_adm_fullname', '25%', 0, '', '', 2),
('bx_persons_moderation', 'last_online', '_bx_persons_grid_column_title_adm_last_online', '25%', 1, '25', '', 3),
('bx_persons_moderation', 'account', '_bx_persons_grid_column_title_adm_account', '25%', 0, '25', '', 4),
('bx_persons_moderation', 'actions', '', '15%', 0, '', '', 5),
('bx_persons_common', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_persons_common', 'fullname', '_bx_persons_grid_column_title_adm_fullname', '48%', 0, '', '', 2),
('bx_persons_common', 'last_online', '_bx_persons_grid_column_title_adm_last_online', '30%', 1, '25', '', 3),
('bx_persons_common', 'actions', '', '20%', 0, '', '', 4);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_persons_administration', 'bulk', 'set_acl_level', '_bx_persons_grid_action_title_adm_set_acl_level', '', 0, 1),
('bx_persons_administration', 'bulk', 'delete', '_bx_persons_grid_action_title_adm_delete', '', 1, 2),
('bx_persons_administration', 'bulk', 'delete_with_content', '_bx_persons_grid_action_title_adm_delete_with_content', '', 1, 3),
('bx_persons_administration', 'single', 'set_acl_level', '', 'certificate', 0, 1),
('bx_persons_administration', 'single', 'delete', '', 'remove', 1, 2),
('bx_persons_administration', 'single', 'settings', '', 'cog', 0, 3),
('bx_persons_moderation', 'single', 'settings', '', 'cog', 0, 1),
('bx_persons_common', 'bulk', 'delete', '_bx_persons_grid_action_title_adm_delete', '', 1, 2),
('bx_persons_common', 'bulk', 'delete_with_content', '_bx_persons_grid_action_title_adm_delete_with_content', '', 1, 3),
('bx_persons_common', 'single', 'delete', '', 'remove', 1, 2),
('bx_persons_common', 'single', 'settings', '', 'cog', 0, 3);