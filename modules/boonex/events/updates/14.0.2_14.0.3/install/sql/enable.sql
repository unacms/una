SET @sName = 'bx_events';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions' AND `name` IN ('profile-fan-remove', 'profile-subscribe-remove');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_events_view_actions', 'bx_events', 'profile-fan-remove', '_bx_events_menu_item_title_system_leave_group', '{title_remove_fan}', 'javascript:void(0)', 'bx_conn_action(this, \'bx_events_fans\', \'remove\', \'{profile_id}\')', '', 'sign-out-alt', '', 0, 2147483647, '', 1, 0, 1, 6),
('bx_events_view_actions', 'bx_events', 'profile-subscribe-remove', '_bx_events_menu_item_title_system_unsubscribe', '_bx_events_menu_item_title_unsubscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'remove\', \'{profile_id}\')', '', 'check', '', 0, 2147483647, '', 1, 0, 1, 21);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_more' AND `name` IN ('event-manage', 'notes', 'audit');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_actions_more', 'bx_events', 'event-manage', '_bx_events_menu_item_title_system_view_manage', '_bx_events_menu_item_title_view_manage', 'page.php?i=event-manage&profile_id={profile_id}', '', '', 'users', '', 2147483647, '', 1, 0, 10),
('bx_events_view_actions_more', 'bx_events', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', 192, '', 1, 0, 20),
('bx_events_view_actions_more', 'bx_events', 'audit', '_sys_menu_item_title_system_va_audit', '_sys_menu_item_title_va_audit', 'page.php?i=dashboard-audit&module=bx_events&content_id={content_id}', '', '', 'history', '', 192, '', 1, 0, 30);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_more' AND `name` IN ('profile-fan-remove', 'profile-subscribe-remove');

UPDATE `sys_objects_menu` SET `persistent`='1' WHERE `object`='bx_events_view_actions_all';
DELETE FROM sys_menu_items WHERE `set_name`='bx_events_view_actions_all' AND `name` IN ('notes', 'audit', 'edit-event-profile', 'event-questionnaire', 'event-sessions', 'event-pricing', 'invite-to-event', 'delete-event-profile', 'approve-event-profile');

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_submenu' AND `name`='event-manage';


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`event_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status_profile` FROM `bx_events_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_events'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_events_administration';
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`event_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status_profile` FROM `bx_events_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_events'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_events_common';
