-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_nexus_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_nexus_option_main_menu';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_nexus_option_main_menu', 'default', @iCategId, '_bx_nexus_option_main_menu', 'select', 'a:3:{s:6:"module";s:8:"bx_nexus";s:6:"method";s:14:"get_menus_list";s:6:"params";a:0:{}}', '', '', '', 40);
