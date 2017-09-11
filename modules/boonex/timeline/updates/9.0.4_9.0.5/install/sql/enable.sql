SET @sName = 'bx_timeline';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-view';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-view', '_bx_timeline_menu_item_title_system_item_view', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, 0, 0, 1, 0);

UPDATE `sys_menu_items` SET `editable`='1' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name` IN ('item-vote', 'item-share', 'item-report', 'item-more');
