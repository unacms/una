SET @sName = 'bx_timeline';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_browse' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` LIKE 'bx_timeline_extenals_every_%';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_extenals_every_public', '0', @iCategId, '_bx_timeline_option_extenals_every_public', 'digit', '', '', '', '', 40),
('bx_timeline_extenals_every_owner', '0', @iCategId, '_bx_timeline_option_extenals_every_owner', 'digit', '', '', '', '', 41),
('bx_timeline_extenals_every_feed', '0', @iCategId, '_bx_timeline_option_extenals_every_feed', 'digit', '', '', '', '', 42),
('bx_timeline_extenals_every_hot', '0', @iCategId, '_bx_timeline_option_extenals_every_hot', 'digit', '', '', '', '', 43),
('bx_timeline_extenals_every_feed_and_hot', '0', @iCategId, '_bx_timeline_option_extenals_every_feed_and_hot', 'digit', '', '', '', '', 44),
('bx_timeline_extenals_every_public_preload', '0', @iCategId, '_bx_timeline_option_extenals_every_public_preload', 'digit', '', '', '', '', 45),
('bx_timeline_extenals_every_owner_preload', '0', @iCategId, '_bx_timeline_option_extenals_every_owner_preload', 'digit', '', '', '', '', 46),
('bx_timeline_extenals_every_feed_preload', '0', @iCategId, '_bx_timeline_option_extenals_every_feed_preload', 'digit', '', '', '', '', 47),
('bx_timeline_extenals_every_hot_preload', '0', @iCategId, '_bx_timeline_option_extenals_every_hot_preload', 'digit', '', '', '', '', 48),
('bx_timeline_extenals_every_feed_and_hot_preload', '0', @iCategId, '_bx_timeline_option_extenals_every_feed_and_hot_preload', 'digit', '', '', '', '', 49);
