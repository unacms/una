-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_posts_view_actions' AND `name` IN ('repost-with-text', 'repost-to-context');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_posts_view_actions', 'bx_posts', 'repost-with-text', '_sys_menu_item_title_system_va_repost_with_text', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 261),
('bx_posts_view_actions', 'bx_posts', 'repost-to-context', '_sys_menu_item_title_system_va_repost_to_context', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 262);
