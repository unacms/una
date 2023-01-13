SET @sName = 'bx_accounts';


-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_accounts', 'bx_accounts@modules/boonex/accounts/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_accounts', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_accounts_export_to', 'csv', @iCategId, '_bx_accounts_option_export_to', 'select', '', '', 'a:2:{s:6:"module";s:11:"bx_accounts";s:6:"method";s:21:"get_options_export_to";}', 10),
('bx_accounts_export_fields', 'name,email', @iCategId, '_bx_accounts_option_export_fields', 'list', '', '', 'a:2:{s:6:"module";s:11:"bx_accounts";s:6:"method";s:25:"get_options_export_fields";}', 20);


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
('bx_accounts_menu_manage_tools', @sName, 'edit-email', '_bx_accnt_menu_item_title_system_edit_email', '_bx_accnt_menu_item_title_edit_email', 'javascript:void(0)', 'javascript:{js_object}.onClickEditEmail({content_id}, this);', '_self', 'at', '', 192, 1, 0, 1),
('bx_accounts_menu_manage_tools', @sName, 'resend-cemail', '_bx_accnt_menu_item_title_system_resend_cemail', '_bx_accnt_menu_item_title_resend_cemail', 'javascript:void(0)', 'javascript:{js_object}.onClickResendCemail({content_id}, this);', '_self', 'far envelope', '', 192, 1, 0, 2),
('bx_accounts_menu_manage_tools', @sName, 'confirm', '_bx_accnt_menu_item_title_system_confirm', '_bx_accnt_menu_item_title_confirm', 'javascript:void(0)', 'javascript:{js_object}.onClickConfirm({content_id}, this);', '_self', 'fas check', '', 192, 1, 0, 3),
('bx_accounts_menu_manage_tools', @sName, 'reset-password', '_bx_accnt_menu_item_title_system_reset_password', '_bx_accnt_menu_item_title_reset_password', 'javascript:void(0)', 'javascript:{js_object}.onClickResetPassword({content_id}, this);', '_self', 'eraser', '', 192, 1, 0, 4),
('bx_accounts_menu_manage_tools', @sName, 'resend-remail', '_bx_accnt_menu_item_title_system_resend_remail', '_bx_accnt_menu_item_title_resend_remail', 'javascript:void(0)', 'javascript:{js_object}.onClickResendRemail({content_id}, this);', '_self', 'eraser', '', 192, 1, 0, 5),
('bx_accounts_menu_manage_tools', @sName, 'unlock-account', '_bx_accnt_menu_item_title_system_unlock_account', '_bx_accnt_menu_item_title_unlock_account', 'javascript:void(0)', 'javascript:{js_object}.onClickUnlockAccount({content_id}, this);', '_self', 'unlock', '', 192, 1, 0, 6),
('bx_accounts_menu_manage_tools', @sName, 'delete', '_bx_accnt_menu_item_title_system_delete', '_bx_accnt_menu_item_title_delete', 'javascript:void(0)', 'javascript:{js_object}.onClickDelete({content_id}, this);', '_self', 'far trash-alt', '', 192, 1, 0, 7),
('bx_accounts_menu_manage_tools', @sName, 'delete-with-content', '_bx_accnt_menu_item_title_system_delete_with_content', '_bx_accnt_menu_item_title_delete_with_content', 'javascript:void(0)', 'javascript:{js_object}.onClickDeleteWithContent({content_id}, this);', '_self', 'far trash-alt', '', 192, 1, 0, 8),
('bx_accounts_menu_manage_tools', @sName, 'make-operator', '_bx_accnt_menu_item_title_system_make_operator', '_bx_accnt_menu_item_title_make_operator', 'javascript:void(0)', 'javascript:{js_object}.onClickMakeOperator({content_id}, this);', '_self', 'wrench', '', 192, 0, 0, 9),
('bx_accounts_menu_manage_tools', @sName, 'unmake-operator', '_bx_accnt_menu_item_title_system_unmake_operator', '_bx_accnt_menu_item_title_unmake_operator', 'javascript:void(0)', 'javascript:{js_object}.onClickUnmakeOperator({content_id}, this);', '_self', 'wrench', '', 192, 0, 0, 10),
('bx_accounts_menu_manage_tools', @sName, 'set-operator-role', '_bx_accnt_menu_item_title_system_set_operator_role', '_bx_accnt_menu_item_title_set_operator_role', 'javascript:void(0)', 'javascript:{js_object}.onClickSetOperatorRole({content_id}, this);', '_self', 'wrench', '', 192, 1, 0, 11);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', @sName, 'accounts-administration', '_bx_accnt_menu_item_title_system_admt_accounts', '_bx_accnt_menu_item_title_admt_accounts', 'page.php?i=accounts-administration', '', '_self', 'at', 'a:2:{s:6:"module";s:11:"bx_accounts";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'edit any entry', NULL, '_bx_accnt_acl_action_edit_any_account', '', 1, 3);
SET @iIdActionProfileEditAny = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'delete any entry', NULL, '_bx_accnt_acl_action_delete_any_account', '', 1, 3);
SET @iIdActionProfileDeleteAny = LAST_INSERT_ID();

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
(@iAdministrator, @iIdActionProfileEditAny),

