DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-share' LIMIT 1;
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-share', '_bx_timeline_menu_item_title_system_item_share', '', 'javascript:void(0)', '', '', '', '', '', 2147483647, 1, 0, 0, 3);


UPDATE `sys_objects_cmts` SET `IsRatable`='1' WHERE `Name`='bx_timeline' LIMIT 1;