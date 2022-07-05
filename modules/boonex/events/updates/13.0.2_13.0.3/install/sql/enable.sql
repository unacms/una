-- SETTINGS
UPDATE `sys_options` SET `value`='H:i' WHERE `name`='bx_events_time_format';
UPDATE `sys_options` SET `value`='j/n/Y' WHERE `name`='bx_events_short_date_format';
UPDATE `sys_options` SET `value`='j/n/Y H:i' WHERE `name`='bx_events_datetime_format';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions' AND `name`='ical-export';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_events_view_actions', 'bx_events', 'ical-export', '', '_bx_events_menu_item_title_ical_export', '{ical_url}', '', '', 'calendar-plus', '', 0, 2147483647, 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:24:"is_ical_export_avaliable";s:6:"params";a:1:{i:0;s:12:"{content_id}";}}', 1, 0, 1, 40);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_all' AND `name`='ical-export';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_actions_all', 'bx_events', 'ical-export', '', '_bx_events_menu_item_title_ical_export', '{ical_url}', '', '', 'calendar-plus', '', '', 0, 2147483647, 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:24:"is_ical_export_avaliable";s:6:"params";a:1:{i:0;s:12:"{content_id}";}}', 1, 0, 60);
