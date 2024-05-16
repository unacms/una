-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_view_actions' AND `name` IN ('profile-friend-remove', 'profile-relation-remove', 'profile-subscribe-remove');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_persons_view_actions', 'bx_persons', 'profile-friend-remove', '_bx_persons_menu_item_title_system_unfriend', '{title_remove_friend}', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_friends\', \'remove\', \'{profile_id}\')', '', 'user-times', '', 0, 2147483647, '', 1, 0, 11),
('bx_persons_view_actions', 'bx_persons', 'profile-relation-remove', '_bx_persons_menu_item_title_system_relation_delete', '_bx_persons_menu_item_title_relation_delete', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_relations\', \'remove\', \'{profile_id}\')', '', 'sync', '', 0, 2147483647, '', 1, 0, 16),
('bx_persons_view_actions', 'bx_persons', 'profile-subscribe-remove', '_bx_persons_menu_item_title_system_unsubscribe', '_bx_persons_menu_item_title_unsubscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'remove\', \'{profile_id}\')', '', 'check', '', 0, 2147483647, '', 1, 0, 21);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_view_actions_more' AND `name` IN ('notes', 'audit');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_persons_view_actions_more', 'bx_persons', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', 192, 1, 0, 10),
('bx_persons_view_actions_more', 'bx_persons', 'audit', '_sys_menu_item_title_system_va_audit', '_sys_menu_item_title_va_audit', 'page.php?i=dashboard-audit&module=bx_persons&content_id={content_id}', '', '', 'history', '', 192, 1, 0, 20);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_view_actions_more' AND `name` IN ('profile-friend-remove', 'profile-relation-remove', 'profile-subscribe-remove');

UPDATE `sys_objects_menu` SET `persistent`='1' WHERE `object`='bx_persons_view_actions_all';
DELETE FROM sys_menu_items WHERE `set_name`='bx_persons_view_actions_all' AND `name` IN ('notes', 'audit', 'edit-persons-profile', 'delete-persons-profile', 'delete-persons-account', 'delete-persons-account-content');
