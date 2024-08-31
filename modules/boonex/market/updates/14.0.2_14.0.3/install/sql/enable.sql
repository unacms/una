-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_snippet_meta' AND `name`='votes';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_market_snippet_meta', 'bx_market', 'votes', '_sys_menu_item_title_system_sm_votes', '_sys_menu_item_title_sm_votes', '', '', '', '', '', 2147483647, 0, 0, 1, 6);