-- delete any entry 
(@iAdministrator, @iIdActionProfileDeleteAny);


-- GRIDS: administration
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('bx_accounts_administration', 'Sql', 'SELECT `ta`.*, `tp`.`status` AS `status`, MAX(IFNULL(`ts`.`date`, `ta`.`active`)) AS `last_active` FROM `sys_accounts` AS `ta` LEFT JOIN `sys_profiles` AS `tp` ON `ta`.`id`=`tp`.`account_id` AND `tp`.`type`=''system'' LEFT JOIN `sys_sessions` AS `ts` ON `ts`.`user_id` = `ta`.`id` WHERE 1 ', 'sys_accounts', 'id', 'logged', 'status', '', 20, NULL, 'start', '', 'name,email,ip,phone', '', 'like', 'email_confirmed,logged,added,last_active', '', 192, 1, 'BxAccntGridAdministration', 'modules/boonex/accounts/classes/BxAccntGridAdministration.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_accounts_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_accounts_administration', 'switcher', '_bx_accnt_grid_column_title_adm_active', '6%', 0, '', '', 2),
('bx_accounts_administration', 'name', '_bx_accnt_grid_column_title_adm_name', '12%', 0, '16', '', 3),
('bx_accounts_administration', 'email', '_bx_accnt_grid_column_title_adm_email', '16%', 0, '16', '', 4),
('bx_accounts_administration', 'is_confirmed', '_bx_accnt_grid_column_title_adm_is_confirmed', '4%', 0, '', '', 5),
('bx_accounts_administration', 'profiles', '_bx_accnt_grid_column_title_adm_profiles', '24%', 0, '', '', 6),
('bx_accounts_administration', 'logged', '_bx_accnt_grid_column_title_adm_logged', '10%', 0, '15', '', 7),
('bx_accounts_administration', 'last_active', '_bx_accnt_grid_column_title_adm_last_active', '10%', 0, '15', '', 8),
('bx_accounts_administration', 'added', '_bx_accnt_grid_column_title_adm_added', '10%', 0, '15', '', 9),
('bx_accounts_administration', 'actions', '', '6%', 0, '', '', 10);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_accounts_administration', 'bulk', 'activate', '_bx_accnt_grid_action_title_adm_activate', '', 0, 0, 1),
('bx_accounts_administration', 'bulk', 'suspend', '_bx_accnt_grid_action_title_adm_suspend', '', 0, 0, 2),
('bx_accounts_administration', 'bulk', 'resend_cemail', '_bx_accnt_grid_action_title_adm_resend_cemail', '', 0, 0, 3),
('bx_accounts_administration', 'bulk', 'resend_remail', '_bx_accnt_grid_action_title_adm_resend_remail', '', 0, 0, 4),
('bx_accounts_administration', 'bulk', 'confirm', '_bx_accnt_grid_action_title_adm_confirm', '', 0, 0, 5),
('bx_accounts_administration', 'bulk', 'delete', '_bx_accnt_grid_action_title_adm_delete', '', 0, 1, 6),
('bx_accounts_administration', 'bulk', 'delete_with_content', '_bx_accnt_grid_action_title_adm_delete_with_content', '', 0, 1, 7),
('bx_accounts_administration', 'bulk', 'export', '_bx_accnt_grid_action_title_adm_export', '', 0, 0, 8),
('bx_accounts_administration', 'bulk', 'send_message', '_bx_accnt_grid_action_title_adm_send_message', '', 0, 0, 9),
('bx_accounts_administration', 'single', 'edit_email', '_bx_accnt_grid_action_title_adm_edit_email', '', 0, 0, 0),
('bx_accounts_administration', 'single', 'reset_password', '_bx_accnt_grid_action_title_adm_reset_password', '', 0, 0, 0),
('bx_accounts_administration', 'single', 'resend_remail', '_bx_accnt_grid_action_title_adm_resend_remail', '', 0, 0, 0),
('bx_accounts_administration', 'single', 'unlock_account', '_bx_accnt_grid_action_title_adm_unlock_account', '', 0, 0, 0),
('bx_accounts_administration', 'single', 'settings', '_bx_accnt_grid_action_title_adm_more_actions', 'cog', 1, 0, 2),
('bx_accounts_administration', 'independent', 'add', '_bx_accnt_grid_action_title_adm_more_add', 'plus', 0, 0, 0),
('bx_accounts_administration', 'independent', 'export', '_bx_accnt_grid_action_title_adm_export', 'download', 0, 0, 1);
