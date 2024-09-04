SET @sName = 'bx_timeline';


-- SETTINGS
UPDATE `sys_options` SET `value`='0' WHERE `name`='bx_timeline_live_updates_length';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_feed_hot' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_hot_content_age_mux';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_hot_content_age_mux', '0', @iCategId, '_bx_timeline_option_hot_content_age_mux', 'digit', '', '', '', '', 30);
