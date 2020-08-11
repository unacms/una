-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_nexus_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` = 'bx_nexus_option_guest_pages';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_nexus_option_guest_pages', 'forgot-password,create-account,terms,privacy,contact,about,home', @iCategId, '_bx_nexus_option_guest_pages', 'text', '', '', '', '', 20);
