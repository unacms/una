-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_view_meta' AND `name` IN ('friends', 'subscribers');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_persons_view_meta', 'bx_persons', 'friends', '_sys_menu_item_title_system_sm_friends', '_sys_menu_item_title_sm_friends', '', '', '', '', '', 0, 2147483647, '', 1, 0, 23),
('bx_persons_view_meta', 'bx_persons', 'subscribers', '_sys_menu_item_title_system_sm_subscribers', '_sys_menu_item_title_sm_subscribers', '', '', '', '', '', 0, 2147483647, '', 1, 0, 25);
