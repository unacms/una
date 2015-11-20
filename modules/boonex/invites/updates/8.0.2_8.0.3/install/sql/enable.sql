SET @sName = 'bx_invites';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_invites_enable_reg_by_inv' LIMIT 1;
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_invites_enable_reg_by_inv', 'on', @iCategId, '_bx_invites_option_enable_reg_by_inv', 'checkbox', '', '', '', 5);