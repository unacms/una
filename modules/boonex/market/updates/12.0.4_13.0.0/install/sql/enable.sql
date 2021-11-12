-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_market' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_market_enable_auto_approve';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_market_enable_auto_approve', 'on', @iCategId, '_bx_market_option_enable_auto_approve', 'checkbox', '', '', '', '', 0);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_reports';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_market_view_entry', 2, 'bx_market', '', '_bx_market_page_block_title_entry_reports', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:14:\"entity_reports\";}', '', 0, '', 0, 0, 1, 6);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_view' AND `name`='approve';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('bx_market_view', 'bx_market', 'approve', '_sys_menu_item_title_system_va_approve', '_sys_menu_item_title_va_approve', 'javascript:void(0)', 'javascript:bx_approve(this, ''{module_uri}'', {content_id});', '', 'check', '', '', '', 0, 2147483647, '', 1, 0, 50);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_view_actions' AND `name` IN ('approve', 'audit');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_market_view_actions', 'bx_market', 'approve', '_sys_menu_item_title_system_va_approve', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 80),
('bx_market_view_actions', 'bx_market', 'audit', '_sys_menu_item_title_system_va_audit', '_sys_menu_item_title_va_audit', 'page.php?i=dashboard-audit&module=bx_market&content_id={content_id}', '', '', 'history', '', '', '', 0, 192, '', 1, 0, 290);


-- ACL
SET @iIdActionEntryDeleteAny = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_market' AND `Name`='delete any entry' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionEntryDeleteAny;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionEntryDeleteAny;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_market', 'delete any entry', NULL, '_bx_market_acl_action_delete_any_entry', '', 1, 3);
SET @iIdActionEntryDeleteAny = LAST_INSERT_ID();

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
(@iAdministrator, @iIdActionEntryDeleteAny);


-- GRIDS:
UPDATE `sys_grid_fields` SET `width`='15%' WHERE `object`='bx_market_common' AND `name`='added';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_market_common' AND `name`='status_admin';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_market_common', 'status_admin', '_bx_market_grid_column_title_adm_status_admin', '15%', 0, '16', '', 5);