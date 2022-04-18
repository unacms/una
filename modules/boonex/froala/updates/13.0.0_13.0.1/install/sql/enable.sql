-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_froala_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_froala_license_key';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_froala_license_key', '', @iCategId, '_bx_froala_option_license_key', 'digit', '', '', '', 1);
