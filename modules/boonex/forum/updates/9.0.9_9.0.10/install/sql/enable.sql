SET @sName = 'bx_forum';


-- SETTINGS
DELETE FROM `sys_options` WHERE `name` IN ('bx_forum_autosubscribe_created', 'bx_forum_autosubscribe_replied');


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view' AND `name`='subscribe-discussion';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view_more' AND `name`='unsubscribe-discussion';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view_actions' AND `name` IN ('subscribe-discussion', 'unsubscribe-discussion');
