SET @sName = 'bx_forum';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_forum_enable_auto_approve';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_forum_enable_auto_approve', 'on', @iCategId, '_bx_forum_option_enable_auto_approve', 'checkbox', '', '', '', '', 0);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_reports';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_view_entry', 2, @sName, '', '_bx_forum_page_block_title_entry_reports', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:14:\"entity_reports\";}', '', 0, '', 0, 0, 1, 6);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_forum_page_block_title_sys_favorites_entries' WHERE `object`='bx_forum_favorites' AND `title`='_bx_forum_page_block_title_favorites_entries';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view' AND `name`='approve';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('bx_forum_view', @sName, 'approve', '_sys_menu_item_title_system_va_approve', '_sys_menu_item_title_va_approve', 'javascript:void(0)', 'javascript:bx_approve(this, ''{module_uri}'', {content_id});', '', 'check', '', '', '', 0, 2147483647, '', 1, 0, 5);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view' AND `name` IN ('approve', 'notes', 'audit');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_forum_view_actions', @sName, 'approve', '_sys_menu_item_title_system_va_approve', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 100),
('bx_forum_view_actions', @sName, 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', '', 0, 2147483647, '', 1, 0, 280),
('bx_forum_view_actions', @sName, 'audit', '_sys_menu_item_title_system_va_audit', '_sys_menu_item_title_va_audit', 'page.php?i=dashboard-audit&module=bx_forum&content_id={content_id}', '', '', 'history', '', '', '', 0, 192, '', 1, 0, 290);


-- GRIDS
UPDATE `sys_grid_fields` SET `width`='15%' WHERE `object`='bx_forum_common' AND `name`='added';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_forum_common' AND `name`='status_admin';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_forum_common', 'status_admin', '_bx_forum_grid_column_title_adm_status_admin', '15%', 0, '16', '', 5);


-- ACL
SET @iIdActionEntryDeleteAny = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='delete any entry' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionEntryDeleteAny;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionEntryDeleteAny;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'delete any entry', NULL, '_bx_forum_acl_action_delete_any_entry', '', 1, 3);
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