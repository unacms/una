SET @sName = 'bx_invites';


-- PAGES & BLOCKS
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_invites_invite', '_bx_invites_page_title_sys_invite', '_bx_invites_page_title_invite', @sName, 5, 2147483647, 1, 'invites-invite', 'page.php?i=invites-invite', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_invites_invite', 1, @sName, '_bx_invites_page_block_title_invite_form', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_invites";s:6:"method";s:21:"get_block_form_invite";}', 0, 1, 1);

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_invites_request', '_bx_invites_page_title_sys_request', '_bx_invites_page_title_request', @sName, 5, 2147483647, 1, 'invites-request', 'page.php?i=invites-request', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_invites_request', 1, @sName, '_bx_invites_page_block_title_request_form', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_invites";s:6:"method";s:22:"get_block_form_request";}', 0, 1, 1);

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_invites_requests', '_bx_invites_page_title_sys_requests', '_bx_invites_page_title_requests', @sName, 5, 192, 1, 'invites-requests', 'page.php?i=invites-requests', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_invites_requests', 1, @sName, '_bx_invites_page_block_title_requests', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_invites";s:6:"method";s:25:"get_block_manage_requests";}', 0, 1, 1);


-- PAGES: add page block on dashboard
SET @iPBCellDashboard = 3;
SET @iPBOrderDashboard = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard' AND `cell_id` = @iPBCellDashboard LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_dashboard', @iPBCellDashboard, @sName, '_bx_invites_page_block_title_invite', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_invites";s:6:"method";s:16:"get_block_invite";}', 0, 1, @iPBOrderDashboard + 1);


-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', @sName, 'invites-requests', '_bx_invites_menu_item_title_system_requests', '_bx_invites_menu_item_title_requests', 'page.php?i=invites-requests', '', '_self', '', 'a:2:{s:6:"module";s:10:"bx_invites";s:6:"method";s:23:"get_menu_addon_requests";}', '', 192, 1, 0, @iManageMenuOrder + 1);


-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_invites', 'bx_invites@modules/boonex/invites/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_invites', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_invites_count_per_user', '5', @iCategId, '_bx_invites_option_count_per_user', 'digit', '', '', '', 1),
('bx_invites_key_lifetime', '7', @iCategId, '_bx_invites_option_key_lifetime', 'digit', '', '', '', 2),
('bx_invites_enable_request_invite', 'on', @iCategId, '_bx_invites_option_enable_request_invite', 'checkbox', '', '', '', 3),
('bx_invites_requests_email', '', @iCategId, '_bx_invites_option_requests_email', 'digit', '', '', '', 4);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'request', NULL, '_bx_invites_acl_action_request', '', 0, 2147483646);
SET @iIdActionRequest = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'delete request', NULL, '_bx_invites_acl_action_delete_request', '', 0, 1);
SET @iIdActionDeleteRequest = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'invite', NULL, '_bx_invites_acl_action_invite', '', 1, 1);
SET @iIdActionInvite = LAST_INSERT_ID();

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

-- request invite
(@iUnauthenticated, @iIdActionRequest),

-- delete request
(@iModerator, @iIdActionDeleteRequest),
(@iAdministrator, @iIdActionDeleteRequest),

-- invite
(@iAccount, @iIdActionInvite),
(@iStandard, @iIdActionInvite),
(@iUnconfirmed, @iIdActionInvite),
(@iPending, @iIdActionInvite),
(@iSuspended, @iIdActionInvite),
(@iModerator, @iIdActionInvite),
(@iAdministrator, @iIdActionInvite),
(@iPremium, @iIdActionInvite);


-- EMAIL TEMPLATES
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
(@sName, '_bx_invites_et_invite_form_message', 'bx_invites_invite_form_message', '_bx_invites_et_invite_form_message_subject', '_bx_invites_et_invite_form_message_body'),
(@sName, '_bx_invites_et_invite_by_request_message', 'bx_invites_invite_by_request_message', '_bx_invites_et_invite_by_request_message_subject', '_bx_invites_et_invite_by_request_message_body'),
(@sName, '_bx_invites_et_request_form_message', 'bx_invites_request_form_message', '_bx_invites_et_request_form_message_subject', '_bx_invites_et_request_form_message_body');


-- ALERTS
INSERT INTO `sys_alerts_handlers`(`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxInvResponse', 'modules/boonex/invites/classes/BxInvResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'add_form', @iHandler),
('account', 'added', @iHandler),
('profile', 'delete', @iHandler);