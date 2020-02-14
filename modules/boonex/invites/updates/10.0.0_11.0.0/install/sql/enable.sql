SET @sName = 'bx_invites';

-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_invites_automatically_befriend';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_invites_automatically_befriend', 'on', @iCategId, '_bx_invites_option_automatically_befriend', 'checkbox', '', '', '', '', 7);


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_invites_invites';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_invites_invites', '_bx_invites_page_title_sys_invites', '_bx_invites_page_title_invites', @sName, 5, 2147483647, 1, 'invites-invites', 'page.php?i=invites-invites', '', '', '', 0, 1, 0, '', '');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_invites_invites';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_invites_invites', 1, @sName, '', '_bx_invites_page_block_title_invites', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_invites";s:6:"method";s:24:"get_block_manage_invites";}', 0, 1, 1);


-- ACL
SET @iIdActionDeleteInvite = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='delete invite' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='delete invite';
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionDeleteInvite;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'delete invite', NULL, '_bx_invites_acl_action_delete_invite', '', 0, 1);
SET @iIdActionDeleteInvite = LAST_INSERT_ID();

SET @iModerator = 7;
SET @iAdministrator = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionDeleteInvite),
(@iAdministrator, @iIdActionDeleteInvite);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_invites_requests', 'bx_invites_invites');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_invites_requests', 'Sql', 'SELECT `bx_inv_requests`.* FROM `bx_inv_requests` WHERE 1', 'bx_inv_requests', 'id', 'status, date', '', '', 20, NULL, 'start', '', 'bx_inv_requests`.`name, bx_inv_requests`.`email', '', 'like', '', '', 192, 'BxInvGridRequests', 'modules/boonex/invites/classes/BxInvGridRequests.php'),
('bx_invites_invites', 'Sql', 'SELECT `bx_inv_invites`.* FROM `bx_inv_invites` WHERE 1', 'bx_inv_invites', 'id', 'date', '', '', 20, NULL, 'start', '', 'email', '', 'like', '', '', 192, 'BxInvGridInvites', 'modules/boonex/invites/classes/BxInvGridInvites.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_invites_requests', 'bx_invites_invites');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_invites_requests', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_invites_requests', 'name', '_bx_invites_grid_column_title_name', '14%', 0, 0, '', 2),
('bx_invites_requests', 'email', '_bx_invites_grid_column_title_email', '14%', 1, 25, '', 3),
('bx_invites_requests', 'nip', '_bx_invites_grid_column_title_nip', '10%', 0, 15, '', 4),
('bx_invites_requests', 'date', '_bx_invites_grid_column_title_date', '10%', 0, 20, '', 5),
('bx_invites_requests', 'joined_account', '_bx_invites_grid_column_title_joined_account', '20%', 0, 20, '', 6),
('bx_invites_requests', 'status', '_bx_invites_grid_column_title_status', '10%', 0, 15, '', 7),
('bx_invites_requests', 'actions', '', '20%', 0, '', '', 8),

('bx_invites_invites', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_invites_invites', 'email', '_bx_invites_grid_invites_column_title_email', '15%', 1, 25, '', 2),
('bx_invites_invites', 'who_send', '_bx_invites_grid_invites_column_title_who_sent', '15%', 0, 0, '', 3),
('bx_invites_invites', 'date', '_bx_invites_grid_invites_column_title_date', '10%', 0, 20, '', 4),
('bx_invites_invites', 'date_seen', '_bx_invites_grid_invites_column_title_date_seen', '10%', 0, 20, '', 5),
('bx_invites_invites', 'joined_account', '_bx_invites_grid_invites_column_title_joined_account', '15%', 0, 20, '', 6),
('bx_invites_invites', 'date_joined', '_bx_invites_grid_invites_column_title_date_joined', '10%', 0, 20, '', 7),
('bx_invites_invites', 'request', '_bx_invites_grid_invites_column_title_request', '13%', 0, 20, '', 8),
('bx_invites_invites', 'actions', '', '20%', 0, '', '', 8);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_invites_requests', 'bx_invites_invites');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_invites_requests', 'bulk', 'invite', '_bx_invites_grid_action_title_adm_invite', '', 0, 0, 1),
('bx_invites_requests', 'bulk', 'delete', '_bx_invites_grid_action_title_adm_delete', '', 0, 1, 2),
('bx_invites_requests', 'single', 'info', '_bx_invites_grid_action_title_adm_info', 'info-circle', 1, 0, 1),
('bx_invites_requests', 'single', 'invite', '_bx_invites_grid_action_title_adm_invite', 'envelope', 1, 0, 2),
('bx_invites_requests', 'single', 'delete', '_bx_invites_grid_action_title_adm_delete', 'remove', 1, 1, 3),
('bx_invites_requests', 'single', 'invite_info', '_bx_invites_grid_action_title_adm_invite_info', 'info-circle', 1, 0, 4),
('bx_invites_requests', 'independent', 'add', '_bx_invites_grid_action_title_adm_add', '', 0, 0, 5),

('bx_invites_invites', 'bulk', 'delete', '_bx_invites_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_invites_invites', 'single', 'delete', '_bx_invites_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_invites_invites', 'independent', 'add', '_bx_invites_grid_action_title_adm_add', '', 0, 0, 3);


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='profile' AND `action`='add' AND `handler_id`=@iHandler;
DELETE FROM `sys_alerts` WHERE `unit`='bx_analytics' AND `action` IN ('get_modules', 'get_reports', 'get_chart_data_line') AND `handler_id`=@iHandler;

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'add', @iHandler),
('bx_analytics', 'get_modules', @iHandler),
('bx_analytics', 'get_reports', @iHandler),
('bx_analytics', 'get_chart_data_line', @iHandler);
