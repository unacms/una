SET @sName = 'bx_timeline';


-- SETTINGS
SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`='bx_timeline' LIMIT 1);

-- Category: General
UPDATE `sys_options_categories` SET `name`='bx_timeline_general', `caption`='_bx_timeline_options_category_general' WHERE `name`='bx_timeline';


-- Category: Browse
DELETE FROM `sys_options_categories` WHERE `name`='bx_timeline_browse';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_timeline_browse', '_bx_timeline_options_category_browse', 2);
SET @iCategId = LAST_INSERT_ID();

UPDATE `sys_options` SET `category_id`=@iCategId WHERE `name` IN ('bx_timeline_enable_show_all', 'bx_timeline_enable_jump_to_switcher', 'bx_timeline_events_per_page_profile', 'bx_timeline_events_per_page_account', 'bx_timeline_events_per_page_home', 'bx_timeline_events_per_page', 'bx_timeline_rss_length', 'bx_timeline_enable_infinite_scroll', 'bx_timeline_events_per_preload', 'bx_timeline_auto_preloads', 'bx_timeline_enable_hot', 'bx_timeline_hot_interval');


-- Category: Card
DELETE FROM `sys_options_categories` WHERE `name`='bx_timeline_card';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_timeline_card', '_bx_timeline_options_category_card', 3);
SET @iCategId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN ('bx_timeline_enable_dynamic_cards', 'bx_timeline_enable_brief_cards');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_dynamic_cards', '', @iCategId, '_bx_timeline_option_enable_dynamic_cards', 'checkbox', '', '', '', '', 1),
('bx_timeline_enable_brief_cards', '', @iCategId, '_bx_timeline_option_enable_brief_cards', 'checkbox', '', '', '', '', 2);

UPDATE `sys_options` SET `category_id`=@iCategId WHERE `name` IN ('bx_timeline_videos_autoplay', 'bx_timeline_preload_comments', 'bx_timeline_attachments_layout');


-- Category: Cache
DELETE FROM `sys_options_categories` WHERE `name`='bx_timeline_cache';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_timeline_cache', '_bx_timeline_options_category_cache', 4);
SET @iCategId = LAST_INSERT_ID();

UPDATE `sys_options` SET `category_id`=@iCategId WHERE `name` IN ('bx_timeline_enable_cache_item', 'bx_timeline_cache_item_engine', 'bx_timeline_cache_item_lifetime', 'bx_timeline_enable_cache_list');


-- Category: Post form
DELETE FROM `sys_options_categories` WHERE `name`='bx_timeline_post';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_timeline_post', '_bx_timeline_options_category_post', 5);
SET @iCategId = LAST_INSERT_ID();

UPDATE `sys_options` SET `category_id`=@iCategId WHERE `name` IN ('bx_timeline_enable_editor_toolbar');


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_alerts` WHERE (`unit`='account' AND `action` IN ('confirm', 'unconfirm') AND `handler_id`=@iHandler) OR (`unit`='profile' AND `action` IN ('approve', 'disapprove', 'activate', 'suspend') AND `handler_id`=@iHandler);
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'confirm', @iHandler),
('account', 'unconfirm', @iHandler),
('profile', 'approve', @iHandler),
('profile', 'disapprove', @iHandler),
('profile', 'activate', @iHandler),
('profile', 'suspend', @iHandler);