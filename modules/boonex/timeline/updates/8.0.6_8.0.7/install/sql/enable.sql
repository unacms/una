-- MENUS
UPDATE `sys_objects_menu` SET `template_id`='15' WHERE `object`='bx_timeline_menu_item_actions';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-more';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-more', '_bx_timeline_menu_item_title_system_item_more', '', 'javascript:void(0)', 'bx_menu_popup(''bx_timeline_menu_item_manage'', this, {}, {content_id:{content_id}});', '', 'ellipsis-h', '', 'bx_timeline_menu_item_manage', 2147483647, 1, 0, 0, 4);


-- SEARCH
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
UPDATE `sys_objects_search` SET `Order`=@iSearchOrder + 1 WHERE `ObjectName`='bx_timeline';
UPDATE `sys_objects_search` SET `Order`=@iSearchOrder + 2 WHERE `ObjectName`='bx_timeline_cmts';