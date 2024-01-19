SET @sName = 'bx_timeline';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_searchable_fields';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_searchable_fields', 'title,description', @iCategId, '_bx_timeline_option_searchable_fields', 'list', '', '', '', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:21:"get_searchable_fields";}', 8);

SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`='bx_timeline' LIMIT 1);

DELETE FROM `sys_options_categories` WHERE `name`='bx_timeline_feed_hot';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_timeline_feed_hot', '_bx_timeline_options_category_feed_hot', 6);
SET @iCategId = LAST_INSERT_ID();

UPDATE `sys_options` set category_id=@iCategId WHERE `name`='bx_timeline_enable_hot' OR `name` LIKE 'bx_timeline_hot_%';

DELETE FROM `sys_options_categories` WHERE `name`='bx_timeline_feed_for_you';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_timeline_feed_for_you', '_bx_timeline_options_category_feed_for_you', 7);
SET @iCategId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` LIKE 'bx_timeline_for_you_%';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_for_you_sources', 'feed,hot,recom_friends,recom_subscriptions', @iCategId, '_bx_timeline_option_for_you_sources', 'list', 'Avail', '', '_bx_timeline_option_for_you_sources_err', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:29:"get_for_you_sources_checklist";}', 1),
('bx_timeline_for_you_threshold_recom_friends', '1', @iCategId, '_bx_timeline_option_for_you_threshold_recom_friends', 'digit', '', '', '', '', 10),
('bx_timeline_for_you_threshold_recom_subscriptions', '1', @iCategId, '_bx_timeline_option_for_you_threshold_recom_subscriptions', 'digit', '', '', '', '', 11);


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_timeline' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action`='save_setting' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler);
