-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_view_meta' AND `name`='relations';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_persons_view_meta', 'bx_persons', 'relations', '_sys_menu_item_title_system_sm_relations', '_sys_menu_item_title_sm_relations', '', '', '', '', '', 0, 2147483647, '', 0, 0, 27);
