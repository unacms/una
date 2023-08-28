-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_snippet_meta' AND `name` IN ('ignore-befriend', 'ignore-subscribe');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `hidden_on_cxt`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_organizations_snippet_meta', 'bx_organizations', 'ignore-befriend', '_sys_menu_item_title_system_sm_ignore_befriend', '_sys_menu_item_title_sm_ignore', '', '', '', '', '', 2147483647, '', 'all!recom_friends', 1, 0, 1, 85),
('bx_organizations_snippet_meta', 'bx_organizations', 'ignore-subscribe', '_sys_menu_item_title_system_sm_ignore_subscribe', '_sys_menu_item_title_sm_ignore', '', '', '', '', '', 2147483647, '', 'all!recom_subscriptions', 1, 0, 1, 90);

UPDATE `sys_menu_items` SET `hidden_on_cxt`='all!recom_friends' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='friends-mutual';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_friends,recom_subscriptions' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='members';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_friends,recom_subscriptions' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='friends';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_friends' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='subscribers';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='friends,friend_requests,friend_requested,subscriptions,subscribed_me,recom_friends,recom_subscriptions' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='join-paid';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='friends,friend_requests,friend_requested,subscriptions,subscribed_me,recom_friends,recom_subscriptions' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='join';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='friends,friend_requests,friend_requested,subscriptions,subscribed_me,recom_friends,recom_subscriptions' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='leave';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_subscriptions' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='befriend';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_friends,recom_subscriptions' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='unfriend';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_friends' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='subscribe';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_friends,recom_subscriptions' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='unsubscribe';

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='sys_profile_stats' AND `module`='bx_organizations' AND `name`='profile-stats-friend-requests';
UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='sys_profile_stats' AND `module`='bx_organizations' AND `name`='profile-stats-subscriptions';
UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='sys_profile_stats' AND `module`='bx_organizations' AND `name`='profile-stats-subscribed-me';