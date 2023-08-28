-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_snippet_meta' AND `name` IN ('ignore-befriend', 'ignore-subscribe');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `hidden_on_cxt`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_persons_snippet_meta', 'bx_persons', 'ignore-befriend', '_sys_menu_item_title_system_sm_ignore_befriend', '_sys_menu_item_title_sm_ignore', '', '', '', '', '', 2147483647, 'all!recom_friends', 1, 0, 1, 13),
('bx_persons_snippet_meta', 'bx_persons', 'ignore-subscribe', '_sys_menu_item_title_system_sm_ignore_subscribe', '_sys_menu_item_title_sm_ignore', '', '', '', '', '', 2147483647, 'all!recom_subscriptions', 1, 0, 1, 14);

UPDATE `sys_menu_items` SET `hidden_on_cxt`='all!recom_friends' WHERE `set_name`='bx_persons_snippet_meta' AND `name` IN ('friends-mutual');
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_friends,recom_subscriptions' WHERE `set_name`='bx_persons_snippet_meta' AND `name` IN ('friends', 'unfriend', 'unsubscribe');
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_friends' WHERE `set_name`='bx_persons_snippet_meta' AND `name` IN ('subscribers', 'subscribe');
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_subscriptions' WHERE `set_name`='bx_persons_snippet_meta' AND `name` IN ('befriend');

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='sys_profile_stats' AND `module`='bx_persons' AND `name` IN ('profile-stats-friend-requests', 'profile-stats-subscriptions', 'profile-stats-subscribed-me');
