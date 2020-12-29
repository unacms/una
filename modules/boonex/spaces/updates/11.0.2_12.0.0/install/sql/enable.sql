-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_spaces' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_spaces_members_mode', 'bx_spaces_internal_notifications');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_spaces_members_mode', '', @iCategId, '_bx_spaces_option_members_mode', 'select', 'a:2:{s:6:"module";s:9:"bx_spaces";s:6:"method";s:24:"get_options_members_mode";}', '', '', '', 40),
('bx_spaces_internal_notifications', '', @iCategId, '_bx_spaces_option_internal_notifications', 'checkbox', '', '', '', '', 50);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_spaces_view_actions_all' AND `name`='notes';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_spaces_view_actions_all', 'bx_spaces', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', '', 0, 2147483647, '', 1, 0, 280);

UPDATE `sys_objects_menu` SET `template_id`='18' WHERE `object`='bx_spaces_view_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_spaces_menu_manage_tools' AND `name`='clear-reports';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_spaces_menu_manage_tools', 'bx_spaces', 'clear-reports', '_bx_spaces_menu_item_title_system_clear_reports', '_bx_spaces_menu_item_title_clear_reports', 'javascript:void(0)', 'javascript:{js_object}.onClickClearReports({content_id});', '_self', 'eraser', '', '', '', 2147483647, '', 1, 0, 3);


-- GRIDS
DELETE FROM `sys_grid_fields` WHERE `object`='bx_spaces_fans' AND `name`='role';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_spaces_fans', 'role', '_bx_spaces_txt_role', '10%', '', 15);

UPDATE `sys_grid_fields` SET `width`='40%' WHERE `object`='bx_spaces_fans' AND `name`='actions';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_spaces_fans' AND `name` IN ('to_admins', 'from_admins', 'set_role', 'set_role_submit');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_spaces_fans', 'single', 'set_role', '_bx_spaces_txt_set_role', '', 0, 20),
('bx_spaces_fans', 'single', 'set_role_submit', '', '', 0, 21);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_spaces_administration' AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_spaces_administration', 'bulk', 'clear_reports', '_bx_spaces_grid_action_title_adm_clear_reports', '', 0, 1, 4);


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_spaces_set_role';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_spaces', '_bx_spaces_email_set_role', 'bx_spaces_set_role', '_bx_spaces_email_set_role_subject', '_bx_spaces_email_set_role_body');
