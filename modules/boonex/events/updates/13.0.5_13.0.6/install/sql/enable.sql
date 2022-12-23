-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_events' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_events_enable_subscribe_for_past_events';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_events_enable_subscribe_for_past_events', 'on', @iCategId, '_bx_events_option_enable_subscribe_for_past_events', 'checkbox', '', '', '', 57);


-- PAGE
DELETE FROM `sys_objects_page` WHERE `object`='bx_events_edit_profile_cover';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_edit_profile_cover';


-- MENUS
UPDATE `sys_menu_items` SET `link`='modules/?r=events/calendar_sync/{content_id}' WHERE `set_name`='bx_events_view_actions' AND `name`='ical-export';
UPDATE `sys_menu_items` SET `link`='modules/?r=events/calendar_sync/{content_id}' WHERE `set_name`='bx_events_view_actions_all' AND `name`='ical-export';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_more' AND `name`='edit-event-cover';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_all' AND `name`='edit-event-cover';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_events' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action`='check_allowed_fan_add' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'check_allowed_fan_add', @iHandler);
