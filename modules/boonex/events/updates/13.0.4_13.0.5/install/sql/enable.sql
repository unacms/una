-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_events' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_events_enable_subscribe_wo_join';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_events_enable_subscribe_wo_join', '', @iCategId, '_bx_events_option_enable_subscribe_wo_join', 'checkbox', '', '', '', 55);


-- MENUS
UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_events_menu_manage_tools' AND `name`='delete-with-content';


-- GRIDS
UPDATE `sys_grid_actions` SET `active`='0' WHERE `object`='bx_events_administration' AND `type`='bulk' AND `name`='delete_with_content';
UPDATE `sys_grid_actions` SET `active`='0' WHERE `object`='bx_events_common' AND `type`='bulk' AND `name`='delete_with_content';
