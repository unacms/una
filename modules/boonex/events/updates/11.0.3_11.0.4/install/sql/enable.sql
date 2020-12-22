-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_snippet_meta' AND `name` IN ('date-start', 'date-end');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_events_snippet_meta', 'bx_events', 'date-start', '_bx_events_menu_item_title_system_sm_date_start', '_bx_events_menu_item_title_sm_date_start', '', '', '', '', '', '', 2147483647, '', 0, 0, 1, 0),
('bx_events_snippet_meta', 'bx_events', 'date-end', '_bx_events_menu_item_title_system_sm_date_end', '_bx_events_menu_item_title_sm_date_end', '', '', '', '', '', '', 2147483647, '', 0, 0, 1, 0);
