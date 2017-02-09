-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_events' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_events_per_page_browse', 'bx_events_short_date_format');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_events_per_page_browse', '24', @iCategId, '_bx_events_option_per_page_browse', 'digit', '', '', '', 11),
('bx_events_short_date_format', 'j M y', @iCategId, '_bx_events_option_short_date_format', 'digit', '', '', '', 22);


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_events_invite';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_invite', 'invite-to-event', '_bx_events_page_title_sys_invite_to_group', '_bx_events_page_title_invite_to_group', 'bx_events', 5, 2147483647, 1, 'page.php?i=invite-to-event', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_invite';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_invite', 1, 'bx_events', '_bx_events_page_block_title_invite_to_group', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:13:\"entity_invite\";}', 0, 0, 0);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_home' AND `title` IN ('_bx_events_page_block_title_featured_profiles');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_events_home', 1, 'bx_events', '_bx_events_page_block_title_featured_profiles', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:15:"browse_featured";s:6:"params";a:1:{i:0;s:7:"gallery";}}', 0, 1, 0);


-- MENU
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_more' AND `name` IN ('invite-to-event');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_actions_more', 'bx_events', 'invite-to-event', '_bx_events_menu_item_title_system_invite', '_bx_events_menu_item_title_invite', 'page.php?i=invite-to-event&id={content_id}', '', '', 'user-plus', '', 2147483647, 1, 0, 42);


-- VIEWS
UPDATE `sys_objects_view` SET `trigger_field_author`='author' WHERE `name`='bx_events';


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`='bx_events';
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_events', '1', '1', 'page.php?i=view-event-profile&id={object_id}', 'bx_events_data', 'id', 'author', 'featured', '', '');


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`event_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status` FROM `bx_events_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_events'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_events_administration';
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`event_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status` FROM `bx_events_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_events'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_events_common';

UPDATE `sys_grid_fields` SET `name`='name', `title`='_bx_events_grid_column_title_adm_name' WHERE `object`='bx_events_administration' AND `name`='event_name';
UPDATE `sys_grid_fields` SET `name`='name', `title`='_bx_events_grid_column_title_adm_name' WHERE `object`='bx_events_common' AND `name`='event_name';


-- ALERTS
UPDATE `sys_alerts` SET `action`='timeline_repost' WHERE `unit`='bx_events' AND `action`='timeline_share';


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name` IN ('bx_events_reminder');
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_events', '_bx_events_email_reminder', 'bx_events_reminder', '_bx_events_email_reminder_subject', '_bx_events_email_reminder_body');


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` IN ('bx_events_process_reminders');
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_events_process_reminders', '55 * * * *', 'BxEventsCronProcessReminders', 'modules/boonex/events/classes/BxEventsCronProcessReminders.php', '');