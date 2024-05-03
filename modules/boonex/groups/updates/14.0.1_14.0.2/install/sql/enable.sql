-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions' AND `name` IN ('profile-fan-remove', 'profile-subscribe-remove');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_groups_view_actions', 'bx_groups', 'profile-fan-remove', '_bx_groups_menu_item_title_system_leave_group', '{title_remove_fan}', 'javascript:void(0)', 'bx_conn_action(this, \'bx_groups_fans\', \'remove\', \'{profile_id}\')', '', 'sign-out-alt', '', 0, 2147483647, '', 1, 0, 1, 11),
('bx_groups_view_actions', 'bx_groups', 'profile-subscribe-remove', '_bx_groups_menu_item_title_system_unsubscribe', '_bx_groups_menu_item_title_unsubscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'remove\', \'{profile_id}\')', '', 'check', '', 0, 2147483647, '', 1, 0, 1, 21);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_more' AND `name` IN ('group-manage', 'notes', 'audit');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_actions_more', 'bx_groups', 'group-manage', '_bx_groups_menu_item_title_system_view_manage', '_bx_groups_menu_item_title_view_manage', 'page.php?i=group-manage&profile_id={profile_id}', '', '', 'users', '', 2147483647, '', 1, 0, 10),
('bx_groups_view_actions_more', 'bx_groups', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', 192, '', 1, 0, 20),
('bx_groups_view_actions_more', 'bx_groups', 'audit', '_sys_menu_item_title_system_va_audit', '_sys_menu_item_title_va_audit', 'page.php?i=dashboard-audit&module=bx_groups&content_id={content_id}', '', '', 'history', '', 192, '', 1, 0, 30);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_more' AND `name` IN ('profile-fan-remove', 'profile-subscribe-remove');

UPDATE `sys_objects_menu` SET `persistent`='1' WHERE `object`='bx_groups_view_actions_all';
DELETE FROM sys_menu_items WHERE `set_name`='bx_groups_view_actions_all' AND `name` IN ('notes', 'audit', 'edit-group-profile', 'edit-group-pricing', 'invite-to-group', 'delete-group-profile', 'approve-group-profile');

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_submenu' AND `name`='group-manage';


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`group_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status_profile` FROM `bx_groups_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_groups'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_groups_administration';
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`group_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status_profile` FROM `bx_groups_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_groups'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_groups_common';
