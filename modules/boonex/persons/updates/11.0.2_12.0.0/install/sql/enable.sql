-- SETTINGS
UPDATE `sys_options` SET `type`='select', `extra`='a:2:{s:6:"module";s:10:"bx_persons";s:6:"method";s:22:"get_options_activation";}' WHERE `name`='bx_persons_autoapproval';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_view_profile' AND `title`='_bx_persons_page_block_title_profile_calendar';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_persons_view_profile', 2, 'bx_persons', '', '_bx_persons_page_block_title_profile_calendar', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:15:\"entity_calendar\";}', 0, 0, 0, 4);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_view_actions_all' AND `title`='notes';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_persons_view_actions_all', 'bx_persons', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', '', 0, 2147483647, '', 1, 0, 280);

UPDATE `sys_objects_menu` SET `template_id`='18' WHERE `object`='bx_persons_view_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_menu_manage_tools' AND `title`='clear-reports';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_persons_menu_manage_tools', 'bx_persons', 'clear-reports', '_bx_persons_menu_item_title_system_clear_reports', '_bx_persons_menu_item_title_clear_reports', 'javascript:void(0)', 'javascript:{js_object}.onClickClearReports({content_id});', '_self', 'eraser', '', '', '', 2147483647, '', 1, 0, 3);


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `ta`.`email` AS `account`, `ta`.`logged` AS `last_online`, `tp`.`status` AS `status`, `tp`.`id` as `profile_id` FROM `bx_persons_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_persons'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_persons_administration';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_persons_administration' AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_persons_administration', 'bulk', 'clear_reports', '_bx_forum_grid_action_title_adm_clear_reports', '', 0, 1, 4);
