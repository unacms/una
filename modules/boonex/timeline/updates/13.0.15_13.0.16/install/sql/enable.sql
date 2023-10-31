SET @sName = 'bx_timeline';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_enable_content_own_actions';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_content_own_actions', '', @iCategId, '_bx_timeline_option_enable_content_own_actions', 'checkbox', '', '', '', '', 4);
