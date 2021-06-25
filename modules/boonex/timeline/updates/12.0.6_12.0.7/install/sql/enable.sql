SET @sName = 'bx_timeline';


-- MENUS
UPDATE `sys_menu_items` SET `onclick`='javascript:{repost_onclick}' WHERE `set_name`='bx_timeline_menu_item_share' AND `name`='item-repost';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-repost';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-repost', '_bx_timeline_menu_item_title_system_item_repost', '_bx_timeline_menu_item_title_item_repost', 'javascript:void(0)', 'javascript:{repost_onclick}', '', 'redo', '', '', '', 0, 2147483647, '', 0, 0, 1, 55);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions_all' AND `name`='item-repost';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-repost', '_bx_timeline_menu_item_title_system_item_repost', '', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 1, 55);
