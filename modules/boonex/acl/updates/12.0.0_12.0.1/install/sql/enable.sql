SET @sName = 'bx_acl';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_acl_recurring_prioritize';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_acl_recurring_prioritize', 'on', @iCategId, '_bx_acl_option_recurring_prioritize', 'checkbox', '', '', '', '', 2);
