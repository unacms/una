SET @sName = 'bx_accounts';


-- PAGE: module administration
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_accounts_administration', '_bx_accnt_page_title_sys_manage_administration', '_bx_accnt_page_title_manage', @sName, 5, 192, 1, 'accounts-administration', 'page.php?i=accounts-administration', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_accounts_administration', 1, @sName, '_bx_accnt_page_block_title_system_manage_administration', '_bx_accnt_page_block_title_manage', 11, 192, 'service', 'a:3:{s:6:\"module\";s:11:\"bx_accounts\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:14:\"administration\";}}', 0, 1, 0);


-- MENU: manage tools submenu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_accounts_menu_manage_tools', '_bx_accnt_menu_title_manage_tools', 'bx_accounts_menu_manage_tools', @sName, 6, 0, 1, 'BxAccntMenuManageTools', 'modules/boonex/accounts/classes/BxAccntMenuManageTools.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_accounts_menu_manage_tools', @sName, '_bx_accnt_menu_set_title_manage_tools', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_accounts_menu_manage_tools', @sName, 'resend-cemail', '_bx_accnt_menu_item_title_system_resend_cemail', '_bx_accnt_menu_item_title_resend_cemail', 'javascript:void(0)', 'javascript:{js_object}.onClickResendCemail({content_id});', '_self', 'envelope-o', '', 192, 1, 0, 1),
('bx_accounts_menu_manage_tools', @sName, 'delete', '_bx_accnt_menu_item_title_system_delete', '_bx_accnt_menu_item_title_delete', 'javascript:void(0)', 'javascript:{js_object}.onClickDelete({content_id});', '_self', 'trash-o', '', 192, 1, 0, 2),
('bx_accounts_menu_manage_tools', @sName, 'delete-with-content', '_bx_accnt_menu_item_title_system_delete_with_content', '_bx_accnt_menu_item_title_delete_with_content', 'javascript:void(0)', 'javascript:{js_object}.onClickDeleteWithContent({content_id});', '_self', 'trash-o', '', 192, 1, 0, 3);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', @sName, 'accounts-administration', '_bx_accnt_menu_item_title_system_admt_accounts', '_bx_accnt_menu_item_title_admt_accounts', 'page.php?i=accounts-administration', '', '_self', '', 'a:2:{s:6:"module";s:11:"bx_accounts";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'edit any entry', NULL, '_bx_accnt_acl_action_edit_any_account', '', 1, 3);
SET @iIdActionProfileEditAny = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- edit any entry 
(@iModerator, @iIdActionProfileEditAny),
(@iAdministrator, @iIdActionProfileEditAny);


-- GRIDS: administration
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_accounts_administration', 'Sql', 'SELECT `ta`.*, `tp`.`status` AS `status` FROM `sys_accounts` AS `ta` LEFT JOIN `sys_profiles` AS `tp` ON `ta`.`id`=`tp`.`account_id` AND `tp`.`type`=''system'' WHERE 1 ', 'sys_accounts', 'id', 'logged', 'status', '', 20, NULL, 'start', '', 'name,email', '', 'like', '', '', 'BxAccntGridAdministration', 'modules/boonex/accounts/classes/BxAccntGridAdministration.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_accounts_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_accounts_administration', 'switcher', '_bx_accnt_grid_column_title_adm_active', '6%', 0, '', '', 2),
('bx_accounts_administration', 'name', '_bx_accnt_grid_column_title_adm_name', '22%', 0, '22', '', 3),
('bx_accounts_administration', 'email', '_bx_accnt_grid_column_title_adm_email', '22%', 0, '22', '', 4),
('bx_accounts_administration', 'email_confirmed', '_bx_accnt_grid_column_title_adm_email_confirmed', '5%', 0, '', '', 5),
('bx_accounts_administration', 'profiles', '_bx_accnt_grid_column_title_adm_profiles', '22%', 0, '22', '', 5),
('bx_accounts_administration', 'logged', '_bx_accnt_grid_column_title_adm_logged', '15%', 0, '15', '', 7),
('bx_accounts_administration', 'actions', '', '6%', 0, '', '', 8);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_accounts_administration', 'bulk', 'activate', '_bx_accnt_grid_action_title_adm_activate', '', 0, 0, 1),
('bx_accounts_administration', 'bulk', 'suspend', '_bx_accnt_grid_action_title_adm_suspend', '', 0, 0, 2),
('bx_accounts_administration', 'bulk', 'resend_cemail', '_bx_accnt_grid_action_title_adm_resend_cemail', '', 0, 0, 3),
('bx_accounts_administration', 'bulk', 'delete', '_bx_accnt_grid_action_title_adm_delete', '', 0, 1, 4),
('bx_accounts_administration', 'bulk', 'delete_with_content', '_bx_accnt_grid_action_title_adm_delete_with_content', '', 0, 1, 5),
('bx_accounts_administration', 'single', 'settings', '_bx_accnt_grid_action_title_adm_more_actions', 'cog', 1, 0, 1);