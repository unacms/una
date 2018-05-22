SET @sName = 'bx_invites';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_invites_requests_notifictaions';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_invites_requests_notifictaions', 'on', @iCategId, '_bx_invites_option_requests_notifictaions', 'checkbox', '', '', '', 6);
