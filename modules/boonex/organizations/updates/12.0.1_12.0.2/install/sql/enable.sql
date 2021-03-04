-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_organizations' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_organizations_enable_subscribe_wo_join';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_organizations_enable_subscribe_wo_join', '', @iCategId, '_bx_orgs_option_enable_subscribe_wo_join', 'checkbox', '', '', '', '', 32);
