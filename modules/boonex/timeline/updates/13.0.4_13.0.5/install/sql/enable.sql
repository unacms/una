SET @sName = 'bx_timeline';


-- PAGES
UPDATE `sys_objects_page` SET `url`='' WHERE `object`='bx_timeline_item';
UPDATE `sys_objects_page` SET `url`='' WHERE `object`='bx_timeline_item_brief';


-- SETTINGS
SET @iHdrIdComment = (SELECT `id` FROM `bx_timeline_handlers` WHERE `group`='comment' AND `type`='insert' AND `alert_unit`='comment' AND `alert_action`='added' LIMIT 1);
UPDATE `sys_options` SET `value`=CONCAT_WS(',', @iHdrIdComment) WHERE `name`='bx_timeline_events_hide';


SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_general' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_timeline_enable_hide_upon_delete';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_hide_upon_delete', '', @iCategId, '_bx_timeline_option_enable_hide_upon_delete', 'checkbox', '', '', '', '', 5);


SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_browse' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_timeline_enable_sort_by_reaction', 'bx_timeline_enable_sort_by_unread');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_sort_by_reaction', '', @iCategId, '_bx_timeline_option_enable_sort_by_reaction', 'checkbox', '', '', '', '', 3),
('bx_timeline_enable_sort_by_unread', '', @iCategId, '_bx_timeline_option_enable_sort_by_unread', 'checkbox', '', '', '', '', 4);


SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_card' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_timeline_title_chars', 'bx_timeline_title_chars_short');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_title_chars', '64', @iCategId, '_bx_timeline_option_title_chars', 'digit', '', '', '', '', 5),
('bx_timeline_title_chars_short', '32', @iCategId, '_bx_timeline_option_title_chars_short', 'digit', '', '', '', '', 6);


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_timeline_clean';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_timeline_clean', '0 0 * * *', 'BxTimelineCronClean', 'modules/boonex/timeline/classes/BxTimelineCronClean.php', '');
