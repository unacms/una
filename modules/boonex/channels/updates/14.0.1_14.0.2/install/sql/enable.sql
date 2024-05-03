SET @sName = 'bx_channels';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_view_actions' AND `name` IN ('profile-subscribe-remove');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_channels_view_actions', 'bx_channels', 'profile-subscribe-remove', '_bx_channels_menu_item_title_system_unsubscribe', '_bx_channels_menu_item_title_unsubscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'remove\', \'{profile_id}\')', '', 'check', '', 0, 2147483647, 1, 0, 1, 21);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_view_actions_more' AND `name` IN ('notes');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_channels_view_actions_more', 'bx_channels', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', 192, 1, 0, 20);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_view_actions_more' AND `name` IN ('profile-subscribe-remove');

UPDATE `sys_objects_menu` SET `persistent`='1' WHERE `object`='bx_channels_view_actions_all';
DELETE FROM sys_menu_items WHERE `set_name`='bx_channels_view_actions_all' AND `name` IN ('notes', 'edit-channel-profile', 'delete-channel-profile');


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`channel_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status_profile` FROM `bx_cnl_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_channels'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_channels_administration';
