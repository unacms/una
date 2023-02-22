SET @sName = 'bx_forum';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_snippet_meta_main' AND `name` IN ('sticked', 'locked');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`, `hidden_on_col`) VALUES 
('bx_forum_snippet_meta_main', 'bx_forum', 'sticked', '_bx_forum_menu_item_title_system_sm_sticked', '_bx_forum_menu_item_title_sm_sticked', '', '', '', 'thumbtack', '', 2147483647, 1, 0, 1, 5, 3),
('bx_forum_snippet_meta_main', 'bx_forum', 'locked', '_bx_forum_menu_item_title_system_sm_locked', '_bx_forum_menu_item_title_sm_locked', '', '', '', 'lock', '', 2147483647, 0, 0, 1, 6, 3);
